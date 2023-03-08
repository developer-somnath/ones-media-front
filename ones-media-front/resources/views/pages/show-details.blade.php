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
                            <select class="form-control w-75 mt-2">
                                <option>MP3 CD: sample collection - $5</option>
                                <option>Audio CD: Disc A001- $5</option>
                                <option>Audio CD: Disc A001- $5</option>
                                <option>Audio CD: Disc A001- $5</option>
                            </select>
                        </div>
                        <div>
                            <a href="javascript:void(0)" class="t-add addToCart"  data-id="{{ $productViewList->id }}">Add to Cart</a>
                            @auth
                                <a href="javascript:void(0)" class="wishlist-add addToWishlist"  data-id="{{ $productViewList->id }}">Add to wishlist</a>
                            @endauth
                            @guest
                                <a href="{{ url('/login') }}" class="wishlist-add"  data-id="{{ $productViewList->id }}">Add to wishlist</a>
                            @endguest
                             
                        </div>
                    </div>
                </div>
            </div>
            <div class="product-description mt-5">
				{{ isset($productViewList)?strip_tags($productViewList->description):'---' }}
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
