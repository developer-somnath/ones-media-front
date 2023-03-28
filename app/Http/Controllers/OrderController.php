<?php
namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Countries;
use App\Models\States;
use App\Models\Order;
use App\Models\OrderAddress;
use App\Models\OrderItems;
use App\Models\OrderStatus;
use App\Models\Shows;
use App\Models\User;
use App\Models\SampleFiles;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use ZipArchive;
use App\Models\Transaction;
use App\Models\TransactionLog;
use App\Mail\Mailer;
use Mail;

class OrderController extends Controller
{
    public function checkout()
    {
        $cartsCount = auth()->user()->carts;
        if (!count($cartsCount) > 0):
            return redirect('my-cart');
        else:
            $title = "Checkout";
            $carts = Cart::item()->where('user_id', auth()->user()->id)->get();
            //dd($carts->first()->shows);
            /* $carts = Cart::leftJoin('shows as s', 'carts.item_id', '=', 's.id')
                ->leftJoin('sample_files as sf', 'carts.item_id', '=', 'sf.id')
                ->select('carts.*', 's.id as s_id', 's.category_id as s_category_id', 's.title as s_title', 's.image as s_image', 's.description as s_description', 's.instant_download_price as s_instant_download_price', 's.mp3_cd_price as s_mp3_cd_price', 's.status as s_status', 'sf.id as sf_id', 'sf.title as sf_title', 'sf.image as sf_image', 'sf.description as sf_description', 'sf.status as sf_status')
                ->where('carts.user_id', auth()->user()->id)
                ->get(); */

            /* $carts = DB::table('carts')
            ->leftJoin('shows', 'shows.id', '=', 'carts.item_id')
            ->leftJoin('sample_files', 'sample_files.id', '=', 'carts.item_id')
            ->where('user_id', auth()->user()->id)
            ->get(); */

            /*
             */
            //dd($carts);
            $countryList = Countries::whereIn('id', ['231', '4', '89', '177', '232', '240','38'])->get();
            return view('pages.loggedin_user.checkout', compact('title', 'carts', 'countryList'));

        endif;

    }

