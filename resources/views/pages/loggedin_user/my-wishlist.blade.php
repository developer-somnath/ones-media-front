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
                    @forelse ($wishlists as $productall)
                        @php
                            $currentDate = date('Y-m-d');
                            $offerCheck = \DB::table('offer_management')
                                ->selectRaw('`discount_amount`,`type`')
                                ->whereRaw(' DATE(`start_date`)<="' . $currentDate . '" AND DATE(`end_date`)>="' . $currentDate . '" AND FIND_IN_SET(' . $productall->id . ',`applicable_shows`) AND `status`="1"')
                                ->first();
                            $flag = 0;
                            if (!empty($offerCheck)):
                                $flag = 1;
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
                                            @if ($offerCheck->type === '1')
                                                @php
                                                    $discountVal = ((float) $productall->show?->instant_download_price * (float) $offerCheck->discount_amount) / 100;
                                                    $price = (float) $productall->show?->instant_download_price - (float) $discountVal;
                                                @endphp
                                            @elseif ($offerCheck->type === '2')
                                                @php
                                                    $discountVal = (float) $offerCheck->discount_amount;
                                                    $price = (float) $productall->show?->instant_download_price - (float) $discountVal;
                                                @endphp
                                            @endif
                                            <span> ${{ number_format($price, 2) }}</span><br>
                                            {{--  <sup>Discount</sup><span> ${{ number_format($discountVal,2) }}</span>  --}}
                                        @else
                                            <span> ${{ $productall->show?->instant_download_price }}</span><br>
                                            {{--  <b>Discount</b><span> ${{ number_format(0,2) }}</span>  --}}
                                        @endif
                                    @elseif($productall->type == 2)
                                        @if ($flag == 1)
                                            @if ($offerCheck->type === '1')
                                                @php
                                                    $discountVal = ((float) $productall->show?->mp3_cd_price * (float) $offerCheck->discount_amount) / 100;
                                                    $price = (float) $productall->show?->mp3_cd_price - (float) $discountVal;
                                                @endphp
                                            @elseif ($offerCheck->type === '2')
                                                @php
                                                    $discountVal = (float) $offerCheck->discount_amount;
                                                    $price = (float) $productall->show?->mp3_cd_price - (float) $discountVal;
                                                @endphp
                                            @endif
                                            <span> ${{ number_format($price, 2) }}</span>
                                            {{--  <span> ${{ number_format($discountVal,2) }}</span>  --}}
                                        @else
                                            <span> ${{ $productall->show?->mp3_cd_price }}</span>
                                            {{--  <span> ${{ number_format(0,2) }}</span>  --}}
                                        @endif
                                    @else
                                       @if ($flag == 1)
                                            @if ($offerCheck->type === '1')
                                                @php
                                                    $discountVal = ((float) $productall->show?->instant_download_price * (float) $offerCheck->discount_amount) / 100;
                                                    $price = (float) $productall->show?->instant_download_price - (float) $discountVal;
                                                @endphp
                                            @elseif ($offerCheck->type === '2')
                                                @php
                                                    $discountVal = (float) $offerCheck->discount_amount;
                                                    $price = (float) $productall->show?->instant_download_price - (float) $discountVal;
                                                @endphp
                                            @endif
                                            <span> ${{ number_format($price, 2) }}</span>
                                            {{--  <span> ${{ number_format($discountVal,2) }}</span>  --}}
                                        @else
                                            <span> ${{ $productall->show?->instant_download_price }}</span>
                                            {{--  <span> ${{ number_format(0,2) }}</span>  --}}
                                        @endif
                                    @endif

                                </div>
                            </td>
                            <td>
                                <div class="list-p-btn">
                                    <button type="button" class="addToCartFromWishlist" data-type="{{ $productall->type }}" data-id="{{ $productall->item_id }}">Add To
                                        Cart</button>
                                    <a href="#" title="Remove" class="close">&times;</a>

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
