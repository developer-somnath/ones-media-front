<?php

namespace App\Http\Controllers;

use DB;
use App\Models\Cms;
use App\Models\Cart;
use App\Models\Sale;
use App\Models\Shows;
use App\Models\Banner;
use App\Models\Wishlist;
use App\Models\Categories;
use App\Models\SampleFiles;
use App\Models\FreeDownload;
use Illuminate\Http\Request;
//use Illuminate\Support\Facades\DB;
use App\Models\OfferManagement;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class Home extends Controller
{
    public function index(Request $request)
    {
        /* $filter = !empty($_REQUEST['filter']) ? $_REQUEST['filter'] : ''; */

        $filter = !empty($request->filter) ? $request->filter : '';
        $currentDate = date('Y-m-d');
        $yesterday = date('Y-m-d', strtotime("-1 days"));
        $twodaysAgo = date('Y-m-d', strtotime("-2 days"));
        //dd($currentDate);
        $salesToday =
        Shows::select("shows.*", \DB::raw("sales.discount_amount as discount_amount,sales.type as sales_type,sales.discount_type as discount_type,categories.name as categoryName,categories.slug as categorySlug,categories.status as categoryStatus"))
            ->join("sales", \DB::raw("FIND_IN_SET(shows.id,sales.applicable_shows)"), ">", \DB::raw("'0'"))
            ->join('categories', 'categories.id', '=', 'shows.category_id', 'inner')->
            where('categories.status', '=', '1')->where('shows.status', '=', '1')
            ->where('sales.sale_date', '=', $currentDate)
            ->where('sales.status', '=', '1')
            ->where('sales.type', '=', '1')
        /* ->where('offer_management.end_date', '>=', $current_date) */
            ->orderby('shows.id', 'asc')->get();
        //dd($salesToday);
        /* $offerManagementList = OfferManagement::where('status', '=', '1')->where('start_date', '<=', $current_date)
        ->where('end_date', '>=', $current_date)->get(); */
        /*  $offerId = [];
        foreach ($offerManagementList as $key => $value) {
        $eachArray = explode(',', $value->applicable_shows);
        $offerId = array_merge($offerId, $eachArray);
        }
        $uniqueArr = array_unique($offerId);
        $todayShowList = Shows::selectRaw('shows.*,categories.name as categoryName,categories.slug as categorySlug,categories.status as categoryStatus')
        ->join('categories', 'categories.id', '=', 'shows.category_id', 'inner')->
        where('categories.status', '=', '1')->where('shows.status', '=', '1')->orderby('shows.id', 'asc')->whereIn('shows.id', $uniqueArr)->get(); */

        $checkSalesToday = Sale::where('type', '1')->whereDate('sale_date', $currentDate)->first();
        $checkSalesDateRange = Sale::where('type', '2')->whereDate('start_date', '<=', $currentDate)
            ->whereDate('end_date', '>=', $currentDate)->first();
        $categoryList = Categories::selectRaw('categories.*')
            ->where('status', '=', '1')
            ->orderBy('name', 'asc')
            ->get();
        $bannerList = Banner::selectRaw('banners.*')
            ->where('status', '=', '1')
            ->get();
        /*  *//* $productListPopular = DB::table('order_has_items')
        ->join('shows', 'shows.id', '=', 'order_has_items.item_id')
        ->select('shows.title','shows.id','shows.image','order_has_items.item_id',
        DB::raw('SUM(order_has_items.quantity) as total'))
        ->where('order_has_items.product_type','=','1')
        ->groupBy('shows.id','shows.title','order_has_items.item_id')
        ->orderBy('total', 'desc')
        ->limit(3)
        ->get(); */
        //dd($topsales);

        $productListPopular = DB::table('shows')
            ->join('order_has_items', 'shows.id', '=', 'order_has_items.item_id', 'inner')
            ->join('categories', 'categories.id', '=', 'shows.category_id', 'inner')
            ->selectRaw('shows.*,categories.name as categoryName,categories.slug as categorySlug,categories.status as categoryStatus,sum(order_has_items.quantity) total,order_has_items.item_id,order_has_items.product_type')
            ->where('order_has_items.product_type', '=', '1')
            ->groupBy('shows.id')
            ->orderBy('total', 'desc')
            ->take(3)
            ->get();

        //dd($productListPopular);

        /* $productListPopular = Shows::selectRaw('shows.*,categories.name as categoryName,categories.slug as categorySlug,categories.status as categoryStatus')
        ->join('categories', 'categories.id', '=', 'shows.category_id', 'inner')->
        where('categories.status', '=', '1')->where('shows.status', '=', '1')->orderby('shows.id', 'desc')->take(3)->get(); */
        if ($filter) {
            $productListAll = Shows::selectRaw('shows.*,categories.name as categoryName,categories.slug as categorySlug,categories.status as categoryStatus')
                ->join('categories', 'categories.id', '=', 'shows.category_id', 'inner')->
                where('categories.status', '=', '1')->where('shows.status', '=', '1')->where('shows.title', 'LIKE', "$filter%")->orderby('shows.id', 'asc')->get();

        } else {
            $productListAll = Shows::selectRaw('shows.*,categories.name as categoryName,categories.slug as categorySlug,categories.status as categoryStatus')
                ->join('categories', 'categories.id', '=', 'shows.category_id', 'inner')->
                where('categories.status', '=', '1')->where('shows.status', '=', '1')->orderby('shows.id', 'asc')->get();
        }
        $offerList = OfferManagement::where('status', '=', '1')->get();
        $todayFreeDownloadList = FreeDownload::whereDate('download_date', $currentDate)->where('status', '=', '1')->get();
        $yesterdayFreeDownloadList = FreeDownload::whereDate('download_date', $yesterday)->where('status', '=', '1')->get();
        $twodaysAgoFreeDownloadList = FreeDownload::whereDate('download_date', $twodaysAgo)->where('status', '=', '1')->get();
        //dd($twodaysAgoFreeDownloadList);
        $title = "Home";
        return view('pages.home', compact('title', 'categoryList', 'bannerList', 'productListPopular', 'productListAll', 'offerList', 'salesToday', 'checkSalesToday', 'checkSalesDateRange', 'todayFreeDownloadList', 'yesterdayFreeDownloadList', 'twodaysAgoFreeDownloadList'));
    }

    public function showsAll(Request $request)
    {
        $title = "All Shows";
        $filter = !empty($request->filter) ? $request->filter : '';
        $currentDate = date('Y-m-d');
        $checkSalesToday = Sale::where('type', '1')->whereDate('sale_date', $currentDate)->first();
        $checkSalesDateRange = Sale::where('type', '2')->whereDate('start_date', '<=', $currentDate)
            ->whereDate('end_date', '>=', $currentDate)->first();

        $productListPopular = DB::table('shows')
            ->join('order_has_items', 'shows.id', '=', 'order_has_items.item_id', 'inner')
            ->join('categories', 'categories.id', '=', 'shows.category_id', 'inner')
            ->selectRaw('shows.*,categories.name as categoryName,categories.slug as categorySlug,categories.status as categoryStatus,sum(order_has_items.quantity) total,order_has_items.item_id,order_has_items.product_type')
            ->where('order_has_items.product_type', '=', '1')
            ->groupBy('shows.id')
            ->orderBy('total', 'desc')
            ->take(3)
            ->get();

        if ($filter) {
            $productList = Shows::selectRaw('shows.*,categories.name as categoryName,categories.slug as categorySlug,categories.status as categoryStatus')
                ->join('categories', 'categories.id', '=', 'shows.category_id', 'inner')->
                where('categories.status', '=', '1')->where('shows.status', '=', '1')->where('shows.title', 'LIKE', "$filter%")->orderby('shows.id', 'asc')->get();

        } else {
            $productList = Shows::selectRaw('shows.*,categories.name as categoryName,categories.slug as categorySlug,categories.status as categoryStatus')
                ->join('categories', 'categories.id', '=', 'shows.category_id', 'inner')->
                where('categories.status', '=', '1')->where('shows.status', '=', '1')->orderby('shows.id', 'asc')->get();
        }

        return view('pages.shows-all', compact('title', 'productList', 'productListPopular', 'checkSalesToday', 'checkSalesDateRange'));
    }
    public function searchAll(Request $request)
    {
        $title = "Search Shows";
        $q = !empty($request->q) ? $request->q : '';
        $currentDate = date('Y-m-d');
        $checkSalesToday = Sale::where('type', '1')->whereDate('sale_date', $currentDate)->first();
        $checkSalesDateRange = Sale::where('type', '2')->whereDate('start_date', '<=', $currentDate)
            ->whereDate('end_date', '>=', $currentDate)->first();

        if ($q != '') {
            $productList = Shows::selectRaw('shows.*,categories.name as categoryName,categories.slug as categorySlug,categories.status as categoryStatus')
                ->join('categories', 'categories.id', '=', 'shows.category_id', 'inner')->
                where('categories.status', '=', '1')->where('shows.status', '=', '1')->where('shows.title', 'LIKE', "$q%")->orderby('shows.id', 'asc')->get();

        } else {
            $productList = Shows::selectRaw('shows.*,categories.name as categoryName,categories.slug as categorySlug,categories.status as categoryStatus')
                ->join('categories', 'categories.id', '=', 'shows.category_id', 'inner')->
                where('categories.status', '=', '1')->where('shows.status', '=', '1')->orderby('shows.id', 'asc')->get();
        }

        return view('pages.search', compact('title', 'productList', 'checkSalesToday', 'checkSalesDateRange'));
    }

    public function newShows(Request $request)
    {
        $title = "New Shows";
        $filter = !empty($request->filter) ? $request->filter : '';
        $currentDate = date('Y-m-d');
        $checkSalesToday = Sale::where('type', '1')->whereDate('sale_date', $currentDate)->first();
        $checkSalesDateRange = Sale::where('type', '2')->whereDate('start_date', '<=', $currentDate)
            ->whereDate('end_date', '>=', $currentDate)->first();

        $productListPopular = DB::table('shows')
            ->join('order_has_items', 'shows.id', '=', 'order_has_items.item_id', 'inner')
            ->join('categories', 'categories.id', '=', 'shows.category_id', 'inner')
            ->selectRaw('shows.*,categories.name as categoryName,categories.slug as categorySlug,categories.status as categoryStatus,sum(order_has_items.quantity) total,order_has_items.item_id,order_has_items.product_type')
            ->where('order_has_items.product_type', '=', '1')
            ->groupBy('shows.id')
            ->orderBy('total', 'desc')
            ->take(3)
            ->get();

        if ($filter) {
            $productList = Shows::selectRaw('shows.*,categories.name as categoryName,categories.slug as categorySlug,categories.status as categoryStatus')
                ->join('categories', 'categories.id', '=', 'shows.category_id', 'inner')->
                where('categories.status', '=', '1')->where('shows.status', '=', '1')->where('shows.title', 'LIKE', "$filter%")->orderby('shows.id', 'desc')->get();

        } else {
            $productList = Shows::selectRaw('shows.*,categories.name as categoryName,categories.slug as categorySlug,categories.status as categoryStatus')
                ->join('categories', 'categories.id', '=', 'shows.category_id', 'inner')->
                where('categories.status', '=', '1')->where('shows.status', '=', '1')->orderby('shows.id', 'desc')->get();
        }

        return view('pages.new-shows', compact('title', 'productList', 'productListPopular', 'checkSalesToday', 'checkSalesDateRange'));
    }
    public function showByCategory(Request $request)
    {
        $title = $request->slug;
        $filter = !empty($request->filter) ? $request->filter : '';
        $currentDate = date('Y-m-d');
        $checkSalesToday = Sale::where('type', '1')->whereDate('sale_date', $currentDate)->first();
        $checkSalesDateRange = Sale::where('type', '2')->whereDate('start_date', '<=', $currentDate)
            ->whereDate('end_date', '>=', $currentDate)->first();
        $productListPopular = DB::table('shows')
            ->join('order_has_items', 'shows.id', '=', 'order_has_items.item_id', 'inner')
            ->join('categories', 'categories.id', '=', 'shows.category_id', 'inner')
            ->selectRaw('shows.*,categories.name as categoryName,categories.slug as categorySlug,categories.status as categoryStatus,sum(order_has_items.quantity) total,order_has_items.item_id,order_has_items.product_type')
            ->where('categories.slug', '=', $request->slug)
            ->where('order_has_items.product_type', '=', '1')
            ->groupBy('shows.id')
            ->orderBy('total', 'desc')
            ->take(3)
            ->get();

        if ($filter) {
            $productList = Shows::selectRaw('shows.*,categories.name as categoryName,categories.slug as categorySlug,categories.status as categoryStatus')
                ->join('categories', 'categories.id', '=', 'shows.category_id', 'inner')->
                where('categories.slug', '=', $request->slug)->where('categories.status', '=', '1')->where('shows.status', '=', '1')->where('shows.title', 'LIKE', "$filter%")->orderby('shows.id', 'asc')->get();

        } else {
            $productList = Shows::selectRaw('shows.*,categories.name as categoryName,categories.slug as categorySlug,categories.status as categoryStatus')
                ->join('categories', 'categories.id', '=', 'shows.category_id', 'inner')->
                where('categories.slug', '=', $request->slug)->where('categories.status', '=', '1')->where('shows.status', '=', '1')->orderby('shows.id', 'asc')->get();

        }
        return view('pages.shows-by-category', compact('title', 'productList', 'productListPopular', 'checkSalesToday', 'checkSalesDateRange'));
    }

    public function showByYear(Request $request)
    {
        $title = $request->year;
        $filter = !empty($request->filter) ? $request->filter : '';
        $currentDate = date('Y-m-d');
        $checkSalesToday = Sale::where('type', '1')->whereDate('sale_date', $currentDate)->first();
        $checkSalesDateRange = Sale::where('type', '2')->whereDate('start_date', '<=', $currentDate)
            ->whereDate('end_date', '>=', $currentDate)->first();

        if ($filter) {
            $productList = Shows::selectRaw('shows.*,categories.name as categoryName,categories.slug as categorySlug,categories.status as categoryStatus')
                ->join('categories', 'categories.id', '=', 'shows.category_id', 'inner')->
                where('shows.show_start_year', '=', $request->year)->where('categories.status', '=', '1')->where('shows.status', '=', '1')->where('shows.title', 'LIKE', "$filter%")->orderby('shows.id', 'asc')->get();

        } else {
            $productList = Shows::selectRaw('shows.*,categories.name as categoryName,categories.slug as categorySlug,categories.status as categoryStatus')
                ->join('categories', 'categories.id', '=', 'shows.category_id', 'inner')->
                where('shows.show_start_year', '=', $request->year)->where('categories.status', '=', '1')->where('shows.status', '=', '1')->orderby('shows.id', 'asc')->get();

        }
        return view('pages.show-by-year', compact('title', 'productList', 'checkSalesToday', 'checkSalesDateRange'));
    }

    public function showDetails(Request $request, $id)
    {
        $currentDate = date('Y-m-d');
        $checkSalesToday = Sale::where('type', '1')->whereDate('sale_date', $currentDate)->first();
        $checkSalesDateRange = Sale::where('type', '2')->whereDate('start_date', '<=', $currentDate)
            ->whereDate('end_date', '>=', $currentDate)->first();

        $productViewList = Shows::selectRaw('shows.*,categories.name as categoryName,categories.slug as categorySlug,categories.status as categoryStatus')
            ->join('categories', 'categories.id', '=', 'shows.category_id', 'inner')->
            where('shows.id', '=', $id)->first();
        //dd($productViewList);
        $title = "Show-Details";
        return view('pages.show-details', compact('title', 'productViewList', 'checkSalesToday', 'checkSalesDateRange'));

    }

    public function cart(Request $request)
    {
        $title = "Cart";
        return view('pages.cart', compact('title'));
    }

    public function addToCart(Request $request)
    {
        if ($request->ajax()) {
            //dd($request->all());
            $type = !empty($request->type) ? $request->type : '';
            //dd($type);
            $id = $request->id;
            $show = Shows::findOrFail($id);
            $currentDate = date('Y-m-d');
            $checkSalesToday = Sale::where('type', '1')->whereDate('sale_date', $currentDate)->first();
            $checkSalesDateRange = Sale::where('type', '2')->whereDate('start_date', '<=', $currentDate)
                ->whereDate('end_date', '>=', $currentDate)->first();

            $applicableShows = explode(',', $checkSalesToday?->applicable_shows);
// \DB::enableQueryLog();
            $applicableCategoryToShow = [];
            if (!is_null($checkSalesDateRange)):
                $applicableCategoryToShow = \DB::table('shows')
                    ->selectRaw('GROUP_CONCAT("",id) AS applicableShows')
                    ->whereIn('category_id', explode(',', $checkSalesDateRange?->applicable_categories))
                    ->pluck('applicableShows')
                    ->first();
                $applicableCategoryToShow = explode(',', $applicableCategoryToShow);
            endif;

            /* $offerCheck = \DB::table('offer_management')
            ->selectRaw('`discount_amount`,`type`')
            ->whereRaw(' DATE(`start_date`)<="' . $currentDate . '" AND DATE(`end_date`)>="' . $currentDate . '" AND FIND_IN_SET(' . $id . ',`applicable_shows`) AND `status`="1"')
            ->first(); */
            /*  $flag = 0;
            if (!empty($offerCheck)):
            $flag = 1;
            endif; */
            $flag = 0;
            $flag1 = 0;
            if (!empty($applicableCategoryToShow) && in_array($id, $applicableCategoryToShow)):
                $flag = 1;
            endif;
            if (!empty($applicableShows) && in_array($id, $applicableShows)):
                $flag1 = 1;
            endif;

            if ($type == 'instant_download') {
                if ($flag == 1) {

                    if ($flag1 == '1') {

                        if ($checkSalesToday?->discount_type === 'P') {
                            $discountVal = ((float) $show->instant_download_price * (float) $checkSalesToday?->discount_amount) / 100;
                            $priceDetails = (float) $show->instant_download_price - (float) $discountVal;

                        } elseif ($checkSalesToday?->discount_type === 'F') {
                            $discountVal = (float) $checkSalesToday?->discount_amount;
                            $priceDetails = (float) $show->instant_download_price - (float) $discountVal;
                        }

                        $show_name = $show->title . '(' . 'Instant Download' . ')';
                        $price = number_format($priceDetails, 2);
                        $discount = number_format($discountVal, 2);

                    } else {
                        if ($show->instant_download_price >= $checkSalesDateRange?->min_price_range && $show->instant_download_price <= $checkSalesDateRange?->max_price_range) {
                            if ($checkSalesDateRange?->discount_type === 'P') {
                                $discountVal = ((float) $show->instant_download_price * (float) $checkSalesDateRange?->discount_amount) / 100;

                                $priceDetails = (float) $show->instant_download_price - (float) $discountVal;

                            } elseif ($checkSalesDateRange?->discount_type === 'F') {
                                $discountVal = (float) $checkSalesDateRange?->discount_amount;
                                $priceDetails = (float) $show->instant_download_price - (float) $discountVal;
                            }
                            $show_name = $show->title . '(' . 'Instant Download' . ')';
                            $price = number_format($priceDetails, 2);
                            $discount = number_format($discountVal, 2);

                        } else {
                            $price = $show->instant_download_price;
                            $show_name = $show->title . '(' . 'Instant Download' . ')';
                            $discount = number_format(0, 2);

                        }
                        /*  $show_name = $show->title . '(' . 'Instant Download' . ')'; */
                        /* $discountVal = (float) $offerCheck->discount_amount;
                    $priceDetails = (float) $show->instant_download_price - (float) $discountVal; */

                    }

                    /* $show_name = $show->title . '(' . 'Instant Download' . ')';
                $price = number_format($priceDetails, 2);
                $discount = number_format($discountVal, 2);
                 */
                } else {
                    $price = $show->instant_download_price;
                    $show_name = $show->title . '(' . 'Instant Download' . ')';
                    $discount = number_format(0, 2);
                }

            } elseif ($type == 'mp3_cd') {
                if ($flag == 1) {

                    if ($flag1 == '1') {

                        if ($checkSalesToday?->discount_type === 'P') {
                            $discountVal = ((float) $show->mp3_cd_price * (float) $checkSalesToday?->discount_amount) / 100;
                            $priceDetails = (float) $show->mp3_cd_price - (float) $discountVal;

                        } elseif ($checkSalesToday?->discount_type === 'F') {
                            $discountVal = (float) $checkSalesToday?->discount_amount;
                            $priceDetails = (float) $show->mp3_cd_price - (float) $discountVal;
                        }

                        $show_name = $show->title . '(' . $show->no_of_mp3_cds . ' ' . 'Mp3 Cd' . ')';
                        $price = number_format($priceDetails, 2);
                        $discount = number_format($discountVal, 2);

                    } else {
                        if ($show->mp3_cd_price >= $checkSalesDateRange?->min_price_range && $show->mp3_cd_price <= $checkSalesDateRange?->max_price_range) {
                            if ($checkSalesDateRange?->discount_type === 'P') {
                                $discountVal = ((float) $show->mp3_cd_price * (float) $checkSalesDateRange?->discount_amount) / 100;

                                $priceDetails = (float) $show->mp3_cd_price - (float) $discountVal;

                            } elseif ($checkSalesDateRange?->discount_type === 'F') {
                                $discountVal = (float) $checkSalesDateRange?->discount_amount;
                                $priceDetails = (float) $show->mp3_cd_price - (float) $discountVal;
                            }
                            $show_name = $show->title . '(' . $show->no_of_mp3_cds . ' ' . 'Mp3 Cd' . ')';
                            $price = number_format($priceDetails, 2);
                            $discount = number_format($discountVal, 2);

                        } else {
                            $price = $show->mp3_cd_price;
                            $show_name = $show->title . '(' . $show->no_of_mp3_cds . ' ' . 'Mp3 Cd' . ')';
                            $discount = number_format(0, 2);

                        }
                        /*  $show_name = $show->title . '(' . 'Instant Download' . ')'; */
                        /* $discountVal = (float) $offerCheck->discount_amount;
                    $priceDetails = (float) $show->instant_download_price - (float) $discountVal; */

                    }

                    /* $show_name = $show->title . '(' . 'Instant Download' . ')';
                $price = number_format($priceDetails, 2);
                $discount = number_format($discountVal, 2);
                 */
                } else {
                    $price = $show->mp3_cd_price;
                    $show_name = $show->title . '(' . $show->no_of_mp3_cds . ' ' . 'Mp3 Cd' . ')';
                    $discount = number_format(0, 2);
                }

                /* $price = $show->mp3_cd_price;
            $show_name = $show->title . '(' . $show->no_of_mp3_cds . ' ' . 'Mp3 Cd' . ')'; */
            } else {
                if ($flag == 1) {

                    if ($flag1 == '1') {

                        if ($checkSalesToday?->discount_type === 'P') {
                            $discountVal = ((float) $show->instant_download_price * (float) $checkSalesToday?->discount_amount) / 100;
                            $priceDetails = (float) $show->instant_download_price - (float) $discountVal;

                        } elseif ($checkSalesToday?->discount_type === 'F') {
                            $discountVal = (float) $checkSalesToday?->discount_amount;
                            $priceDetails = (float) $show->instant_download_price - (float) $discountVal;
                        }

                        $show_name = $show->title . '(' . 'Instant Download' . ')';
                        $price = number_format($priceDetails, 2);
                        $discount = number_format($discountVal, 2);

                    } else {
                        if ($show->instant_download_price >= $checkSalesDateRange?->min_price_range && $show->instant_download_price <= $checkSalesDateRange?->max_price_range) {
                            if ($checkSalesDateRange?->discount_type === 'P') {
                                $discountVal = ((float) $show->instant_download_price * (float) $checkSalesDateRange?->discount_amount) / 100;

                                $priceDetails = (float) $show->instant_download_price - (float) $discountVal;

                            } elseif ($checkSalesDateRange?->discount_type === 'F') {
                                $discountVal = (float) $checkSalesDateRange?->discount_amount;
                                $priceDetails = (float) $show->instant_download_price - (float) $discountVal;
                            }
                            $show_name = $show->title . '(' . 'Instant Download' . ')';
                            $price = number_format($priceDetails, 2);
                            $discount = number_format($discountVal, 2);

                        } else {
                            $price = $show->instant_download_price;
                            $show_name = $show->title . '(' . 'Instant Download' . ')';
                            $discount = number_format(0, 2);

                        }
                        /*  $show_name = $show->title . '(' . 'Instant Download' . ')'; */
                        /* $discountVal = (float) $offerCheck->discount_amount;
                    $priceDetails = (float) $show->instant_download_price - (float) $discountVal; */

                    }

                    /* $show_name = $show->title . '(' . 'Instant Download' . ')';
                $price = number_format($priceDetails, 2);
                $discount = number_format($discountVal, 2);
                 */
                } else {
                    $price = $show->instant_download_price;
                    $show_name = $show->title . '(' . 'Instant Download' . ')';
                    $discount = number_format(0, 2);
                }

            }

            if (auth()->user()) {
                $carts = Cart::where('item_id', $id)->where('user_id', auth()->user()->id)->where('product_type', '=', '1')->first();
                if (!empty($carts)) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Product already exist in cart !!',
                        'redirect' => '',
                    ]);

                } else {
                    Cart::create([
                        'user_id' => auth()->user()->id,
                        'item_id' => $id,
                        "quantity" => 1,
                        "price" => $price,
                        "discount" => $discount,
                        'type' => ($type == '' || $type == 'instant_download') ? 1 : 2,
                        'product_type' => 1,

                    ]);
                    return response()->json([
                        'status' => true,
                        'message' => 'Product added to the cart successfully!',
                        'redirect' => 'my-cart',
                    ]);

                    /* return auth()->user()->carts()->create([
                'product_id' => $attributes['product_id'],
                'attributes' => $attributes['attributes'],
                'quantity' => $attributes['quantity'],
                ]); */
                }

            } else {
                $cart = session()->get('cart', []);
                //dd($cart);

                if (isset($cart[$id])) {
                    /* $cart[$id]['quantity']++; */
                    session()->put('cart', $cart);
                    return response()->json([
                        'status' => false,
                        'message' => 'Product already exist in cart !!',
                        'redirect' => '',
                    ]);

                } else {
                    $cart[$id] = [
                        "id" => $show->id,
                        "name" => $show_name,
                        "quantity" => 1,
                        "price" => $price,
                        "discount" => $discount,
                        'type' => ($type == '' || $type == 'instant_download') ? 1 : 2,
                        "image" => $show->image,
                    ];
                    session()->put('cart', $cart);
                    //dd($cartDetails);
                    $cartDetails = session()->get('cart', []);
                    //dd($cartDetails);

                    return response()->json([
                        'status' => true,
                        'message' => 'Product added to the cart successfully!',
                        'redirect' => 'cart',
                    ]);

                }
                /*  session()->put('cart', $cart);
                session()->get('cart', []);
                 */

                /*  $is_cart = session()->get('cart', []);
            if ($is_cart):
            return response()->json([
            'status' => true,
            'message' => 'Product added to the cart successfully!',
            'redirect' => 'my-cart',
            ]);
            else:
            return response()->json([
            'status' => false,
            'message' => 'Something went wrong!',
            'redirect' => '',
            ]);
            endif; */
            }

        }

    }

    public function filterByCart(Request $request)
    {
        if ($request->ajax()):
            //dd($request->all());
            $type = !empty($request->type) ? $request->type : '';
            //dd($type);
            $id = $request->item_id;
            //dd($id);
            $show = Shows::findOrFail($id);
            $currentDate = date('Y-m-d');
            $checkSalesToday = Sale::where('type', '1')->whereDate('sale_date', $currentDate)->first();
            $checkSalesDateRange = Sale::where('type', '2')->whereDate('start_date', '<=', $currentDate)
                ->whereDate('end_date', '>=', $currentDate)->first();
            $applicableShows = explode(',', $checkSalesToday?->applicable_shows);
// \DB::enableQueryLog();
            $applicableCategoryToShow = [];
            if (!is_null($checkSalesDateRange)):
                $applicableCategoryToShow = \DB::table('shows')
                    ->selectRaw('GROUP_CONCAT("",id) AS applicableShows')
                    ->whereIn('category_id', explode(',', $checkSalesDateRange?->applicable_categories))
                    ->pluck('applicableShows')
                    ->first();
                $applicableCategoryToShow = explode(',', $applicableCategoryToShow);
            endif;
            $flag = 0;
            $flag1 = 0;
            if (!empty($applicableCategoryToShow) && in_array($id, $applicableCategoryToShow)):
                $flag = 1;
            endif;
            if (!empty($applicableShows) && in_array($id, $applicableShows)):
                $flag1 = 1;
            endif;

            if ($type == 1) {
                if ($flag == 1) {

                    if ($flag1 == '1') {

                        if ($checkSalesToday?->discount_type === 'P') {
                            $discountVal = ((float) $show->instant_download_price * (float) $checkSalesToday?->discount_amount) / 100;
                            $priceDetails = (float) $show->instant_download_price - (float) $discountVal;

                        } elseif ($checkSalesToday?->discount_type === 'F') {
                        $discountVal = (float) $checkSalesToday?->discount_amount;
                        $priceDetails = (float) $show->instant_download_price - (float) $discountVal;
                    }

                    $show_name = $show->title . '(' . 'Instant Download' . ')';
                    $price = number_format($priceDetails, 2);
                    $discount = number_format($discountVal, 2);

                } else {
                    if ($show->instant_download_price >= $checkSalesDateRange?->min_price_range && $show->instant_download_price <= $checkSalesDateRange?->max_price_range) {
                        if ($checkSalesDateRange?->discount_type === 'P') {
                            $discountVal = ((float) $show->instant_download_price * (float) $checkSalesDateRange?->discount_amount) / 100;

                            $priceDetails = (float) $show->instant_download_price - (float) $discountVal;

                        } elseif ($checkSalesDateRange?->discount_type === 'F') {
                            $discountVal = (float) $checkSalesDateRange?->discount_amount;
                            $priceDetails = (float) $show->instant_download_price - (float) $discountVal;
                        }
                        $show_name = $show->title . '(' . 'Instant Download' . ')';
                        $price = number_format($priceDetails, 2);
                        $discount = number_format($discountVal, 2);

                    } else {
                        $price = $show->instant_download_price;
                        $show_name = $show->title . '(' . 'Instant Download' . ')';
                        $discount = number_format(0, 2);

                    }

                }

            } else {
                $price = $show->instant_download_price;
                $show_name = $show->title . '(' . 'Instant Download' . ')';
                $discount = number_format(0, 2);
            }

        } elseif ($type == 2) {
            if ($flag == 1) {

                if ($flag1 == '1') {

                    if ($checkSalesToday?->discount_type === 'P') {
                        $discountVal = ((float) $show->mp3_cd_price * (float) $checkSalesToday?->discount_amount) / 100;
                        $priceDetails = (float) $show->mp3_cd_price - (float) $discountVal;

                    } elseif ($checkSalesToday?->discount_type === 'F') {
                        $discountVal = (float) $checkSalesToday?->discount_amount;
                        $priceDetails = (float) $show->mp3_cd_price - (float) $discountVal;
                    }

                    $show_name = $show->title . '(' . $show->no_of_mp3_cds . ' ' . 'Mp3 Cd' . ')';
                    $price = number_format($priceDetails, 2);
                    $discount = number_format($discountVal, 2);

                } else {
                    if ($show->mp3_cd_price >= $checkSalesDateRange?->min_price_range && $show->mp3_cd_price <= $checkSalesDateRange?->max_price_range) {
                        if ($checkSalesDateRange?->discount_type === 'P') {
                            $discountVal = ((float) $show->mp3_cd_price * (float) $checkSalesDateRange?->discount_amount) / 100;

                            $priceDetails = (float) $show->mp3_cd_price - (float) $discountVal;

                        } elseif ($checkSalesDateRange?->discount_type === 'F') {
                            $discountVal = (float) $checkSalesDateRange?->discount_amount;
                            $priceDetails = (float) $show->mp3_cd_price - (float) $discountVal;
                        }
                        $show_name = $show->title . '(' . $show->no_of_mp3_cds . ' ' . 'Mp3 Cd' . ')';
                        $price = number_format($priceDetails, 2);
                        $discount = number_format($discountVal, 2);

                    } else {
                        $price = $show->mp3_cd_price;
                        $show_name = $show->title . '(' . $show->no_of_mp3_cds . ' ' . 'Mp3 Cd' . ')';
                        $discount = number_format(0, 2);

                    }
                    /*  $show_name = $show->title . '(' . 'Instant Download' . ')'; */
                    /* $discountVal = (float) $offerCheck->discount_amount;
                $priceDetails = (float) $show->instant_download_price - (float) $discountVal; */

                }

                /* $show_name = $show->title . '(' . 'Instant Download' . ')';
            $price = number_format($priceDetails, 2);
            $discount = number_format($discountVal, 2);
             */
            } else {
                $price = $show->mp3_cd_price;
                $show_name = $show->title . '(' . $show->no_of_mp3_cds . ' ' . 'Mp3 Cd' . ')';
                $discount = number_format(0, 2);
            }

/* $price = $show->mp3_cd_price;
$show_name = $show->title . '(' . $show->no_of_mp3_cds . ' ' . 'Mp3 Cd' . ')'; */

        } else {
            if ($flag == 1) {

                if ($flag1 == '1') {

                    if ($checkSalesToday?->discount_type === 'P') {
                        $discountVal = ((float) $show->instant_download_price * (float) $checkSalesToday?->discount_amount) / 100;
                        $priceDetails = (float) $show->instant_download_price - (float) $discountVal;

                    } elseif ($checkSalesToday?->discount_type === 'F') {
                        $discountVal = (float) $checkSalesToday?->discount_amount;
                        $priceDetails = (float) $show->instant_download_price - (float) $discountVal;
                    }

                    $show_name = $show->title . '(' . 'Instant Download' . ')';
                    $price = number_format($priceDetails, 2);
                    $discount = number_format($discountVal, 2);

                } else {
                    if ($show->instant_download_price >= $checkSalesDateRange?->min_price_range && $show->instant_download_price <= $checkSalesDateRange?->max_price_range) {
                        if ($checkSalesDateRange?->discount_type === 'P') {
                            $discountVal = ((float) $show->instant_download_price * (float) $checkSalesDateRange?->discount_amount) / 100;

                            $priceDetails = (float) $show->instant_download_price - (float) $discountVal;

                        } elseif ($checkSalesDateRange?->discount_type === 'F') {
                            $discountVal = (float) $checkSalesDateRange?->discount_amount;
                            $priceDetails = (float) $show->instant_download_price - (float) $discountVal;
                        }
                        $show_name = $show->title . '(' . 'Instant Download' . ')';
                        $price = number_format($priceDetails, 2);
                        $discount = number_format($discountVal, 2);

                    } else {
                        $price = $show->instant_download_price;
                        $show_name = $show->title . '(' . 'Instant Download' . ')';
                        $discount = number_format(0, 2);

                    }
                    /*  $show_name = $show->title . '(' . 'Instant Download' . ')'; */
                    /* $discountVal = (float) $offerCheck->discount_amount;
                $priceDetails = (float) $show->instant_download_price - (float) $discountVal; */

                }

                /* $show_name = $show->title . '(' . 'Instant Download' . ')';
            $price = number_format($priceDetails, 2);
            $discount = number_format($discountVal, 2);
             */
            } else {
                $price = $show->instant_download_price;
                $show_name = $show->title . '(' . 'Instant Download' . ')';
                $discount = number_format(0, 2);
            }

        }

        //$oldCart = Session::has('cart') ? Session::get('cart') : null;
        $cart = session()->get('cart');
        //dd($cart);
        //dd($cart[$id]["quantity"]) ;
        $cart[$id]["price"] = $price;
        $cart[$id]["discount"] = $discount;
        $cart[$id]["type"] = $type;

        /* $cart[$id] = [
        "id" => $show->id,
        "name" => $show_name,
        "quantity" => 1,
        "price" => $price,
        "discount" => $discount,
        'type' => $type,
        "image" => $show->image,
        ]; */

        //$cart[$id]["type"] = $type;
        //dd($cart);
        session()->put('cart', $cart);
        $datas = session()->get('cart', []);
        json_encode($datas);
        //dd($datas);
        //$cart = new Cart($oldCart);

        /* $carts = $oldCart->update($id, [
        'quantity' => 1,
        'type' => $type
        ]); */

        /* for ($i = 0; $i < count($cart); $i++) {
        $cart[$i]['type'] = $type[$i];
        } */

        /* for ($i = 0; $i < count($id); $i++) {
        $cart->update($id[$i], $type[$i]);

        } */

        /* $datas = Session::put('cart', $carts);
        dd($datas); */

        /* $cart = session()->get('cart');
        if (isset($cart[$id])) {
        $cart[$id]["type"] = $type;
        session()->put('cart', $cart);
        $datas = session()->get('cart', []);
        dd($datas)
        json_encode($datas);

        } */
        /* $cart[$id]["id"] = $show->id;
        $cart[$id]["type"] = $type;
        $cart[$id]["name"] = $show_name;
        $cart[$id]["quantity"] = 1;
        $cart[$id]["price"] = $price;
        $cart[$id]["image"] = $show->image; */
        /* session()->put('cart', $cart);
        $datas = session()->get('cart', []);
        json_encode($datas); */
        endif;

    }

    public function filterByMyCart(Request $request)
    {
        if ($request->ajax()):
            //dd($request->all());
            $type = !empty($request->type) ? $request->type : '';
            //dd($type);
            $id = $request->item_id;
            //dd($id);
            $show = Shows::findOrFail($id);
            //dd($show);
            /* $currentDate = date('Y-m-d');
            $offerCheck = \DB::table('offer_management')
            ->selectRaw('`discount_amount`,`type`')
            ->whereRaw(' DATE(`start_date`)<="' . $currentDate . '" AND DATE(`end_date`)>="' . $currentDate . '" AND FIND_IN_SET(' . $id . ',`applicable_shows`) AND `status`="1"')
            ->first();
            $flag = 0;
            if (!empty($offerCheck)):
            $flag = 1;
            endif; */

            $currentDate = date('Y-m-d');
            $checkSalesToday = Sale::where('type', '1')->whereDate('sale_date', $currentDate)->first();
            $checkSalesDateRange = Sale::where('type', '2')->whereDate('start_date', '<=', $currentDate)
                ->whereDate('end_date', '>=', $currentDate)->first();
            $applicableShows = explode(',', $checkSalesToday?->applicable_shows);
// \DB::enableQueryLog();
            $applicableCategoryToShow = [];
            if (!is_null($checkSalesDateRange)):
                $applicableCategoryToShow = \DB::table('shows')
                    ->selectRaw('GROUP_CONCAT("",id) AS applicableShows')
                    ->whereIn('category_id', explode(',', $checkSalesDateRange?->applicable_categories))
                    ->pluck('applicableShows')
                    ->first();
                $applicableCategoryToShow = explode(',', $applicableCategoryToShow);
            endif;
            $flag = 0;
            $flag1 = 0;
            if (!empty($applicableCategoryToShow) && in_array($id, $applicableCategoryToShow)):
                $flag = 1;
            endif;
            if (!empty($applicableShows) && in_array($id, $applicableShows)):
                $flag1 = 1;
            endif;

            if ($type == 1) {
                if ($flag == 1) {

                    if ($flag1 == '1') {

                        if ($checkSalesToday?->discount_type === 'P') {
                            $discountVal = ((float) $show->instant_download_price * (float) $checkSalesToday?->discount_amount) / 100;
                            $priceDetails = (float) $show->instant_download_price - (float) $discountVal;

                        } elseif ($checkSalesToday?->discount_type === 'F') {
                        $discountVal = (float) $checkSalesToday?->discount_amount;
                        $priceDetails = (float) $show->instant_download_price - (float) $discountVal;
                    }

                    $show_name = $show->title . '(' . 'Instant Download' . ')';
                    $price = number_format($priceDetails, 2);
                    $discount = number_format($discountVal, 2);

                } else {
                    if ($show->instant_download_price >= $checkSalesDateRange?->min_price_range && $show->instant_download_price <= $checkSalesDateRange?->max_price_range) {
                        if ($checkSalesDateRange?->discount_type === 'P') {
                            $discountVal = ((float) $show->instant_download_price * (float) $checkSalesDateRange?->discount_amount) / 100;

                            $priceDetails = (float) $show->instant_download_price - (float) $discountVal;

                        } elseif ($checkSalesDateRange?->discount_type === 'F') {
                            $discountVal = (float) $checkSalesDateRange?->discount_amount;
                            $priceDetails = (float) $show->instant_download_price - (float) $discountVal;
                        }
                        $show_name = $show->title . '(' . 'Instant Download' . ')';
                        $price = number_format($priceDetails, 2);
                        $discount = number_format($discountVal, 2);

                    } else {
                        $price = $show->instant_download_price;
                        $show_name = $show->title . '(' . 'Instant Download' . ')';
                        $discount = number_format(0, 2);

                    }

                }

            } else {
                $price = $show->instant_download_price;
                $show_name = $show->title . '(' . 'Instant Download' . ')';
                $discount = number_format(0, 2);
            }

        } elseif ($type == 2) {
            if ($flag == 1) {

                if ($flag1 == '1') {

                    if ($checkSalesToday?->discount_type === 'P') {
                        $discountVal = ((float) $show->mp3_cd_price * (float) $checkSalesToday?->discount_amount) / 100;
                        $priceDetails = (float) $show->mp3_cd_price - (float) $discountVal;

                    } elseif ($checkSalesToday?->discount_type === 'F') {
                        $discountVal = (float) $checkSalesToday?->discount_amount;
                        $priceDetails = (float) $show->mp3_cd_price - (float) $discountVal;
                    }

                    $show_name = $show->title . '(' . $show->no_of_mp3_cds . ' ' . 'Mp3 Cd' . ')';
                    $price = number_format($priceDetails, 2);
                    $discount = number_format($discountVal, 2);

                } else {
                    if ($show->mp3_cd_price >= $checkSalesDateRange?->min_price_range && $show->mp3_cd_price <= $checkSalesDateRange?->max_price_range) {
                        if ($checkSalesDateRange?->discount_type === 'P') {
                            $discountVal = ((float) $show->mp3_cd_price * (float) $checkSalesDateRange?->discount_amount) / 100;

                            $priceDetails = (float) $show->mp3_cd_price - (float) $discountVal;

                        } elseif ($checkSalesDateRange?->discount_type === 'F') {
                            $discountVal = (float) $checkSalesDateRange?->discount_amount;
                            $priceDetails = (float) $show->mp3_cd_price - (float) $discountVal;
                        }
                        $show_name = $show->title . '(' . $show->no_of_mp3_cds . ' ' . 'Mp3 Cd' . ')';
                        $price = number_format($priceDetails, 2);
                        $discount = number_format($discountVal, 2);

                    } else {
                        $price = $show->mp3_cd_price;
                        $show_name = $show->title . '(' . $show->no_of_mp3_cds . ' ' . 'Mp3 Cd' . ')';
                        $discount = number_format(0, 2);

                    }
                    /*  $show_name = $show->title . '(' . 'Instant Download' . ')'; */
                    /* $discountVal = (float) $offerCheck->discount_amount;
                $priceDetails = (float) $show->instant_download_price - (float) $discountVal; */

                }

                /* $show_name = $show->title . '(' . 'Instant Download' . ')';
            $price = number_format($priceDetails, 2);
            $discount = number_format($discountVal, 2);
             */
            } else {
                $price = $show->mp3_cd_price;
                $show_name = $show->title . '(' . $show->no_of_mp3_cds . ' ' . 'Mp3 Cd' . ')';
                $discount = number_format(0, 2);
            }

/* $price = $show->mp3_cd_price;
$show_name = $show->title . '(' . $show->no_of_mp3_cds . ' ' . 'Mp3 Cd' . ')'; */

        }

        if (auth()->user()) {
            $carts = Cart::where('item_id', $id)->where('user_id', auth()->user()->id)->where('product_type', '=', '1')->update([
                'price' => $price,
                'discount' => $discount,
                'type' => $type,
            ]);
            json_encode($carts);

            /* if (!empty($carts)) {
        return response()->json([
        'status' => false,
        'message' => 'Product already exist in cart !!',
        'redirect' => '',
        ]);

        } else {
        Cart::create([
        'user_id' => auth()->user()->id,
        'item_id' => $id,
        'quantity' => 1,
        'type' => ($type == '' || $type == 'instant_download') ? 1 : 2,

        ]);
        return response()->json([
        'status' => true,
        'message' => 'Product added to the cart successfully!',
        'redirect' => 'my-cart',
        ]);

        } */

        }
        endif;

    }

    public function removeFromCart(Request $request)
    {
        if ($request->ajax()):
            $id = $request->id;
            $product_type = $request->product_type;

            //dd($request->all());
            if (auth()->user()):
                $isCartDeleted = auth()->user()->carts()->where('item_id', $id)->where('product_type', $product_type)->delete();
                if ($isCartDeleted):
                    return response()->json([
                        'status' => true,
                        'message' => 'Product removed from cart successfully !!',
                        'redirect' => 'my-cart',
                    ]);

                else:
                    return response()->json([
                        'status' => false,
                        'message' => 'Something went wrong  !!',
                        'redirect' => '',
                    ]);

                endif;

            else:
                $cart = session()->get('cart');
                if (isset($cart[$request->id])):
                    unset($cart[$request->id]);
                    session()->put('cart', $cart);
                    $datas = collect(session()->get('cart', []));
                    //dd($datas);
                    return response()->json([
                        'status' => true,
                        'message' => 'Product removed from cart successfully !!',
                        'redirect' => 'cart',
                    ]);

                else:
                    return response()->json([
                        'status' => false,
                        'message' => 'Something went wrong  !!',
                        'redirect' => '',
                    ]);

                endif;

            endif;

        endif;

    }

    public function addToWishlist(Request $request)
    {
        //dd($request->all());
        if ($request->ajax()) {
            $id = $request->id;
            $show = Shows::findOrFail($id);
            $wishListData = Wishlist::where('item_id', $show->id)->where('user_id', auth()->user()->id)->first();
            if (!empty($wishListData)) {
                return response()->json([
                    'status' => false,
                    'message' => 'Product already exist in wishlist !!',
                    'redirect' => '',
                ]);

            } else {
                Wishlist::create([
                    'user_id' => auth()->user()->id,
                    'item_id' => $show->id,
                    'type' => 1,

                ]);
                return response()->json([
                    'status' => true,
                    'message' => 'Product added to the wishlist successfully!',
                    'redirect' => 'my-wishlist',
                ]);

            }

        }
    }

    public function addToCartFromWishlist(Request $request)
    {
        if ($request->ajax()) {
            //dd($request->all());
            $type = !empty($request->type) ? $request->type : '';
            $id = $request->id;
            $show = Shows::findOrFail($id);
            /*  $currentDate = date('Y-m-d');
            $offerCheck = \DB::table('offer_management')
            ->selectRaw('`discount_amount`,`type`')
            ->whereRaw(' DATE(`start_date`)<="' . $currentDate . '" AND DATE(`end_date`)>="' . $currentDate . '" AND FIND_IN_SET(' . $id . ',`applicable_shows`) AND `status`="1"')
            ->first();
            $flag = 0;
            if (!empty($offerCheck)):
            $flag = 1;
            endif; */

            $currentDate = date('Y-m-d');
            $checkSalesToday = Sale::where('type', '1')->whereDate('sale_date', $currentDate)->first();
            $checkSalesDateRange = Sale::where('type', '2')->whereDate('start_date', '<=', $currentDate)
                ->whereDate('end_date', '>=', $currentDate)->first();
            $applicableShows = explode(',', $checkSalesToday?->applicable_shows);
// \DB::enableQueryLog();
            $applicableCategoryToShow = [];
            if (!is_null($checkSalesDateRange)):
                $applicableCategoryToShow = \DB::table('shows')
                    ->selectRaw('GROUP_CONCAT("",id) AS applicableShows')
                    ->whereIn('category_id', explode(',', $checkSalesDateRange?->applicable_categories))
                    ->pluck('applicableShows')
                    ->first();
                $applicableCategoryToShow = explode(',', $applicableCategoryToShow);
            endif;
            $flag = 0;
            $flag1 = 0;
            if (!empty($applicableCategoryToShow) && in_array($id, $applicableCategoryToShow)):
                $flag = 1;
            endif;
            if (!empty($applicableShows) && in_array($id, $applicableShows)):
                $flag1 = 1;
            endif;

            if ($type == 1) {
                if ($flag == 1) {

                    if ($flag1 == '1') {

                        if ($checkSalesToday?->discount_type === 'P') {
                            $discountVal = ((float) $show->instant_download_price * (float) $checkSalesToday?->discount_amount) / 100;
                            $priceDetails = (float) $show->instant_download_price - (float) $discountVal;

                        } elseif ($checkSalesToday?->discount_type === 'F') {
                            $discountVal = (float) $checkSalesToday?->discount_amount;
                            $priceDetails = (float) $show->instant_download_price - (float) $discountVal;
                        }

                        $show_name = $show->title . '(' . 'Instant Download' . ')';
                        $price = number_format($priceDetails, 2);
                        $discount = number_format($discountVal, 2);

                    } else {
                        if ($show->instant_download_price >= $checkSalesDateRange?->min_price_range && $show->instant_download_price <= $checkSalesDateRange?->max_price_range) {
                            if ($checkSalesDateRange?->discount_type === 'P') {
                                $discountVal = ((float) $show->instant_download_price * (float) $checkSalesDateRange?->discount_amount) / 100;

                                $priceDetails = (float) $show->instant_download_price - (float) $discountVal;

                            } elseif ($checkSalesDateRange?->discount_type === 'F') {
                                $discountVal = (float) $checkSalesDateRange?->discount_amount;
                                $priceDetails = (float) $show->instant_download_price - (float) $discountVal;
                            }
                            $show_name = $show->title . '(' . 'Instant Download' . ')';
                            $price = number_format($priceDetails, 2);
                            $discount = number_format($discountVal, 2);

                        } else {
                            $price = $show->instant_download_price;
                            $show_name = $show->title . '(' . 'Instant Download' . ')';
                            $discount = number_format(0, 2);

                        }

                    }

                } else {
                    $price = $show->instant_download_price;
                    $show_name = $show->title . '(' . 'Instant Download' . ')';
                    $discount = number_format(0, 2);
                }

            } elseif ($type == 2) {
                if ($flag == 1) {

                    if ($flag1 == '1') {

                        if ($checkSalesToday?->discount_type === 'P') {
                            $discountVal = ((float) $show->mp3_cd_price * (float) $checkSalesToday?->discount_amount) / 100;
                            $priceDetails = (float) $show->mp3_cd_price - (float) $discountVal;

                        } elseif ($checkSalesToday?->discount_type === 'F') {
                            $discountVal = (float) $checkSalesToday?->discount_amount;
                            $priceDetails = (float) $show->mp3_cd_price - (float) $discountVal;
                        }

                        $show_name = $show->title . '(' . $show->no_of_mp3_cds . ' ' . 'Mp3 Cd' . ')';
                        $price = number_format($priceDetails, 2);
                        $discount = number_format($discountVal, 2);

                    } else {
                        if ($show->mp3_cd_price >= $checkSalesDateRange?->min_price_range && $show->mp3_cd_price <= $checkSalesDateRange?->max_price_range) {
                            if ($checkSalesDateRange?->discount_type === 'P') {
                                $discountVal = ((float) $show->mp3_cd_price * (float) $checkSalesDateRange?->discount_amount) / 100;

                                $priceDetails = (float) $show->mp3_cd_price - (float) $discountVal;

                            } elseif ($checkSalesDateRange?->discount_type === 'F') {
                                $discountVal = (float) $checkSalesDateRange?->discount_amount;
                                $priceDetails = (float) $show->mp3_cd_price - (float) $discountVal;
                            }
                            $show_name = $show->title . '(' . $show->no_of_mp3_cds . ' ' . 'Mp3 Cd' . ')';
                            $price = number_format($priceDetails, 2);
                            $discount = number_format($discountVal, 2);

                        } else {
                            $price = $show->mp3_cd_price;
                            $show_name = $show->title . '(' . $show->no_of_mp3_cds . ' ' . 'Mp3 Cd' . ')';
                            $discount = number_format(0, 2);

                        }
                        /*  $show_name = $show->title . '(' . 'Instant Download' . ')'; */
                        /* $discountVal = (float) $offerCheck->discount_amount;
                    $priceDetails = (float) $show->instant_download_price - (float) $discountVal; */

                    }

                    /* $show_name = $show->title . '(' . 'Instant Download' . ')';
                $price = number_format($priceDetails, 2);
                $discount = number_format($discountVal, 2);
                 */
                } else {
                    $price = $show->mp3_cd_price;
                    $show_name = $show->title . '(' . $show->no_of_mp3_cds . ' ' . 'Mp3 Cd' . ')';
                    $discount = number_format(0, 2);
                }

/* $price = $show->mp3_cd_price;
$show_name = $show->title . '(' . $show->no_of_mp3_cds . ' ' . 'Mp3 Cd' . ')'; */

            } else {
                if ($flag == 1) {

                    if ($flag1 == '1') {

                        if ($checkSalesToday?->discount_type === 'P') {
                            $discountVal = ((float) $show->instant_download_price * (float) $checkSalesToday?->discount_amount) / 100;
                            $priceDetails = (float) $show->instant_download_price - (float) $discountVal;

                        } elseif ($checkSalesToday?->discount_type === 'F') {
                            $discountVal = (float) $checkSalesToday?->discount_amount;
                            $priceDetails = (float) $show->instant_download_price - (float) $discountVal;
                        }

                        $show_name = $show->title . '(' . 'Instant Download' . ')';
                        $price = number_format($priceDetails, 2);
                        $discount = number_format($discountVal, 2);

                    } else {
                        if ($show->instant_download_price >= $checkSalesDateRange?->min_price_range && $show->instant_download_price <= $checkSalesDateRange?->max_price_range) {
                            if ($checkSalesDateRange?->discount_type === 'P') {
                                $discountVal = ((float) $show->instant_download_price * (float) $checkSalesDateRange?->discount_amount) / 100;

                                $priceDetails = (float) $show->instant_download_price - (float) $discountVal;

                            } elseif ($checkSalesDateRange?->discount_type === 'F') {
                                $discountVal = (float) $checkSalesDateRange?->discount_amount;
                                $priceDetails = (float) $show->instant_download_price - (float) $discountVal;
                            }
                            $show_name = $show->title . '(' . 'Instant Download' . ')';
                            $price = number_format($priceDetails, 2);
                            $discount = number_format($discountVal, 2);

                        } else {
                            $price = $show->instant_download_price;
                            $show_name = $show->title . '(' . 'Instant Download' . ')';
                            $discount = number_format(0, 2);

                        }
                        /*  $show_name = $show->title . '(' . 'Instant Download' . ')'; */
                        /* $discountVal = (float) $offerCheck->discount_amount;
                    $priceDetails = (float) $show->instant_download_price - (float) $discountVal; */

                    }

                    /* $show_name = $show->title . '(' . 'Instant Download' . ')';
                $price = number_format($priceDetails, 2);
                $discount = number_format($discountVal, 2);
                 */
                } else {
                    $price = $show->instant_download_price;
                    $show_name = $show->title . '(' . 'Instant Download' . ')';
                    $discount = number_format(0, 2);
                }

            }

            $carts = Cart::where('item_id', $id)->where('user_id', auth()->user()->id)->where('product_type', '=', '1')->first();
            if (!empty($carts)) {
                return response()->json([
                    'status' => false,
                    'message' => 'Product already exist in cart !!',
                    'redirect' => '',
                ]);

            } else {
                auth()->user()->wishlists()->where('item_id', $id)->delete();
                Cart::create([
                    'user_id' => auth()->user()->id,
                    'item_id' => $id,
                    "quantity" => 1,
                    "price" => $price,
                    "discount" => $discount,
                    'type' => $type,
                    'product_type' => 1,

                ]);
                return response()->json([
                    'status' => true,
                    'message' => 'Product added to the cart successfully!',
                    'redirect' => 'my-cart',
                ]);
            }

        }
    }

    public function sampleFile(Request $request)
    {
        $title = "Sample File";
        $sampleFiles = SampleFiles::where('status', '=', '1')->get();
        //dd($sampleFiles);
        return view('pages.loggedin_user.samplefile', compact('title', 'sampleFiles'));

    }
    public function addSampleFileToCart(Request $request)
    {
        if ($request->ajax()) {

            $id = $request->id;
            $show = SampleFiles::findOrFail($id);
            $price = number_format(0, 2);
            $discount = number_format(0, 2);
            //dd($show);

            if (auth()->user()) {
                $carts = Cart::where('item_id', $id)->where('type', '=', '3')->where('product_type', '=', '2')->where('user_id', auth()->user()->id)->first();
                if (!empty($carts)) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Sample File already exist in cart !!',
                        'redirect' => '',
                    ]);

                } else {
                    Cart::create([
                        'user_id' => auth()->user()->id,
                        'item_id' => $id,
                        "quantity" => 0,
                        "price" => $price,
                        "discount" => $discount,
                        'type' => 3,
                        'product_type' => 2,

                    ]);
                    return response()->json([
                        'status' => true,
                        'message' => 'Sample File added to the cart successfully!',
                        'redirect' => 'checkout',
                    ]);

                }

            }

        }

    }

    public function updateCartQuantity(Request $request)
    {
        if ($request->ajax()) {
            //dd($request->all());
            if ($request->id && $request->quantity) {

                $cart = session()->get('cart');
                $cart[$request->id]["quantity"] = $request->quantity;
                session()->put('cart', $cart);
                session()->get('cart', []);

                return response()->json([
                    'status' => true,
                    'message' => 'Quantity Updated Successfully!',
                    'redirect' => 'cart',
                ]);

            }
        } else {
            abort(403);
        }

    }

    public function removeFromWishlist(Request $request)
    {
        if ($request->ajax()):
            $id = $request->id;

            //dd($request->all());
            if (auth()->user()):
                $isWishlistsDeleted = auth()->user()->wishlists()->where('item_id', $id)->delete();
                if ($isWishlistsDeleted):
                    return response()->json([
                        'status' => true,
                        'message' => 'Product removed from wishlist successfully !!',
                        'redirect' => 'my-wishlist',
                    ]);

                else:
                    return response()->json([
                        'status' => false,
                        'message' => 'Something went wrong  !!',
                        'redirect' => '',
                    ]);

                endif;
            endif;

        endif;

    }

    public function aboutUs(Request $request)
    {
        $title = "About Us";
        $cmsList = Cms::where('slug','=','about-us')->where('status','=','1')->first();
        //dd($cmsList);
        return view('pages.about-us',compact('title', 'cmsList'));
    }
    public function termsConditions(Request $request)
    {
        $title = "Terms & Conditions";
        $cmsList = Cms::where('slug','=','terms-conditions')->where('status','=','1')->first();
        //dd($cmsList);
        return view('pages.about-us',compact('title', 'cmsList'));
    }
    public function privacyPolicy(Request $request)
    {
        $title = "Privacy Policy";
        $cmsList = Cms::where('slug','=','privacy-policy')->where('status','=','1')->first();
        //dd($cmsList);
        return view('pages.about-us',compact('title', 'cmsList'));
    }

}
