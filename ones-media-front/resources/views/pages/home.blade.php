<link href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/responsive/2.3.0/css/responsive.dataTables.min.css" rel="stylesheet">
@extends('layouts.master')
@section('content')

    <div class="hero-section">
        <div class="container">
            <div class="banner-slider" id="h-slider">
                @forelse ($bannerList as $banner)
                    <div class="item">
                        <div class="banner-item">
                            <a href="#">
                                <img src="{{ env('IMAGE_URL') }}uploads/banners/{{ $banner->image }}">
                                <h4>{{ $banner->short_description }}</h4>
                            </a>
                        </div>
                    </div>
                @empty
                @endforelse

            </div>
        </div>
    </div>
    <div class="third-product">
        <div class="container">
            <div class="row">
                @forelse ($offerList as $offer)
                    <div class="col-md-4">
                        <div class="third-product-card-discount">
                            <img src="{{ env('IMAGE_URL') }}uploads/offer/{{ $offer->image }}">
                            <div class="card-overlay">
                                <p>{!! $offer->description !!}</p>
                            </div>
                        </div>

                    </div>
                @empty
                    <div class="col-md-8">
                        <h3>No Data Found !!</h3>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
    <div class="main-part-home">
        <div class="container">
            <div class="row">
                <div class="col-md-3">
                    <div class="card widget-box">
                        <div class="card-header">
                            <h5>Categories</h5>
                        </div>
                        <div class="card-body" id="accordion">
                            <ul>
                                @forelse ($categoryList as $category)
                                    <li>
                                        <a
                                            href="{{ app()->router->has('show.by.category') ? route('show.by.category', $category->slug) : 'javascript:void(0)' }}">{{ $category->name }}</a>
                                    </li>
                                @empty
                                @endforelse


                            </ul>
                        </div>
                    </div>
                    <div class="card widget-box">
                        <div class="card-header">
                            <h5>INFORMATION</h5>
                        </div>
                        <div class="card-body">
                            <ul>
                                <li><a href="">Shipping &amp; Returns</a></li>
                                <li><a href="">Privacy Notice</a></li>
                                <li><a href="">Conditions of Use</a></li>
                                <li><a href="">Contact Us</a></li>
                                <li><a href="">Site Map</a></li>
                                <li><a href="">Gift Certificate FAQ</a></li>
                                <li><a href="">Discount Coupons</a></li>
                                <li><a href="">Newsletter Unsubscribe</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="card widget-box">
                        <div class="card-header">
                            <h5>IMPORTANT LINKS</h5>
                        </div>
                        <div class="card-body">
                            <ul>
                                <li><a href="">Newsletter Unsubsriber</a></li>
                                <li><a href="">Shopping Cart</a></li>
                                <li><a href="{{ url('login') }}">Login</a></li>
                                <li><a href="">Site Map</a></li>
                                <li><a href="">Online Support</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-md-9">
                    <div class="page-title">
                        <h2>Most Popular Shows</h2>
                    </div>
                    <div class="row">
                        @forelse ($productListPopular as $product)
                            <div class="col-md-4">
                                <div class="popular-box">
                                    <a href="{{ url('show/details/' . $product->id) }}">
                                        <div class="popular-box-container">
                                            <img
                                                src="{{ env('IMAGE_URL') }}uploads/categories/{{ $product->categorySlug }}/{{ $product->image }}">
                                        </div>
                                        <h3>{{ $product->title }}</h3>
                                    </a>
                                    </a>
                                </div>
                            </div>
                        @empty
                            <div class="col-md-12">
                                <div class="popular-box">
                                    <a href="product-details.html">

                                        <h3>No Data Found !!</h3>
                                    </a>
                                </div>
                            </div>
                        @endforelse
                    </div>
                    <div class="page-title">
                        <h2>Our Shows</h2>
                    </div>
                    <div class="product-filter">
                        <div class="d-flex">
                            {{--  <div class="d-flex show-f">
                                <label>Show</label>
                                <select class="show-sele">
                                    <option>100</option>
                                    <option>200</option>
                                </select>
                            </div>  --}}
                            <div class="d-flex show-f">
                                <label>CD Type</label>
                                <select class="show-sele cd">
                                    <option value="">Select Type</option>
                                    <option value="instant_download"@if (!empty($_REQUEST['type']) && $_REQUEST['type'] == 'instant_download') selected @endif>
                                        Instant Download</option>
                                    <option value="mp3_cd"@if (!empty($_REQUEST['type']) && $_REQUEST['type'] == 'mp3_cd') selected @endif>MP3 cd</option>
                                </select>
                            </div>
                        </div>
                        <div class="cate-list">
                            <ul>
                                @php
                                    $alphabet = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z'];
                                @endphp
                                <li><a href="{{ url('/') }}">Show All</a></li>
                                @forelse($alphabet as $value)
                                    <li><a href="javascript:void(0)" value="{{ lcfirst($value) }}"
                                            class="filterData">{{ $value }}</a></li>
                                @empty
                                @endforelse
                            </ul>
                        </div>
                        <div class="home-pro-list">
                            <table class="w-100" id="hompageTable">
                                <thead>
                                    <th>image</th>
                                    <th>Title</th>
                                    <th>Popularity</th>
                                    <th>Episodes</th>
                                    <th>MP3 CDs</th>
                                    <th>Price</th>
                                </thead>
                                <tbody>
                                    {{-- {{ dd($checkSalesDateRange) }} --}}
                                    {{-- {{ dd($checkSalesToday) }} --}}
                                    @php
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
                                        
                                    @endphp
                                    @forelse ($productListAll as $productall)
                                        @php
                                            $flag = 0;
                                            $flag1 = 0;
                                            if (!empty($applicableCategoryToShow) && in_array($productall->id, $applicableCategoryToShow)):
                                                $flag = 1;
                                            endif;
                                            if (!empty($applicableShows) && in_array($productall->id, $applicableShows)):
                                                $flag1 = 1;
                                            endif;
                                        @endphp
                                        <tr>
                                            <td>
                                                <div class="list-p-img">
                                                    <a href="{{ url('show/details/' . $productall->id) }}">
                                                        <img
                                                            src="{{ env('IMAGE_URL') }}uploads/categories/{{ $productall->categorySlug }}/{{ $productall->image }}">
                                                    </a>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="list-p-title">
                                                    <a href="{{ url('show/details/' . $productall->id) }}">
                                                        <h3>{{ $productall->title }}</h3>
                                                    </a>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="list-p-rating">
                                                    <i class="fa-solid fa-star"></i>
                                                    <i class="fa-solid fa-star"></i>
                                                    <i class="fa-solid fa-star"></i>
                                                    <i class="fa-solid fa-star d-star"></i>
                                                    <i class="fa-solid fa-star d-star"></i>
                                                </div>
                                            </td>
                                            <td>
                                                {{ $productall->no_of_episodes }}
                                            </td>
                                            <td>{{ $productall->no_of_mp3_cds }}</td>
                                            <td>
                                                <div class="list-p-btn">
                                                    {{--  @if (!empty($productall->discount_amount))
                                                        <del>${{ $productall->instant_download_price }}</del>
                                                    @endif  --}}
                                                    @if (!empty($_REQUEST['type']) && $_REQUEST['type'] == 'instant_download')
                                                        @if ($flag == 1)
                                                            @if ($flag1 == 1)
                                                                <del>${{ $productall->instant_download_price }}</del>
                                                                @if ($checkSalesToday?->discount_type === 'P')
                                                                    @php
                                                                        $discountVal = ((float) $productall->instant_download_price * (float) $checkSalesToday?->discount_amount) / 100;
                                                                        $price = (float) $productall->instant_download_price - (float) $discountVal;
                                                                    @endphp
                                                                @elseif ($checkSalesToday?->discount_type === 'F')
                                                                    @php
                                                                        $price = (float) $productall->instant_download_price - (float) $checkSalesToday?->discount_amount;
                                                                    @endphp
                                                                @endif
                                                                <span> ${{ number_format($price, 2) }}</span>
                                                            @else
                                                                @if (
                                                                    $productall->instant_download_price >= $checkSalesDateRange?->min_price_range &&
                                                                        $productall->instant_download_price <= $checkSalesDateRange?->max_price_range)
                                                                    <del>${{ $productall->instant_download_price }}</del>
                                                                    @if ($checkSalesDateRange?->discount_type === 'P')
                                                                        @php
                                                                            $discountVal = ((float) $productall->instant_download_price * (float) $checkSalesDateRange?->discount_amount) / 100;
                                                                            $price = (float) $productall->instant_download_price - (float) $discountVal;
                                                                        @endphp
                                                                    @elseif ($checkSalesDateRange?->discount_type === 'F')
                                                                        @php
                                                                            $price = (float) $productall->instant_download_price - (float) $checkSalesDateRange?->discount_amount;
                                                                        @endphp
                                                                    @endif
                                                                    <span> ${{ number_format($price, 2) }}</span>
                                                                @else
                                                                    <span>
                                                                        ${{ $productall->instant_download_price }}</span>
                                                                @endif
                                                            @endif
                                                        @else
                                                            <span> ${{ $productall->instant_download_price }}</span>
                                                        @endif
                                                    @elseif(!empty($_REQUEST['type']) && $_REQUEST['type'] == 'mp3_cd')
                                                        @if ($flag == 1)
                                                            @if ($flag1 == 1)
                                                                <del>${{ $productall->mp3_cd_price }}</del>
                                                                @if ($checkSalesToday?->discount_type === 'P')
                                                                    @php
                                                                        $discountVal = ((float) $productall->mp3_cd_price * (float) $checkSalesToday?->discount_amount) / 100;
                                                                        $price = (float) $productall->mp3_cd_price - (float) $discountVal;
                                                                    @endphp
                                                                @elseif ($checkSalesToday?->discount_type === 'F')
                                                                    @php
                                                                        $price = (float) $productall->mp3_cd_price - (float) $checkSalesToday?->discount_amount;
                                                                    @endphp
                                                                @endif
                                                                <span> ${{ number_format($price, 2) }}</span>
                                                            @else
                                                                @if (
                                                                    $productall->mp3_cd_price >= $checkSalesDateRange?->min_price_range &&
                                                                        $productall->mp3_cd_price <= $checkSalesDateRange?->max_price_range)
                                                                    <del>${{ $productall->mp3_cd_price }}</del>
                                                                    @if ($checkSalesDateRange?->discount_type === 'P')
                                                                        @php
                                                                            $discountVal = ((float) $productall->mp3_cd_price * (float) $checkSalesDateRange?->discount_amount) / 100;
                                                                            $price = (float) $productall->mp3_cd_price - (float) $discountVal;
                                                                        @endphp
                                                                    @elseif ($checkSalesDateRange?->discount_type === 'F')
                                                                        @php
                                                                            $price = (float) $productall->mp3_cd_price - (float) $checkSalesDateRange?->discount_amount;
                                                                        @endphp
                                                                    @endif
                                                                    <span> ${{ number_format($price, 2) }}</span>
                                                                @else
                                                                    <span>
                                                                        ${{ $productall->mp3_cd_price }}</span>
                                                                @endif
                                                            @endif
                                                        @else
                                                            <span> ${{ $productall->mp3_cd_price }}</span>
                                                        @endif
                                                    @else
                                                        @if ($flag == 1)
                                                            @if ($flag1 == 1)
                                                                <del>${{ $productall->instant_download_price }}</del>
                                                                @if ($checkSalesToday?->discount_type === 'P')
                                                                    @php
                                                                        $discountVal = ((float) $productall->instant_download_price * (float) $checkSalesToday?->discount_amount) / 100;
                                                                        $price = (float) $productall->instant_download_price - (float) $discountVal;
                                                                    @endphp
                                                                @elseif ($checkSalesToday?->discount_type === 'F')
                                                                    @php
                                                                        $price = (float) $productall->instant_download_price - (float) $checkSalesToday?->discount_amount;
                                                                    @endphp
                                                                @endif
                                                                <span> ${{ number_format($price, 2) }}</span>
                                                            @else
                                                                @if (
                                                                    $productall->instant_download_price >= $checkSalesDateRange?->min_price_range &&
                                                                        $productall->instant_download_price <= $checkSalesDateRange?->max_price_range)
                                                                    <del>${{ $productall->instant_download_price }}</del>
                                                                    @if ($checkSalesDateRange?->discount_type === 'P')
                                                                        @php
                                                                            $discountVal = ((float) $productall->instant_download_price * (float) $checkSalesDateRange?->discount_amount) / 100;
                                                                            $price = (float) $productall->instant_download_price - (float) $discountVal;
                                                                        @endphp
                                                                    @elseif ($checkSalesDateRange?->discount_type === 'F')
                                                                        @php
                                                                            $price = (float) $productall->instant_download_price - (float) $checkSalesDateRange?->discount_amount;
                                                                        @endphp
                                                                    @endif
                                                                    <span> ${{ number_format($price, 2) }}</span>
                                                                @else
                                                                    <span>
                                                                        ${{ $productall->instant_download_price }}</span>
                                                                @endif
                                                            @endif
                                                        @else
                                                            <span> ${{ $productall->instant_download_price }}</span>
                                                        @endif
                                                    @endif
                                                    <button type="button" class="addToCart"
                                                        data-id="{{ $productall->id }}">Add To Cart</button>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" align="center">No Data Available!</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="today-sale my-5">
                <div class="page-title">
                    <h2>On Sale Today Only</h2>
                </div>
                @php
                    $current_date = date('Y-m-d');
                    $new_date = date('jS F Y', strtotime($current_date));
                @endphp
                <h5 class="text-center">{{ $new_date }}, in Radio History :</h5>
                <div class="row mt-3 justify-content-center">
                    @forelse ($salesToday as $show)
                        <div class="col-md-4">
                            <div class="r-b">
                                <img
                                    src="{{ env('IMAGE_URL') }}uploads/categories/{{ $show->categorySlug }}/{{ $show->image }}">
                                <a href="{{ url('show/details/' . $show->id) }}">
                                    <p>{{ $show->title }} <br>
                                        {{ $show->discount_type == 'P' ? round($show->discount_amount, 0) . ' ' . '%' : 'FLAT' . ' ' . '$' . round($show->discount_amount, 0) }}

                                        off today only</p>
                                </a>
                                <a href="javascript:void(0)" class="t-add addToCart" data-id="{{ $show->id }}">Add
                                    to cart</a>
                            </div>
                        </div>
                    @empty
                        <div class="col-md-8 r-b">
                            <h6>No Data Found !!</h5>
                        </div>
                    @endforelse
                    {{--  <div class="col-md-4">
                        <div class="r-b">
                            <img src="assets/img/_lineup.jpg">
                            <a href="#">
                                <p>Happy Birthday <br>Spike Jones <br> 20% off today only</p>
                            </a>
                            <a href="#" class="t-add">Add to cart</a>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="r-b">
                            <img src="assets/img/_lineup.jpg">
                            <a href="#">
                                <p>Happy Birthday <br>Spike Jones <br> 20% off today only</p>
                            </a>
                            <a href="#" class="t-add">Add to cart</a>
                        </div>
                    </div>  --}}
                </div>
            </div>
            <div class="free-d mb-5">
                <div class="page-title">
                    <h2>Free Daily Download</h2>
                </div>
                <p>Onesmedia features thousands of old time radio recordings for fans of the golden age of radio. Our
                    collections are available on MP3 CD, Audio CD, and Instant Downloads.
                </p>
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab"
                            aria-controls="home" aria-selected="true">Today</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab"
                            aria-controls="profile" aria-selected="false">Yesterday</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="contact-tab" data-toggle="tab" href="#contact" role="tab"
                            aria-controls="contact" aria-selected="false">2 day ago</a>
                    </li>
                </ul>
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                        @forelse ($todayFreeDownloadList as $list)
                            <div class="free-d-list">
                                <div class="row">
                                    {{--  <div class="col-md-2">
                                        <div class="">
                                                <h3>{{ $list->title }}</h3>
                                        </div>
                                    </div>  --}}
                                    <div class="col-md-2">
                                        <div class="free-d-list-img">
                                            <img src="{{ env('IMAGE_URL') }}uploads/free-downloads/{{ $list->image }}">
                                        </div>
                                    </div>
                                    <div class="col-md-10">
                                        <div class="free-d-list-content">
                                            {{--   <p>On the air in 1998, 24 years ago today:
                                            December 16, 1998 Speech: Airstrikes on Iraq from Clinton (William J
                                            Clinton) Speeches
                                        </p>  --}}
                                            <div class="d-flex align-items-center">
                                                <audio controls>
                                                    <source
                                                        src="{{ env('IMAGE_URL') }}uploads/free-downloads/{{ $list->file_name }}"
                                                        type="audio/ogg">
                                                    <source
                                                        src="{{ env('IMAGE_URL') }}uploads/free-downloads/{{ $list->file_name }}"
                                                        type="audio/mpeg">
                                                    Your browser does not support the audio element.
                                                </audio>
                                                <a class="d-bttn"
                                                    href="assets/img/i_l_a_m_491214_03_the_million_dollar_curse.mp3"
                                                    download="">Download for free</a>
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
                                <h6>No Downloads Found !!</h5>
                            </div>
                        @endforelse
                        {{--   <div class="free-d-list">
                            <div class="row">
                                <div class="col-md-2">
                                    <div class="free-d-list-img">
                                        <img src="assets/img/PatNovak.png">
                                    </div>
                                </div>
                                <div class="col-md-7">
                                    <div class="free-d-list-content">
                                        <p>On the air in 1998, 24 years ago today:
                                            December 16, 1998 Speech: Airstrikes on Iraq from Clinton (William J
                                            Clinton) Speeches
                                        </p>
                                        <div class="d-flex align-items-center">
                                            <audio controls>
                                                <source src="assets/img/i_l_a_m_491214_03_the_million_dollar_curse.mp3"
                                                    type="audio/mpeg">
                                                Your browser does not support the audio element.
                                            </audio>
                                            <a class="d-bttn"
                                                href="assets/img/i_l_a_m_491214_03_the_million_dollar_curse.mp3"
                                                download="">Download for free</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="free-cart-content">
                                        <h4>Order this episode and 802 additional episodes on MP3 CD</h4>
                                        <h5>Only $5.00 per episode </h5>
                                        <strong>$99</strong>
                                        <a href="#">Add to cart</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="free-d-list">
                            <div class="row">
                                <div class="col-md-2">
                                    <div class="free-d-list-img">
                                        <img src="assets/img/PatNovak.png">
                                    </div>
                                </div>
                                <div class="col-md-7">
                                    <div class="free-d-list-content">
                                        <p>On the air in 1998, 24 years ago today:
                                            December 16, 1998 Speech: Airstrikes on Iraq from Clinton (William J
                                            Clinton) Speeches
                                        </p>
                                        <div class="d-flex align-items-center">
                                            <audio controls>
                                                <source src="assets/img/i_l_a_m_491214_03_the_million_dollar_curse.mp3"
                                                    type="audio/mpeg">
                                                Your browser does not support the audio element.
                                            </audio>
                                            <a class="d-bttn"
                                                href="assets/img/i_l_a_m_491214_03_the_million_dollar_curse.mp3"
                                                download="">Download for free</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="free-cart-content">
                                        <h4>Order this episode and 802 additional episodes on MP3 CD</h4>
                                        <h5>Only $5.00 per episode </h5>
                                        <strong>$99</strong>
                                        <a href="#">Add to cart</a>
                                    </div>
                                </div>
                            </div>
                        </div>  --}}
                    </div>
                    <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                        @forelse ($yesterdayFreeDownloadList as $list)
                            <div class="free-d-list">
                                <div class="row">
                                    {{--  <div class="col-md-2">
                                        <div class="">
                                                <h3>{{ $list->title }}</h3>
                                        </div>
                                    </div>  --}}
                                    <div class="col-md-2">
                                        <div class="free-d-list-img">
                                            <img src="{{ env('IMAGE_URL') }}uploads/free-downloads/{{ $list->image }}">
                                        </div>
                                    </div>
                                    <div class="col-md-10">
                                        <div class="free-d-list-content">
                                            {{--   <p>On the air in 1998, 24 years ago today:
                                            December 16, 1998 Speech: Airstrikes on Iraq from Clinton (William J
                                            Clinton) Speeches
                                        </p>  --}}
                                            <div class="d-flex align-items-center">
                                                <audio controls>
                                                    <source
                                                        src="{{ env('IMAGE_URL') }}uploads/free-downloads/{{ $list->file_name }}"
                                                        type="audio/ogg">
                                                    <source
                                                        src="{{ env('IMAGE_URL') }}uploads/free-downloads/{{ $list->file_name }}"
                                                        type="audio/mpeg">
                                                    Your browser does not support the audio element.
                                                </audio>
                                                <a class="d-bttn"
                                                    href="assets/img/i_l_a_m_491214_03_the_million_dollar_curse.mp3"
                                                    download="">Download for free</a>
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
                                <h6>No Downloads Found !!</h5>
                            </div>
                        @endforelse
                    </div>
                    <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">
                        @forelse ($twodaysAgoFreeDownloadList as $list)
                            <div class="free-d-list">
                                <div class="row">
                                    {{--  <div class="col-md-2">
                                        <div class="">
                                                <h3>{{ $list->title }}</h3>
                                        </div>
                                    </div>  --}}
                                    <div class="col-md-2">
                                        <div class="free-d-list-img">
                                            <img src="{{ env('IMAGE_URL') }}uploads/free-downloads/{{ $list->image }}">
                                        </div>
                                    </div>
                                    <div class="col-md-10">
                                        <div class="free-d-list-content">
                                            {{--   <p>On the air in 1998, 24 years ago today:
                                            December 16, 1998 Speech: Airstrikes on Iraq from Clinton (William J
                                            Clinton) Speeches
                                        </p>  --}}
                                            <div class="d-flex align-items-center">
                                                <audio controls>
                                                    <source
                                                        src="{{ env('IMAGE_URL') }}uploads/free-downloads/{{ $list->file_name }}"
                                                        type="audio/ogg">
                                                    <source
                                                        src="{{ env('IMAGE_URL') }}uploads/free-downloads/{{ $list->file_name }}"
                                                        type="audio/mpeg">
                                                    Your browser does not support the audio element.
                                                </audio>
                                                <a class="d-bttn"
                                                    href="assets/img/i_l_a_m_491214_03_the_million_dollar_curse.mp3"
                                                    download="">Download for free</a>
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
                                <h6>No Downloads Found !!</h5>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
            {{--  <div class="client-section">
                <div class="container">
                    <div class="page-title">
                        <h2>Reviews</h2>
                    </div>
                    <div class="client-slider" id="c-slider">
                        <div class="item">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="client-img">
                                        <img src="assets/img/_lineup.jpg">
                                    </div>
                                </div>
                                <div class="col-md-9">
                                    <div class="client-txt">
                                        <p>
                                            <i class="fa-solid fa-quote-left"></i>
                                            Aenean laoreet tellus et commodo egestas. Aliquam placerat enim et
                                            libero lacinia egestas. In quis fringilla erat, a placerat magna.
                                            Praesent velit ipsum, congue.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="item">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="client-img">
                                        <img src="assets/img//_durante.jpg">
                                    </div>
                                </div>
                                <div class="col-md-9">
                                    <div class="client-txt">
                                        <p>
                                            <i class="fa-solid fa-quote-left"></i>
                                            Aenean laoreet tellus et commodo egestas. Aliquam placerat enim et
                                            libero lacinia egestas. In quis fringilla erat, a placerat magna.
                                            Praesent velit ipsum, congue.
                                            <i class="fa-solid fa-quote-right"></i>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>  --}}
        </div>
        <form action="{{ url('/') }}" method="GET" id="myForm">
            <input type="hidden" name="filter" value="{{ !empty($_REQUEST['filter']) ? $_REQUEST['filter'] : '' }}"
                id="filter">
            <input type="hidden" name="type" value="{{ !empty($_REQUEST['type']) ? $_REQUEST['type'] : '' }}"
                id="type">
        </form>
    @stop
    @push('scripts')
        <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/responsive/2.3.0/js/dataTables.responsive.min.js"></script>
        <script>
            $(document).on('click', '.filterData', function(e) {
                console.log('clicked');
                var filter_value = $(this).attr('value');
                //alert(filter_value)
                $('#filter').val(filter_value);
                $("#myForm").submit();
                //const currenturl = "{{ url()->full() }}";
                //alert(currenturl);
                //window.location.href = baseUrl+'?type='+value;
            });
            $(document).on('change', '.cd', function(e) {
                var value = $(this).val();
                $('#type').val(value);
                $("#myForm").submit();
                //const currenturl = "{{ url()->full() }}";
                //alert(currenturl);
                //window.location.href = baseUrl+'?type='+value;
            });
            $(document).ready(function() {
                loadData();
            })
            const loadData = () => {
                //   $('#bannerTable').DataTable().destroy();
                var dataTable = $('#hompageTable').DataTable({
                    {{--  dom: 'Bfrtip',  --}}
                    processing: true,
                    serverSide: false,
                    autoWidth: false,
                    responsive: true,
                    searching: false,


                });
            }
        </script>
    @endpush
