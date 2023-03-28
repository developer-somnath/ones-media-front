<?php
namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Countries;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Sale;
use Session;
use App\Mail\Mailer;
use Mail;

class Authentication extends Controller
{
    public function login()
    {
        if (Auth::check()):
            return redirect('my-account');
        endif;
        $title = "Signup - Login";
        $countryList = Countries::whereIn('id', ['231', '4', '89', '177', '232', '240','38'])->get();
        return view('pages.login', compact('title', 'countryList'));
    }

    public function register(Request $request)
    {
        if ($request->ajax()):
            $validated = $request->validate([
                'first_name' => 'required|max:255|regex:/^[a-zA-ZÑñ\s]+$/',
                'last_name' => 'required|max:255|regex:/^[a-zA-ZÑñ\s]+$/',
                'user_email' => 'required|email',
                'user_phone' => 'required',
                'user_password' => 'required',
                'confirmpassword' => 'required',
                "country_id" => "required",
                "state_id" => "required",
                "city" => "required",
                "zip_code" => "required",
            ]);
            if ($validated):

                if (User::where('email', '=', $request->input('user_email'))->where('status', '!=', 3)->exists()):
                    return response()->json([
                        'status' => false,
                        'message' => 'Email already exists!',
                        'redirect' => '',
                    ]);
                endif;
                if (User::where('phone', '=', $request->input('user_phone'))->where('status', '!=', 3)->exists()):
                    return response()->json([
                        'status' => false,
                        'message' => 'Phone already exists!',
                        'redirect' => '',
                    ]);
                endif;
                if ($request->input('user_password') != $request->input('confirmpassword')):
                    return response()->json([
                        'status' => false,
                        'message' => 'Password & Confirm Password  missmatch!!',
                        'redirect' => '',
                    ]);
                endif;

                User::create([
                    "first_name" => $request->input('first_name'),
                    "last_name" => $request->input('last_name'),
                    "email" => $request->input('user_email'),
                    "phone" => $request->input('user_phone'),
                    "password" => Hash::make($request->input('user_password')),
                    "user_type" => '2',
                    "country_id" => $request->input('country_id'),
                    "state_id" => $request->input('state_id'),
                    "city" => $request->input('city'),
                    "zip_code" => $request->input('zip_code'),

                ]);
                $mailDetails = [
                'email' => $request->input('user_email'),
                'subject' => 'Welcome to' .' '. env('APP_NAME'),
                'html' => 'emails.customer-registration',
                'userName' => $request->input('first_name').' '.$request->input('last_name'),
                'password' => $request->input('user_password'),
                ];
                // dd($mailDetails);
                Mail::to($request->input('user_email'))->send(new Mailer($mailDetails));
            

                return response()->json([
                    'status' => true,
                    'message' => 'User created successfully!',
                    'redirect' => 'login',
                ]);

            else:
                return response()->json([
                    'status' => false,
                    'message' => 'All data are not present in the request!',
                    'redirect' => '',
                ]);
            endif;
        endif;

    }

    public function userCheck(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);
        if ($validated):
            if (Auth::attempt($request->only('email', 'password'))):
                if (Auth::user()->user_type == 2):
                    /* if (Session::has('cart')) : */
                    //$cart = new Cart;
                    $cart = session()->get('cart', []);
                    if (!empty($cart)):
                        //dd($cart);
//                         $cart = Session::get('cart');
                        foreach ($cart as $id => $details):
                            /* dd($cart); */
                            $cartData = Cart::where('item_id', $details['id'])->where('user_id', auth()->user()->id)->first();
                            if(empty($cartData)):
                            $cartModel = new Cart();
                            $cartModel['user_id'] = Auth::user()->id;
                            $cartModel['item_id'] = $details['id'];
                            $cartModel['type'] = $details['type'];
                            $cartModel['price'] = $details['price'];
                            $cartModel['discount'] = $details['discount'];
                            $cartModel['quantity'] = $details['quantity'];
                            $cartModel['product_type'] = 1;
                            $cartModel->save();
                             endif;
                        /* else:
                         */
                        endforeach;
                    endif;

                    return response()->json([
                        'status' => true,
                        'message' => 'Logged In suceessfully!',
                        'redirect' => 'login',
                    ]);
                else:
                    Session::flush();
                    Auth::logout();
                    return response()->json([
                        'status' => false,
                        'message' => 'Unauthorized Access!',
                        'redirect' => 'login',
                    ]);
                endif;
            endif;
            return response()->json([
                'status' => false,
                'message' => 'Invalid Email or Password!',
                'redirect' => '',
            ]);
        else:
            return response()->json([
                'status' => false,
                'message' => 'All data are not present in the request!',
                'redirect' => '',
            ]);
        endif;
    }

    public function logout()
    {
        Session::flush();
        Auth::logout();
        return redirect('/');
    }

    public function stateListByCountryId(Request $request)
    {
        if ($request->ajax()):
            $stateList = \DB::table('states')->where('country_id', $request->input('countryId'))->get();
            if (count($stateList) > 0):
                return response()->json([
                    'status' => true,
                    'message' => 'Data available!',
                    'data' => $stateList,
                ]);
            endif;
            return response()->json([
                'status' => false,
                'message' => 'No Data found!',
                'redirect' => '',
            ]);
        endif;
    }

    public function myCart()
    {
        $title = "My-Cart";
        $carts = Cart::item()->where('user_id', auth()->user()->id)->get();

        //dd($carts);
        return view('pages.loggedin_user.my-cart', compact('title', 'carts'));

    }
    public function myWishlist()
    {
        $title = "My-Wishlist";
        $currentDate = date('Y-m-d');
        $wishlists = auth()->user()->wishlists;
        $checkSalesToday = Sale::where('type', '1')->whereDate('sale_date', $currentDate)->first();
        $checkSalesDateRange = Sale::where('type', '2')->whereDate('start_date', '<=', $currentDate)
            ->whereDate('end_date', '>=', $currentDate)->first();

        //dd($wishlists);
        return view('pages.loggedin_user.my-wishlist', compact('title', 'wishlists', 'checkSalesToday', 'checkSalesDateRange'));

    }

}
