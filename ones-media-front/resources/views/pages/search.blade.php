<link href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/responsive/2.3.0/css/responsive.dataTables.min.css" rel="stylesheet">
@extends('layouts.master')
@section('content')
    <div class="main-part-home">
        <div class="container">
            <div>
                {{--  <div class="page-title">
                    <h2>All Shows</h2>
                </div>  --}}
                <div class="product-filter">
                    <div class="d-flex">
                        Search Results :  <b>{{ count($productList) }} Shows</b>
                        {{--  <div class="d-flex show-f">
                            <label>Show</label>
                            <select class="show-sele">
                                <option>100</option>
                                <option>200</option>
                            </select>
                        </div>  --}}
                        {{--  <div class="d-flex show-f">
                            <label>CD Type</label>
                            <select class="show-sele cd">
                                <option value="">Select Type</option>
                                <option value="instant_download"@if (!empty($_REQUEST['type']) && $_REQUEST['type'] == 'instant_download') selected @endif>
                                    Instant Download</option>
                                <option value="mp3_cd"@if (!empty($_REQUEST['type']) && $_REQUEST['type'] == 'mp3_cd') selected @endif>MP3 cd</option>
                            </select>
                        </div>  --}}
                    </div>
                </div><br>
                <div class="home-pro-list">
                    <table class="w-100" id="showallDatatable">
                        <thead>
                            <th>image</th>
                            <th>Title</th>
                            <th>Popularity</th>
                            <th>Episodes</th>
                            <th>MP3 CDs</th>
                            <th>Price</th>
                        </thead>
                        <tbody>
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
                            @forelse ($productList as $product)
                                {{--  @php
                                    $currentDate = date('Y-m-d');
                                    $offerCheck = \DB::table('offer_management')
                                        ->selectRaw('`discount_amount`,`type`')
                                        ->whereRaw(' DATE(`start_date`)<="' . $currentDate . '" AND DATE(`end_date`)>="' . $currentDate . '" AND FIND_IN_SET(' . $product->id . ',`applicable_shows`) AND `status`="1"')
                                        ->first();
                                    $flag = 0;
                                    if (!empty($offerCheck)):
                                        $flag = 1;
                                    endif;
                                @endphp  --}}
                                @php
                                    $flag = 0;
                                    $flag1 = 0;
                                    if (!empty($applicableCategoryToShow) && in_array($product->id, $applicableCategoryToShow)):
                                        $flag = 1;
                                    endif;
                                    if (!empty($applicableShows) && in_array($product->id, $applicableShows)):
                                        $flag1 = 1;
                                    endif;
                                @endphp
                                <tr>
                                    <td>
                                        <div class="list-p-img">
                                            <a href="{{ url('show/details/' . $product->id) }}">
                                                <img
                                                    src="{{ env('IMAGE_URL') }}uploads/categories/{{ $product->categorySlug }}/{{ $product->image }}">
                                            </a>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="list-p-title">
                                            <a href="{{ url('show/details/' . $product->id) }}">
                                                <h3>{{ $product->title }}</h3>
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
                                        {{ $product->no_of_episodes }}
                                    </td>
                                    <td>{{ $product->no_of_mp3_cds }}</td>
                                    <td>
                                        <div class="list-p-btn">
                                            {{--  @if (!empty($productall->discount_amount))
                                                        <del>${{ $productall->instant_download_price }}</del>
                                                    @endif  --}}
                                            @if (!empty($_REQUEST['type']) && $_REQUEST['type'] == 'instant_download')
                                                @if ($flag == 1)
                                                    @if ($flag1 == 1)
                                                        <del>${{ $product->instant_download_price }}</del>
                                                        @if ($checkSalesToday?->discount_type === 'P')
                                                            @php
                                                                $discountVal = ((float) $product->instant_download_price * (float) $checkSalesToday?->discount_amount) / 100;
                                                                $price = (float) $product->instant_download_price - (float) $discountVal;
                                                            @endphp
                                                        @elseif ($checkSalesToday?->discount_type === 'F')
                                                            @php
                                                                $price = (float) $product->instant_download_price - (float) $checkSalesToday?->discount_amount;
                                                            @endphp
                                                        @endif
                                                        <span> ${{ number_format($price, 2) }}</span>
                                                    @else
                                                        @if (
                                                            $product->instant_download_price >= $checkSalesDateRange?->min_price_range &&
                                                                $product->instant_download_price <= $checkSalesDateRange?->max_price_range)
                                                            <del>${{ $product->instant_download_price }}</del>
                                                            @if ($checkSalesDateRange?->discount_type === 'P')
                                                                @php
                                                                    $discountVal = ((float) $product->instant_download_price * (float) $checkSalesDateRange?->discount_amount) / 100;
                                                                    $price = (float) $product->instant_download_price - (float) $discountVal;
                                                                @endphp
                                                            @elseif ($checkSalesDateRange?->discount_type === 'F')
                                                                @php
                                                                    $price = (float) $product->instant_download_price - (float) $checkSalesDateRange?->discount_amount;
                                                                @endphp
                                                            @endif
                                                            <span> ${{ number_format($price, 2) }}</span>
                                                        @else
                                                            <span>
                                                                ${{ $product->instant_download_price }}</span>
                                                        @endif
                                                    @endif
                                                @else
                                                    <span> ${{ $product->instant_download_price }}</span>
                                                @endif
                                            @elseif(!empty($_REQUEST['type']) && $_REQUEST['type'] == 'mp3_cd')
                                                @if ($flag == 1)
                                                    @if ($flag1 == 1)
                                                        <del>${{ $product->mp3_cd_price }}</del>
                                                        @if ($checkSalesToday?->discount_type === 'P')
                                                            @php
                                                                $discountVal = ((float) $product->mp3_cd_price * (float) $checkSalesToday?->discount_amount) / 100;
                                                                $price = (float) $product->mp3_cd_price - (float) $discountVal;
                                                            @endphp
                                                        @elseif ($checkSalesToday?->discount_type === 'F')
                                                            @php
                                                                $price = (float) $product->mp3_cd_price - (float) $checkSalesToday?->discount_amount;
                                                            @endphp
                                                        @endif
                                                        <span> ${{ number_format($price, 2) }}</span>
                                                    @else
                                                        @if (
                                                            $product->mp3_cd_price >= $checkSalesDateRange?->min_price_range &&
                                                                $product->mp3_cd_price <= $checkSalesDateRange?->max_price_range)
                                                            <del>${{ $product->mp3_cd_price }}</del>
                                                            @if ($checkSalesDateRange?->discount_type === 'P')
                                                                @php
                                                                    $discountVal = ((float) $product->mp3_cd_price * (float) $checkSalesDateRange?->discount_amount) / 100;
                                                                    $price = (float) $product->mp3_cd_price - (float) $discountVal;
                                                                @endphp
                                                            @elseif ($checkSalesDateRange?->discount_type === 'F')
                                                                @php
                                                                    $price = (float) $product->mp3_cd_price - (float) $checkSalesDateRange?->discount_amount;
                                                                @endphp
                                                            @endif
                                                            <span> ${{ number_format($price, 2) }}</span>
                                                        @else
                                                            <span>
                                                                ${{ $product->mp3_cd_price }}</span>
                                                        @endif
                                                    @endif
                                                @else
                                                    <span> ${{ $product->mp3_cd_price }}</span>
                                                @endif
                                            @else
                                                @if ($flag == 1)
                                                    @if ($flag1 == 1)
                                                        <del>${{ $product->instant_download_price }}</del>
                                                        @if ($checkSalesToday?->discount_type === 'P')
                                                            @php
                                                                $discountVal = ((float) $product->instant_download_price * (float) $checkSalesToday?->discount_amount) / 100;
                                                                $price = (float) $product->instant_download_price - (float) $discountVal;
                                                            @endphp
                                                        @elseif ($checkSalesToday?->discount_type === 'F')
                                                            @php
                                                                $price = (float) $product->instant_download_price - (float) $checkSalesToday?->discount_amount;
                                                            @endphp
                                                        @endif
                                                        <span> ${{ number_format($price, 2) }}</span>
                                                    @else
                                                        @if (
                                                            $product->instant_download_price >= $checkSalesDateRange?->min_price_range &&
                                                                $product->instant_download_price <= $checkSalesDateRange?->max_price_range)
                                                            <del>${{ $product->instant_download_price }}</del>
                                                            @if ($checkSalesDateRange?->discount_type === 'P')
                                                                @php
                                                                    $discountVal = ((float) $product->instant_download_price * (float) $checkSalesDateRange?->discount_amount) / 100;
                                                                    $price = (float) $product->instant_download_price - (float) $discountVal;
                                                                @endphp
                                                            @elseif ($checkSalesDateRange?->discount_type === 'F')
                                                                @php
                                                                    $price = (float) $product->instant_download_price - (float) $checkSalesDateRange?->discount_amount;
                                                                @endphp
                                                            @endif
                                                            <span> ${{ number_format($price, 2) }}</span>
                                                        @else
                                                            <span>
                                                                ${{ $product->instant_download_price }}</span>
                                                        @endif
                                                    @endif
                                                @else
                                                    <span> ${{ $product->instant_download_price }}</span>
                                                @endif
                                            @endif
                                            <button type="button" class="addToCart" data-id="{{ $product->id }}">Add To
                                                Cart</button>
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
                {{--   <div class="custom-pagination mt-3">
                    <nav aria-label="Page navigation example">
                        <ul class="pagination justify-content-end">
                            <li class="page-item disabled">
                                <a class="page-link" href="#" tabindex="-1">Previous</a>
                            </li>
                            <li class="page-item"><a class="page-link" href="#">1</a></li>
                            <li class="page-item"><a class="page-link" href="#">2</a></li>
                            <li class="page-item"><a class="page-link" href="#">3</a></li>
                            <li class="page-item">
                                <a class="page-link" href="#">Next</a>
                            </li>
                        </ul>
                    </nav>
                </div>  --}}
            </div>

        </div>

    </div>
    <form action="{{ url('search') }}" method="GET" id="showForm">
        {{--  <input type="hidden" name="q" value="{{ !empty($_REQUEST['q']) ? $_REQUEST['q'] : '' }}"  --}}
        <input type="hidden" name="type" value="{{ !empty($_REQUEST['type']) ? $_REQUEST['type'] : '' }}"
            id="type">
    </form>
@stop
@push('scripts')
    <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.3.0/js/dataTables.responsive.min.js"></script>
    <script>
        $(document).on('change', '.cd', function(e) {
            var value = $(this).val();
            $('#type').val(value);
            $("#showForm").submit();
            //const currenturl = "{{ url()->full() }}";
            //alert(currenturl);
            //window.location.href = baseUrl+'?type='+value;
        });
        $(document).ready(function() {
            loadData();
        })
        const loadData = () => {
            //   $('#bannerTable').DataTable().destroy();
            var dataTable = $('#showallDatatable').DataTable({
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
