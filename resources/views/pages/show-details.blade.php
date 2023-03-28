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
@push('css')
    <style>
        .rating {
            margin-top: 40px;
            border: none;
            float: left;
        }

        .rating>label {
            color: #90A0A3;
            float: right;
        }

        .rating>label:before {
            margin: 5px;
            font-size: 1em;
            font-family: FontAwesome;
            content: "\f005";
            display: inline-block;
        }

        .rating>input {
            display: none;
        }

        .rating>input:checked~label,
        .rating:not(:checked)>label:hover,
        .rating:not(:checked)>label:hover~label {
            color: #F79426;
        }

        .rating>input:checked+label:hover,
        .rating>input:checked~label:hover,
        .rating>label:hover~input:checked~label,
        .rating>input:checked~label:hover~label {
            color: #FECE31;
        }
    </style>
@endpush
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
                            src="{{ env('IMAGE_URL') }}uploads/categories/{{ $productViewList->categorySlug }}/{{ $productViewList->image }}"><br>
                        {{--  <a href="" class=""><i class="fa fa-heart-o" style="font-size:48px;color:red"></i></a>  --}}
                        <h6 style="text-align: center;margin-top:15px;">({{ $productViewList->no_of_mp3_cds }}MP3 CDs)</h6>
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
                            @if (!is_null($productViewList))
                                <audio controls>
                                    <source
                                        src="{{ env('IMAGE_URL') }}uploads/categories/{{ $productViewList->categorySlug }}/{{ $productViewList->sample_file }}"
                                        type="audio/ogg">
                                    <source
                                        src="{{ env('IMAGE_URL') }}uploads/categories/{{ $productViewList->categorySlug }}/{{ $productViewList->sample_file }}"
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
            <div class="product-description mt-5">
                {!! $productViewList->description !!}
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
            </div><br><br>
            <div class="free-d mb-5">
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab"
                            aria-controls="home" aria-selected="true">Instant Download</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab"
                            aria-controls="profile" aria-selected="false">Mp3 Cd</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="contact-tab" data-toggle="tab" href="#contact" role="tab"
                            aria-controls="contact" aria-selected="false">Reviews</a>
                    </li>
                </ul>
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                        {{--  @forelse ($todayFreeDownloadList as $list)  --}}
                        <div class="free-d-list">
                            <div class="row">
                                <div class="col-md-2">
                                    <div class="free-d-list-img">
                                        <img
                                            src="{{ env('IMAGE_URL') }}uploads/categories/{{ $productViewList->categorySlug }}/{{ $productViewList->image }}">
                                    </div>
                                </div>

                                <div class="col-md-8">
                                    <div class="free-d-list-content">
                                        {{--   <p>On the air in 1998, 24 years ago today:
                                            December 16, 1998 Speech: Airstrikes on Iraq from Clinton (William J
                                            Clinton) Speeches
                                        </p>  --}}
                                        <div class="d-flex align-items-center">
                                            <h5>{{ $productViewList->title }}</h5>

                                            <span style="margin-left: 20px;">${{ $instantprice }}</span>
                                        </div>
                                    </div>
                                </div>
                                {{--  <div class="col-md-3">
                                    <div class="free-cart-content">
                                        <h4>Order this episode and 802 additional episodes on MP3 CD</h4>
                                        <h5>Only $5.00 per episode </h5>
                                        <strong>$99</strong>
                                        <a href="#">Add to cart</a>
                                    </div>
                                </div>  --}}
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                        <div class="free-d-list">
                            <div class="row">
                                <div class="col-md-2">
                                    <div class="free-d-list-img">
                                        <img
                                            src="{{ env('IMAGE_URL') }}uploads/categories/{{ $productViewList->categorySlug }}/{{ $productViewList->image }}">
                                    </div>
                                </div>

                                <div class="col-md-8">
                                    <div class="free-d-list-content">
                                        {{--   <p>On the air in 1998, 24 years ago today:
                                            December 16, 1998 Speech: Airstrikes on Iraq from Clinton (William J
                                            Clinton) Speeches
                                        </p>  --}}
                                        <div class="d-flex align-items-center">
                                            <h5>{{ $productViewList->title }}</h5>

                                            <span style="margin-left: 20px;">${{ $mpthreeprice }}</span>
                                        </div>
                                    </div>
                                </div>
                                {{--  <div class="col-md-3">
                                    <div class="free-cart-content">
                                        <h4>Order this episode and 802 additional episodes on MP3 CD</h4>
                                        <h5>Only $5.00 per episode </h5>
                                        <strong>$99</strong>
                                        <a href="#">Add to cart</a>
                                    </div>
                                </div>  --}}
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">
                        <div class="free-d-list">
                            <h7>Average</h7> : <span>{{ !empty($reviewrating->ratings_average)?$reviewrating->ratings_average:'0.0' }}<i class="fa fa-star" aria-hidden="true"></i></span>
                            <div class="row">
                                @forelse ($productReviewList as $list)
                                    <div class="free-d-list">
                                        <div class="row">
                                            {{--  <div class="col-md-2">
                                        <div class="">
                                                <h3>{{ $list->title }}</h3>
                                        </div>
                                    </div>  --}}
                                            <div class="col-md-2">
                                                <div class="free-d-list-img">
                                                    <h5>{{ $list->name }}</h5>
                                                </div>
                                            </div>
                                            <div class="col-md-10">
                                                <div class="free-d-list-content">
                                                    {{--   <p>On the air in 1998, 24 years ago today:
                                            December 16, 1998 Speech: Airstrikes on Iraq from Clinton (William J
                                            Clinton) Speeches
                                        </p>  --}}
                                                    <div class="d-flex align-items-center">
                                                        @if ($list->rating == '5')
                                                            <span>
                                                                <i class="fa fa-star" aria-hidden="true"></i>
                                                                <i class="fa fa-star" aria-hidden="true"></i>
                                                                <i class="fa fa-star" aria-hidden="true"></i>
                                                                <i class="fa fa-star" aria-hidden="true"></i>
                                                                <i class="fa fa-star" aria-hidden="true"></i>
                                                            </span>
                                                        @elseif($list->rating == '4')
                                                            <span>
                                                                <i class="fa fa-star" aria-hidden="true"></i>
                                                                <i class="fa fa-star" aria-hidden="true"></i>
                                                                <i class="fa fa-star" aria-hidden="true"></i>
                                                                <i class="fa fa-star" aria-hidden="true"></i>
                                                            </span>
                                                        @elseif($list->rating == '3')
                                                            <span>
                                                                <i class="fa fa-star" aria-hidden="true"></i>
                                                                <i class="fa fa-star" aria-hidden="true"></i>
                                                                <i class="fa fa-star" aria-hidden="true"></i>
                                                            </span>
                                                        @elseif($list->rating == '2')
                                                            <span>
                                                                <i class="fa fa-star" aria-hidden="true"></i>
                                                                <i class="fa fa-star" aria-hidden="true"></i>
                                                            </span>
                                                        @elseif($list->rating == '1')
                                                            <span>
                                                                <i class="fa fa-star" aria-hidden="true"></i>
                                                            </span>
                                                        @else
                                                            <span>
                                                                {{-- <i class="fa fa-star-o" aria-hidden="true"></i> --}}
                                                            </span>
                                                        @endif

                                                        <p>{{ $list->description }}</p>
                                                        {{--  <audio controls>
                                                            <source
                                                                src="{{ env('IMAGE_URL') }}uploads/free-downloads/{{ $list->file_name }}"
                                                                type="audio/ogg">
                                                            <source
                                                                src="{{ env('IMAGE_URL') }}uploads/free-downloads/{{ $list->file_name }}"
                                                                type="audio/mpeg">
                                                            Your browser does not support the audio element.
                                                        </audio>  --}}
                                                        
                                                    </div>
                                                </div>
                                            </div>
                                            {{--  <div class="col-md-3">
                                    <div class="free-cart-content">
                                        <h4>Order this episode and 802 additional episodes on MP3 CD</h4>
                                        <h5>Only $5.00 per episode </h5>
                                        <strong>$99</strong>
                                        <a href="#">Add to cart</a>
                                    </div>
                                </div>  --}}
                                        </div>
                                    </div>
                                @empty
                                    <div class="free-d-list">
                                        <h6>No Rating Found !!</h5>
                                    </div>
                                @endforelse
                                {{--  <div class="col-md-3">
                                    <div class="free-cart-content">
                                        <h4>Order this episode and 802 additional episodes on MP3 CD</h4>
                                        <h5>Only $5.00 per episode </h5>
                                        <strong>$99</strong>
                                        <a href="#">Add to cart</a>
                                    </div>
                                </div>  --}}
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <form class="userFrm" data-action="post-review" method="post"
                                        data-validation="requiredCheck">
                                        <div class="row">
                                            {{--  <form class="userFrm" data-action="change-password" method="post" data-validation="requiredCheck">  --}}
                                            @csrf
                                            <input type="hidden" name="show_id" value="{{ $productViewList->id }}" />
                                            <div class="col-md-6">
                                                <div class="form-group">

                                                    <div class="rating">

                                                        <input type="radio" id="star5" name="rating"
                                                            value="5" class="requiredCheck" data-check="Rating" />
                                                        <label class="star" for="star5" title="Awesome"
                                                            aria-hidden="true"></label>
                                                        <input type="radio" id="star4" name="rating"
                                                            value="4" class="requiredCheck" data-check="Rating" />
                                                        <label class="star" for="star4" title="Great"
                                                            aria-hidden="true"></label>
                                                        <input type="radio" id="star3" name="rating"
                                                            value="3" class="requiredCheck" data-check="Rating" />
                                                        <label class="star" for="star3" title="Very good"
                                                            aria-hidden="true"></label>
                                                        <input type="radio" id="star2" name="rating"
                                                            value="2" class="requiredCheck" data-check="Rating" />
                                                        <label class="star" for="star2" title="Good"
                                                            aria-hidden="true"></label>
                                                        <input type="radio" id="star1" name="rating"
                                                            value="1" class="requiredCheck" data-check="Rating" />
                                                        <label class="star" for="star1" title="Bad"
                                                            aria-hidden="true"></label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">

                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Name <sup>*</sup></label>
                                                    <input type="text" name="name" id="name"
                                                        class="form-control requiredCheck" placeholder="Name"
                                                        data-check="Name">
                                                </div>
                                            </div>
                                            <div class="col-md-6">

                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Email <sup>*</sup></label>
                                                    <input type="email" name="email" id="email"
                                                        class="form-control requiredCheck" placeholder="Email"
                                                        data-check="Email">
                                                </div>
                                            </div>
                                            <div class="col-md-6">

                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Comment <sup>*</sup></label>
                                                    <textarea id="description" name="description" placeholder="Enter Description here"
                                                        class="form-control requiredCheck" rows="5" data-check="Description"></textarea>
                                                </div>
                                            </div>
                                            <div class="col-md-6">

                                            </div>


                                            <div class="col-md-12">
                                                <input type="submit" value="Post" class="submit-btn">
                                            </div>
                                            {{--  </form>  --}}
                                        </div>
                                    </form>
                                </div>

                            </div>
                        </div>
                        {{--  @empty
                        <div class="free-d-list">
                            <h6>No Downloads Found !!</h5>
                        </div>
                    @endforelse  --}}
                    </div>
                </div>
            </div>
        </div>

    </div>
    </div>

@stop
