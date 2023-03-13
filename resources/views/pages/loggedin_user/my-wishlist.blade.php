<link href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/responsive/2.3.0/css/responsive.dataTables.min.css" rel="stylesheet">
@extends('layouts.logged_in_master')
@section('content')
    <div class="col-md-8">
        <div class="my-acc-right">
            <h4>{{ $title ? $title : '' }}({{ $wishlists->count() }})</h4>
            <table class="w-100" id="wishlistpageTable">
                <thead>
                    <th>Image</th>
                    <th>Title</th>
                    <th>Price</th>
                    <th>Action</th>
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
                    @forelse ($wishlists as $productall)
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
                                    <a href="{{ url('show/details/' . $productall->item_id) }}">
                                        <img
                                            src="{{ env('IMAGE_URL') }}uploads/categories/{{ $productall->show?->category?->slug }}/{{ $productall->show?->image }}">
                                    </a>
                                </div>
                            </td>
                            <td>
                                <div class="list-p-title">
                                    <a href="{{ url('show/details/' . $productall->item_id) }}">
                                        <h3>{{ $productall->show?->title }}</h3>
                                    </a>
                                </div>
                            </td>

                            <td>
                                <div class="list-p-btn">
                                    @if ($productall->type == 1)
                                        @if ($flag == 1)
                                            @if ($flag1 == 1)
                                                @if ($checkSalesToday?->discount_type === 'P')
                                                    @php
                                                        $discountVal = ((float) $productall->show?->instant_download_price * (float) $checkSalesToday?->discount_amount) / 100;
                                                        $price = (float) $productall->show?->instant_download_price - (float) $discountVal;
                                                    @endphp
                                                @elseif ($checkSalesToday?->discount_type === 'F')
                                                    @php
                                                        $price = (float) $productall->show?->instant_download_price - (float) $checkSalesToday?->discount_amount;
                                                    @endphp
                                                @endif
                                                <span> ${{ number_format($price, 2) }}</span>
                                            @else
                                                @if (
                                                    $productall->show?->instant_download_price >= $checkSalesDateRange?->min_price_range &&
                                                        $productall->show?->instant_download_price <= $checkSalesDateRange?->max_price_range)
                                                    @if ($checkSalesDateRange?->discount_type === 'P')
                                                        @php
                                                            $discountVal = ((float) $productall->show?->instant_download_price * (float) $checkSalesDateRange?->discount_amount) / 100;
                                                            $price = (float) $productall->show?->instant_download_price - (float) $discountVal;
                                                        @endphp
                                                    @elseif ($checkSalesDateRange?->discount_type === 'F')
                                                        @php
                                                            $price = (float) $productall->show?->instant_download_price - (float) $checkSalesDateRange?->discount_amount;
                                                        @endphp
                                                    @endif
                                                    <span> ${{ number_format($price, 2) }}</span>
                                                @else
                                                    <span>
                                                        ${{ $productall->show?->instant_download_price }}</span>
                                                @endif
                                            @endif
                                        @else
                                            <span> ${{ $productall->show?->instant_download_price }}</span>
                                        @endif
                                    @elseif($productall->type == 2)
                                        @if ($flag == 1)
                                            @if ($flag1 == 1)
                                                @if ($checkSalesToday?->discount_type === 'P')
                                                    @php
                                                        $discountVal = ((float) $productall->show?->mp3_cd_price * (float) $checkSalesToday?->discount_amount) / 100;
                                                        $price = (float) $productall->show?->mp3_cd_price - (float) $discountVal;
                                                    @endphp
                                                @elseif ($checkSalesToday?->discount_type === 'F')
                                                    @php
                                                        $price = (float) $productall->show?->mp3_cd_price - (float) $checkSalesToday?->discount_amount;
                                                    @endphp
                                                @endif
                                                <span> ${{ number_format($price, 2) }}</span>
                                            @else
                                                @if (
                                                    $productall->show?->mp3_cd_price >= $checkSalesDateRange?->min_price_range &&
                                                        $productall->show?->mp3_cd_price <= $checkSalesDateRange?->max_price_range)
                                                    @if ($checkSalesDateRange?->discount_type === 'P')
                                                        @php
                                                            $discountVal = ((float) $productall->show?->mp3_cd_price * (float) $checkSalesDateRange?->discount_amount) / 100;
                                                            $price = (float) $productall->show?->mp3_cd_price - (float) $discountVal;
                                                        @endphp
                                                    @elseif ($checkSalesDateRange?->discount_type === 'F')
                                                        @php
                                                            $price = (float) $productall->show?->mp3_cd_price - (float) $checkSalesDateRange?->discount_amount;
                                                        @endphp
                                                    @endif
                                                    <span> ${{ number_format($price, 2) }}</span>
                                                @else
                                                    <span>
                                                        ${{ $productall->show?->mp3_cd_price }}</span>
                                                @endif
                                            @endif
                                        @else
                                            <span> ${{ $productall->show?->mp3_cd_price }}</span>
                                        @endif
                                    @else
                                        @if ($flag == 1)
                                            @if ($flag1 == 1)
                                                @if ($checkSalesToday?->discount_type === 'P')
                                                    @php
                                                        $discountVal = ((float) $productall->show?->instant_download_price * (float) $checkSalesToday?->discount_amount) / 100;
                                                        $price = (float) $productall->show?->instant_download_price - (float) $discountVal;
                                                    @endphp
                                                @elseif ($checkSalesToday?->discount_type === 'F')
                                                    @php
                                                        $price = (float) $productall->show?->instant_download_price - (float) $checkSalesToday?->discount_amount;
                                                    @endphp
                                                @endif
                                                <span> ${{ number_format($price, 2) }}</span>
                                            @else
                                                @if (
                                                    $productall->show?->instant_download_price >= $checkSalesDateRange?->min_price_range &&
                                                        $productall->show?->instant_download_price <= $checkSalesDateRange?->max_price_range)
                                                    @if ($checkSalesDateRange?->discount_type === 'P')
                                                        @php
                                                            $discountVal = ((float) $productall->show?->instant_download_price * (float) $checkSalesDateRange?->discount_amount) / 100;
                                                            $price = (float) $productall->show?->instant_download_price - (float) $discountVal;
                                                        @endphp
                                                    @elseif ($checkSalesDateRange?->discount_type === 'F')
                                                        @php
                                                            $price = (float) $productall->show?->instant_download_price - (float) $checkSalesDateRange?->discount_amount;
                                                        @endphp
                                                    @endif
                                                    <span> ${{ number_format($price, 2) }}</span>
                                                @else
                                                    <span>
                                                        ${{ $productall->show?->instant_download_price }}</span>
                                                @endif
                                            @endif
                                        @else
                                            <span> ${{ $productall->show?->instant_download_price }}</span>
                                        @endif
                                    @endif

                                </div>
                            </td>
                            <td>
                                <div class="list-p-btn">
                                    <button type="button" class="addToCartFromWishlist"
                                        data-type="{{ $productall->type }}" data-id="{{ $productall->item_id }}">Add To
                                        Cart</button>
                                    <a href="javascript:void(0)" title="Remove" class="close wishlistRemove"  data-id="{{ $productall->item_id }}">&times;</a>

                                </div>

                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" align="center">No Data Available!</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@stop
@push('scripts')
    <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.3.0/js/dataTables.responsive.min.js"></script>
    <script>
        $(document).ready(function() {
            loadData();
        })
        const loadData = () => {
            //   $('#bannerTable').DataTable().destroy();
            var dataTable = $('#wishlistpageTable').DataTable({
                dom: 'Bfrtip',
                processing: true,
                serverSide: false,
                autoWidth: false,
                responsive: true,
                searching: false,


            });
        }
    </script>
@endpush
