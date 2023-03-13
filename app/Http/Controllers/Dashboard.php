<?php
namespace App\Http\Controllers;

use App\Models\Countries;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class Dashboard extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()):
            $validated = $request->validate([
                "first_name" => "required",
                "last_name" => "required",
                "phone" => "required",
                "country_id" => "required",
                "state_id" => "required",
                "city" => "required",
                "zip_code" => "required",
            ]);

            if (!$validated):

                return response()->json([
                    'status' => false,
                    'message' => 'All data are not present in the request!',
                    'redirect' => '',
                ]);

            else:
                $user = Auth::user();
                $user->first_name = $request->input('first_name');
                $user->last_name = $request->input('last_name');
                $user->phone = $request->input('phone');
                $user->email = $request->input('email');
                $user->phone = $request->input('phone');
                $user->country_id = $request->input('country_id');
                $user->state_id = $request->input('state_id');
                $user->city = $request->input('city');
                $user->zip_code = $request->input('zip_code');
                $user->save();
                return response()->json([
                    'status' => true,
                    'message' => 'Profile Updated Successfully!!',
                    'redirect' => 'my-account',
                ]);

            endif;
        endif;

        $title = "My Account";
        $countryList = Countries::whereIn('id', ['231', '4', '89', '177', '232', '240'])->get();
        return view('pages.loggedin_user.my-account', compact('title', 'countryList'));
    }
    public function changePassword(Request $request)
    {
        if ($request->ajax()):
            $validated = $request->validate([
                'oldpassword' => 'required',
                'newpassword' => 'required',
                'confirmpassword' => 'required',
            ]);
            if (!$validated):

                return response()->json([
                    'status' => false,
                    'message' => 'All data are not present in the request!',
                    'redirect' => '',
                ]);

            else:
                if (Hash::check($request->get('oldpassword'), Auth::user()->password)):

                    if ($request->get('newpassword') == $request->get('confirmpassword')):
                        $user = Auth::user();
                        $user->password = Hash::make($request->get('newpassword'));
                        $user->save();
                        return response()->json([
                            'status' => true,
                            'message' => 'Password Updated Successfully!!',
                            'redirect' => 'change-password',
                        ]);
                    else:
                        return response()->json([
                            'status' => false,
                            'message' => 'New Password & Confirm Password  missmatch!!',
                            'redirect' => '',
                        ]);
                    endif;
                else:
                    return response()->json([
                        'status' => false,
                        'message' => 'Your current password does not matches with the password!!',
                        'redirect' => '',
                    ]);
                endif;

            endif;
        endif;

        $title = "Change Password";
        return view('pages.loggedin_user.change-password', compact('title'));
    }

    public function updateMyCartQuantity(Request $request)
    {
        if ($request->ajax()) {
            //dd($request->all());
            if ($request->item_id && $request->quantity) {
                if (auth()->user()) {
                    $isQuantityUpdated = auth()->user()->carts()->where('item_id', $request->item_id)->where('product_type', '=', '1')->update([
                        'quantity' => $request->quantity
                    ]);

                    return response()->json([
                        'status' => true,
                        'message' => 'Quantity Updated Successfully!',
                        'redirect' => 'my-cart',
                    ]);

                }
            }
        } else {
            abort(403);
        }
    }

}
