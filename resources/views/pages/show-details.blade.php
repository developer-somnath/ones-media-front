@php
    $sample_file = DB::table('sample_files')
        ->where(
            'status',
            '<>
',
            1,
        )
        ->first();
    $currentDate = date('Y-m-d');
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
    if (!empty($applicableCategoryToShow) && in_array($productViewList->id, $applicableCategoryToShow)):
        $flag = 1;
    endif;
    if (!empty($applicableShows) && in_array($productViewList->id, $applicableShows)):
        $flag1 = 1;
    endif;
    
    if (!empty($productViewList->instant_download_price)) {
        if ($flag == 1) {
            if ($flag1 == '1') {
                if ($checkSalesToday?->discount_type === 'P') {
                    $discountVal = ((float) $productViewList->instant_download_price * (float) $checkSalesToday?->discount_amount) / 100;
                    $instantpriceDetails = (float) $productViewList->instant_download_price - (float) $discountVal;
                } elseif ($checkSalesToday?->discount_type === 'F') {
                    $discountVal = (float) $checkSalesToday?->discount_amount;
                    $instantpriceDetails = (float) $productViewList->instant_download_price - (float) $discountVal;
                }
    
                $instantprice = number_format($instantpriceDetails, 2);
            } else {
                if ($productViewList->instant_download_price >= $checkSalesDateRange?->min_price_range && $productViewList->instant_download_price <= $checkSalesDateRange?->max_price_range) {
                    if ($checkSalesDateRange?->discount_type === 'P') {
                        $discountVal = ((float) $productViewList->instant_download_price * (float) $checkSalesDateRange?->discount_amount) / 100;
    
                        $instantpriceDetails = (float) $productViewList->instant_download_price - (float) $discountVal;
                    } elseif ($checkSalesDateRange?->discount_type === 'F') {
                        $discountVal = (float) $checkSalesDateRange?->discount_amount;
                        $instantpriceDetails = (float) $productViewList->instant_download_price - (float) $discountVal;
                    }
                    $instantprice = number_format($instantpriceDetails, 2);
                } else {
                    $instantprice = $productViewList->instant_download_price;
                }
            }
        } else {
            $instantprice = $productViewList->instant_download_price;
        }
    }
    if (!empty($productViewList->mp3_cd_price)) {
        if ($flag == 1) {
            if ($flag1 == '1') {
                if ($checkSalesToday?->discount_type === 'P') {
                    $discountVal = ((float) $productViewList->mp3_cd_price * (float) $checkSalesToday?->discount_amount) / 100;
                    $mpthreepriceDetails = (float) $productViewList->mp3_cd_price - (float) $discountVal;
                } elseif ($checkSalesToday?->discount_type === 'F') {
                    $discountVal = (float) $checkSalesToday?->discount_amount;
                    $mpthreepriceDetails = (float) $productViewList->mp3_cd_price - (float) $discountVal;
                }
    
                $mpthreeprice = number_format($mpthreepriceDetails, 2);
            } else {
                if ($productViewList->mp3_cd_price >= $checkSalesDateRange?->min_price_range && $productViewList->mp3_cd_price <= $checkSalesDateRange?->max_price_range) {
                    if ($checkSalesDateRange?->discount_type === 'P') {
                        $discountVal = ((float) $productViewList->mp3_cd_price * (float) $checkSalesDateRange?->discount_amount) / 100;
    
                        $mpthreepriceDetails = (float) $productViewList->mp3_cd_price - (float) $discountVal;
                    } elseif ($checkSalesDateRange?->discount_type === 'F') {
                        $discountVal = (float) $checkSalesDateRange?->discount_amount;
                        $mpthreepriceDetails = (float) $productViewList->mp3_cd_price - (float) $discountVal;
                    }
                    $mpthreeprice = number_format($mpthreepriceDetails, 2);
                } else {
                    $mpthreeprice = $productViewList->mp3_cd_price;
                }
            }
        } else {
            $mpthreeprice = $productViewList->mp3_cd_price;
        }
    
        //dd($mpthreeprice);
    }