    public function placeOrder(Request $request)
    {
        if ($request->ajax()):
            $validated = $request->validate([
                'billing_first_name' => 'required|max:255|regex:/^[a-zA-ZÑñ\s]+$/',
                'billing_last_name' => 'required|max:255|regex:/^[a-zA-ZÑñ\s]+$/',
                'billing_email' => 'required|email',
                'billing_phone' => 'required',
                'billing_street_address' => 'required',
                'billing_address_line_2' => 'required',
                "billing_country_id" => "required",
                "billing_state_id" => "required",
                "billing_city" => "required",
                "billing_zip_code" => "required",
                'shipping_first_name' => 'required|max:255|regex:/^[a-zA-ZÑñ\s]+$/',
                'shipping_last_name' => 'required|max:255|regex:/^[a-zA-ZÑñ\s]+$/',
                'shipping_email' => 'required|email',
                'shipping_phone' => 'required',
                'shipping_street_address' => 'required',
                'shipping_address_line_2' => 'required',
                "shipping_country_id" => "required",
                "shipping_state_id" => "required",
                "shipping_city" => "required",
                "shipping_zip_code" => "required",
            ]);
            if ($validated):
                $request->session()->put('shippingBillingAddress', $request->input());
                return response()->json([
                    'status' => true,
                    'message' => 'Address Saved for this order!',
                    'redirect' => 'sample-file',
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
    public function payment(){
        $title="Payment";
        // return view('pages.loggedin_user.payment',compact('title'));
        return view('pages.loggedin_user.payment', compact('title'));

    }
    public function createOrderStripe(Request $request)
    {
        
        \Stripe\Stripe::setApiKey(env('STRIPE_SK'));
        $shippingData = $request->session()->get('shippingBillingAddress');
        $paymentIntent = \Stripe\PaymentIntent::create([
            'amount' => ((float)$this->orderAmount($shippingData)*100),
            'currency' => 'USD',
            'shipping' => [
                'name' => $shippingData['shipping_first_name'].' '.$shippingData['shipping_last_name'],
                'address' => [
                  'line1' => $shippingData['shipping_street_address'],
                  'postal_code' => $shippingData['shipping_zip_code'],
                  'city' => $shippingData['shipping_city'],
                  'state' => States::where('id',$shippingData['shipping_state_id'])->pluck('name')->first(),
                  'country' => Countries::where('id',$shippingData['shipping_country_id'])->pluck('short_code')->first(),
                ]
            ],
            'automatic_payment_methods' => [
                'enabled' => true,
            ],
            'description'=>"Order Purchase",
            // 'customer'=>auth()->user()->first_name.' '.auth()->user()->last_name
        ]);
        return response()->json([
                            'status' => true,
                            'message' => 'payment',
                            'redirect' => '',
                            'clientSecret'=>$paymentIntent->client_secret
                        ]);
        
    }
    private function orderAmount($shippingBillingAddress){
        $countryShortCode = Countries::where('id',$shippingBillingAddress['shipping_country_id'])->pluck('short_code')->first();
        $costCountry = "OTHER";
        if($countryShortCode==='US'){
            $costCountry = "US";
        }
        if($countryShortCode==='CA'){
            $costCountry = "CANADA";
        }
        $shippingPrice = DB::table('shipping_costs')
            ->where('id', '=', 1)
            ->where('country', '=', $costCountry)
            ->first();
        $carts = Cart::item()->where('user_id', auth()->user()->id)->get();
        //dd($carts);
        $total = 0;
        $discount = 0;
        $quantity = 0;

        foreach ($carts as $order):
            if ($order->type == 1):
                /* $price = $order->instant_download_price; */
                $price = $order->price;
            elseif($order->type == 2):
                /* $price = $order->mp3_cd_price; */
                $price = $order->price;
            else:
                /* $price = $order->mp3_cd_price; */
                $price = $order->price;
            endif;
            $total += $price * $order->quantity;
            $quantity += $order->quantity;
            $discount += $order->discount;
        endforeach;
        if ($quantity == 0 || $quantity == '' || $quantity == null):
            $shipping = number_format(0, 2);
        elseif ($quantity == 1):
            $shipping = $shippingPrice->price_for_single_qty;
        elseif ($quantity == 2):
            $shipping = $shippingPrice->price_for_double_qty;
        else:
            $shipping = $shippingPrice->price_for_more_than_three_or_equal;
        endif;
        return $allTotal = $total + $shipping;
    }
    public function orderCreate(Request $request)
    {
        // dd($request->all());
            $shippingBillingAddress = $request->session()->get('shippingBillingAddress');
            $countryShortCode = Countries::where('id',$shippingBillingAddress['shipping_country_id'])->pluck('short_code')->first();
            $costCountry = "OTHER";
            if($countryShortCode==='US'){
                $costCountry = "US";
            }
            if($countryShortCode==='CA'){
                $costCountry = "CANADA";
            }
            $shippingPrice = DB::table('shipping_costs')
                ->where('id', '=', 1)
                ->where('country', '=', $costCountry)
                ->first();
                $orderCount = Order::select('id')->count();
                $orderAltId = 1;
                if ($orderCount > 0):
                    $orderAltId = $orderCount + 1;
                endif;
                $orderAltId = sprintf('%010d', $orderAltId);
                $orderAltId = 'ODR-' . $orderAltId;
                /* $carts = DB::table('carts')
                    ->join('shows', 'shows.id', '=', 'carts.item_id')
                    ->where('user_id', auth()->user()->id)
                    ->get(); */
                $carts = Cart::item()->where('user_id', auth()->user()->id)->get();
                //dd($carts);
                $total = 0;
                $discount = 0;
                $quantity = 0;

                foreach ($carts as $order):
                    if ($order->type == 1):
                        /* $price = $order->instant_download_price; */
                        $price = $order->price;
                    elseif($order->type == 2):
                        /* $price = $order->mp3_cd_price; */
                        $price = $order->price;
                    else:
                        /* $price = $order->mp3_cd_price; */
                        $price = $order->price;
                    endif;
                    $total += $price * $order->quantity;
                    $quantity += $order->quantity;
                    $discount += $order->discount;
                endforeach;
                if ($quantity == 0 || $quantity == '' || $quantity == null):
                    $shipping = number_format(0, 2);
                elseif ($quantity == 1):
                    $shipping = $shippingPrice->price_for_single_qty;
                elseif ($quantity == 2):
                    $shipping = $shippingPrice->price_for_double_qty;
                else:
                    $shipping = $shippingPrice->price_for_more_than_three_or_equal;
                endif;
                $allTotal = $total + $shipping;
                if($request->filled('payment_intent')):
                    \Stripe\Stripe::setApiKey(env('STRIPE_SK'));
                    $checkPayment = \Stripe\PaymentIntent::retrieve($request->get('payment_intent'),[]);
                    if($checkPayment->status==='succeeded'):
                        $paymentMethodCheck = \Stripe\PaymentMethod::retrieve($checkPayment->payment_method,[]);
                        $order = Order::create([
                            'user_id' => auth()->user()->id,
                            'order_alt_id' => $orderAltId,
                            'oder_amount' => $allTotal,
                            'paid_amount' => $allTotal,
                            'shipping_cost' => $shipping,
                            'discount_amount' => $discount,
                            'payment_status' => 'P',
                        ]);
                        $TransactionData = Transaction::create([
                            'payment_intend_id'=>$request->get('payment_intent'),
                            'payment_intent_client_secret'=>$checkPayment->client_secret,
                            'order_id'=>$order->id,
                            'amount'=>(float)$checkPayment->amount/100,
                            'payment_status'=>'P',
                            'payment_method'=>$paymentMethodCheck->type,
                        ]);
                        TransactionLog::create([
                            'transaction_id'=>$TransactionData->id,
                            'request'=>json_encode($request->all()),
                            'response'=>json_encode(['payment'=>$checkPayment,'method'=>$paymentMethodCheck])
                        ]);
                        if ($order): foreach ($carts as $orderItem):
                                //dd($orderItem);
                                if ($orderItem->type == 1):
                                    $type = 1;
                                    $amount = $orderItem->price;
                                    $quantity = $orderItem->quantity;
                                    $discount = $orderItem->discount;

                                elseif($orderItem->type == 2):
                                    $type = 2;
                                    $amount = $orderItem->price;
                                    $quantity = $orderItem->quantity;
                                    $discount = $orderItem->discount;
                                else:
                                    $type = 3;
                                    $amount = $orderItem->price;
                                    $quantity = $orderItem->quantity;
                                    $discount = $orderItem->discount;

                                endif;

                                OrderItems::create([
                                    'order_id' => $order->id,
                                    'item_id' => $orderItem->item_id,
                                    'quantity' => $quantity,
                                    'discount_amount' => $discount,
                                    'type' => $type,
                                    'product_type' => $orderItem->product_type,
                                    'item_amount' => $amount * $quantity,
                                    'paid_amount' => $amount * $quantity,
                                ]);

                            endforeach;
                            OrderStatus::create([
                                'order_id' => $order->id,
                                'order_status' => '1',
                            ]);
                            $shippingData = $request->session()->get('shippingBillingAddress');
                            OrderAddress::create([
                                'order_id' => $order->id,
                                "first_name" => $shippingData['billing_first_name'],
                                "last_name" => $shippingData['billing_last_name'],
                                "email" => $shippingData['billing_email'],
                                "phone" => $shippingData['billing_phone'],
                                "street_address" => $shippingData['billing_street_address'],
                                "address_line_2" => $shippingData['billing_address_line_2'],
                                "country_id" => $shippingData['billing_country_id'],
                                "state_id" => $shippingData['billing_state_id'],
                                "city" => $shippingData['billing_city'],
                                "zip_code" => $shippingData['billing_zip_code'],
                                "type"=>"B"

                            ]);
                            OrderAddress::create([
                                'order_id' => $order->id,
                                "first_name" => $shippingData['shipping_first_name'],
                                "last_name" => $shippingData['shipping_last_name'],
                                "email" => $shippingData['shipping_email'],
                                "phone" => $shippingData['shipping_phone'],
                                "street_address" => $shippingData['shipping_street_address'],
                                "address_line_2" => $shippingData['shipping_address_line_2'],
                                "country_id" => $shippingData['shipping_country_id'],
                                "state_id" => $shippingData['shipping_state_id'],
                                "city" => $shippingData['shipping_city'],
                                "zip_code" => $shippingData['shipping_zip_code'],
                                "type"=>"S"

                            ]);
                            auth()->user()->carts()->delete();
                            $request->session()->forget('shippingBillingAddress');
                            $admin = User::where('user_type','=','1')->first();
                            $mailDetails = [
                                'email' => auth()->user()->email,
                                'subject' => 'Order Confirmation',
                                'html' => 'emails.order-confirmation',
                                'userName' => auth()->user()->first_name . ' ' . auth()->user()->last_name,
                                'order_no' => $orderAltId,
                                'all_total' => $order->oder_amount,
                            ];
                            Mail::to(auth()->user()->email)->send(new Mailer($mailDetails));
                            $adminmailDetails = [
                                'user_email' => auth()->user()->email,
                                'admin_email' => $admin->email,
                                'subject' => 'Admin Received a order confirmation mail',
                                'html' => 'emails.admin-order-confirmation',
                                'userName' => auth()->user()->first_name . ' ' . auth()->user()->last_name,
                                'order_no' => $orderAltId,
                                'all_total' => $order->oder_amount,
                            ];
                            Mail::to($admin->email)->send(new Mailer($adminmailDetails));
                            // return response()->json([
                            //     'status' => true,
                            //     'message' => 'Payment Received Successfully !!',
                            //     'redirect' => 'order-history',
                            // ]);
                            return redirect('order-summery/'.$order->id);
                        else:
                            return response()->json([
                                'status' => false,
                                'message' => 'Something Went Wrong !!',
                                'redirect' => '',
                            ]);

                        endif;
                    else:
                        print "Payment Faild try again later!";exit(0);
                    endif;
                else:
                    return response()->json([
                        'status' => false,
                        'message' => 'Payment Faild !!',
                        'redirect' => '',
                    ]);
                endif;
                
    }
    public function orderHistory(Request $request)
    {
        $title = "Order - History";
        return view('pages.loggedin_user.orders.order-history', compact('title'));

    }

    public function ajaxDataTable(Request $request)
    {
        if ($request->ajax()):
            $draw = $request->input('draw');
            $start = $request->input("start");
            $rowperpage = $request->input("length"); // Rows display per page
            $columnIndexArr = $request->input('order');
            $columnNameArr = $request->input('columns');
            $orderArr = $request->input('order');
            $searchArr = $request->input('search');
            $columnIndex = $columnIndexArr[0]['column']; // Column index
            $columnName = $columnNameArr[$columnIndex]['data']; // Column name
            $columnSortOrder = $orderArr[0]['dir']; // asc or desc
            $searchValue = $searchArr['value']; // Search value
            $totalRecords = Order::select('count(*) as allcount')->where('status', '!=', '3')->count();
            $totalRecordswithFilter = Order::select('count(*) as allcount')->where('status', '!=', '3')->count();
            // ->when($published, function ($q) use ($published) {
            //     return $q->where('published', 1);
            // })
            // Fetch records
            $records = Order::where('status', '!=', '3')->where('user_id', '=', auth()->user()->id)
                ->orderBy($columnName, $columnSortOrder)
                ->skip($start)
                ->take($rowperpage)
                ->get();
            $tempArr = [];

            foreach ($records as $key => $value):
                if ($value->payment_status === 'P'):
                    $paymentStatus = '<span class="badge badge-primary">Paid</span>';
                else:
                    $paymentStatus = '<span class="badge badge-danger">Unpaid</a>';
                endif;
                if ($value->shipment_status === 'C'):
                    $shippingStatus = '<span class="badge badge-primary">Complete</span>';
                else:
                    $shippingStatus = '<span class="badge badge-danger">Pending</a>';
                endif;
                $action = '<a href="' . (url("order-details/" . $value->id)) . '" class="btn btn-info"><i class="fa fa-eye" aria-hidden="true"></i></a>
																										                <a href="javascript:void(0)" id="' . ($value->id) . '" data-table="orders" data-status="3" data-key="id" data-id="' . ($value->id) . '" class="btn btn-danger change-status"><i class="fa fa-trash" aria-hidden="true"></i></a>';
                $image = '';

                $tempArr[] = [
                    'id' => ($key + 1),
                    'order_alt_id' => $value->order_alt_id,
                    'name' => $value->user->first_name . ' ' . $value->user->last_name,
                    'email' => $value->user->email,
                    'phone' => $value->user->phone,
                    'oder_amount' => $value->oder_amount,
                    'payment_status' => $paymentStatus,
                    'shipment_status' => $shippingStatus,
                    'action' => $action,
                ];
            endforeach;
            return response()->json([
                'draw' => $draw,
                'recordsTotal' => $totalRecords,
                'recordsFiltered' => $totalRecordswithFilter,
                'data' => $tempArr,
            ]);
        endif;
    }
    public function details($id)
    {
        $title = "Order - Details";
        $details = Order::find($id);
        return view('pages.loggedin_user.orders.order-details', compact('title', 'details'));
    }
    public function createZipFile($id)
    {
        $show = Shows::find($id);
        $zipFileName = $show->title . ".zip";
        $zip = new ZipArchive;
        if ($zip->open(public_path($zipFileName), ZipArchive::CREATE) === TRUE):
            // Add files to the zip file
            foreach ($show->audios as $audio):
                $serverAdudioFilename = env('AUDIO_FILE_PATH') . '/categories/' . $show->category->slug . '/' . $audio->file_name;
                $ordinalFileName = basename($audio->file_original_name);
                $zip->addFile($serverAdudioFilename, $ordinalFileName);
            endforeach;
            $zip->close();
        endif;
        return response()->download(public_path($zipFileName));
        //return $zipFileName;
    }

    public function purchasedRecordings()
    {
        $title = "Purchased Recording";
        $purchaseDetails = OrderItems::selectRaw('order_has_items.*,orders.user_id,orders.status')
            ->join('orders', 'orders.id', '=', 'order_has_items.order_id', 'inner')->
            where('order_has_items.type', '=', 1)->where('orders.user_id', '=', auth()->user()->id)->where('orders.status', '!=', '3')->get();
        //dd($purchaseDetails);

        return view('pages.loggedin_user.orders.purchased-recording', compact('title', 'purchaseDetails'));

    }

    public function summery($id)
    {
        $title="Order Summery";
        $info=Order::find($id);
        return view('pages.loggedin_user.order-summery', compact('title', 'info'));  
    }

    public function orderAddress()
    {
        $title = "Order Address"; 
        $countryList = Countries::whereIn('id', ['231', '4', '89', '177', '232', '240','38'])->get();
        return view('pages.loggedin_user.address', compact('title','countryList'));
    }
}