@endphp
@extends('layouts.master')
@section('content')
    <div class="container">
        <div class="bredcrames-sec">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ url('/show-by-category/' . $productViewList->categorySlug) }}">
                            {{ isset($productViewList) ? $productViewList->categoryName : '' }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">
                        {{ isset($productViewList) ? $productViewList->title : '' }}</li>
                </ol>
            </nav>
        </div>
    </div>


    <div class="product-details">
        <div class="container">
            <div class="row">
                <div class="col-md-5">
                    <div class="pro-details-img">
                        <img
                            src="{{ env('IMAGE_URL') }}uploads/categories/{{ $productViewList->categorySlug }}/{{ $productViewList->image }}">
                        {{--  <a href="" class=""><i class="fa fa-heart-o" style="font-size:48px;color:red"></i></a>  --}}
                    </div>
                </div>
                <div class="col-md-7">
                    <div class="pro-d-info">
                        <h2>{{ isset($productViewList) ? $productViewList->title : '' }}</h2>
                        <h5><b>Categories:</b> {{ isset($productViewList) ? $productViewList->categoryName : '' }}</h5>
                        <h5><b>Created by:</b> Dashiell Hammett, Howard Duff</h5>
                        {{--  <b>Description:</b>
					<h5>{{ isset($productViewList)?strip_tags($productViewList->description):'---' }}</h5>  --}}
                        <div>
                            <b>
                                Choose your CD format
                                or order disks individually:</b>
                            <select class="form-control w-75 mt-2 cd">
                                <option value="instant_download">Instant Download -
                                    ${{ $instantprice }}</option>
                                <option value="mp3_cd">Mp3 Cd-
                                    ${{ $mpthreeprice }}</option>
                            </select>
                        </div>
                        <div>
                            <a href="javascript:void(0)" class="t-add addToCart" data-id="{{ $productViewList->id }}">Add
                                to Cart</a>
                            @auth
                                <a href="javascript:void(0)" class="wishlist-add addToWishlist"
                                    data-id="{{ $productViewList->id }}">Add to wishlist</a>
                            @endauth
                            @guest
                                <a href="{{ url('/login') }}" class="wishlist-add" data-id="{{ $productViewList->id }}">Add to
                                    wishlist</a>
                            @endguest
                            <br><br>
                            @if(!is_null($sample_file))
                            <audio controls>
                                <source src="{{ env('IMAGE_URL') }}uploads/sample-files/{{ $sample_file->file_name }}"
                                    type="audio/ogg">
                                <source src="{{ env('IMAGE_URL') }}uploads/sample-files/{{ $sample_file->file_name }}"
                                    type="audio/mpeg">
                                Your browser does not support the audio element.
                            </audio>
                            @endif



                            {{--  <div class="popular-box-container">  --}}

                            {{--  </div>
                            <h3>{{ $sample_file->title }}</h3>  --}}

                            </a>

                        </div>

                    </div>
                </div>
            </div>
        </div>
        <div class="product-description mt-5">
            {{ isset($productViewList) ? strip_tags($productViewList->description) : '---' }}
            {{--   <div class="row">
                    <div class="col-md-3">
                        <img src="assets/img/navy.jpg">
                    </div>
                    <div class="col-md-9">
                        <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.
                            Lorem Ipsum has been the industry's standard dummy text ever since the 1500s,
                            when an unknown printer took a galley of type and scrambled it to make a type specimen book. It
                            has survived not only five centuries, but also the leap into electronic typesetting, remaining
                            essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets
                            containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus
                            PageMaker including versions of Lorem Ipsum.</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <img src="assets/img/navy.jpg">
                    </div>
                    <div class="col-md-9">
                        <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.
                            Lorem Ipsum has been the industry's standard dummy text ever since the 1500s,
                            when an unknown printer took a galley of type and scrambled it to make a type specimen book. It
                            has survived not only five centuries, but also the leap into electronic typesetting, remaining
                            essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets
                            containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus
                            PageMaker including versions of Lorem Ipsum.</p>
                    </div>
                </div>  --}}
        </div>
    </div>
    </div>

@stop
