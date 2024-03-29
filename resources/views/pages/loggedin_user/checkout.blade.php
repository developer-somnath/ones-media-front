<link href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/responsive/2.3.0/css/responsive.dataTables.min.css" rel="stylesheet">
@extends('layouts.master')
@section('content')
    {{--   @php

    @endphp  --}}
    <div class="container">
        <div class="bredcrames-sec">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $title ? $title : '' }}</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="main-part-home">
        <div class="container">
            <div>
                <div class="page-title">
                    <h2>Checkout</h2>
                </div>
                <div class="home-pro-list">
                    <table class="w-100" id="showallDatatable">
                        <thead>
                            <th>Name</th>
                            <th>Quantity</th>
                            <th>Price</th>
                            <th>Subtotal</th>
                        </thead>
                        <tbody>
                            {{--   @forelse ($productList as $product)  --}}
                            @php
                                $shippingAndBillingAddress = Request::session()->get('shippingBillingAddress');
                                $countryShortCode = \App\Models\Countries::where('id',$shippingAndBillingAddress['shipping_country_id'])->pluck('short_code')->first();
                                $costCountry = "OTHER";
                                if($countryShortCode==='US'){
                                    $costCountry = "US";
                                }
                                if($countryShortCode==='CA'){
                                    $costCountry = "CANADA";
                                }
                                $shippingPrice = \DB::table('shipping_costs')
                                    ->where('status', '=', '1')
                                    ->where('country', '=', $costCountry)
                                    ->first();
                                $total = 0;
                                $quantity = 0;
                            @endphp
                            @if ($carts)
                                @foreach ($carts as $cart => $details)
                                    @php
                                        //dd($details);
                                        if ($details->type == 1):
                                            //$price = $details->instant_download_price;
                                            $show_name = $details->shows?->title . '(' . 'Instant Download' . ')';
                                            //$price = $details->mp3_cd_price;
                                        elseif($details->type==2):
                                            $show_name = $details->shows?->title . '(' . $details->shows?->no_of_mp3_cds . ' ' . 'Mp3 Cd' . ')';
                                        else:
                                            $show_name = $details->sampleFiles?->title . '(' . 'Sample File' . ')';
                                        endif;
                                        $total += number_format($details->price, 2) * $details->quantity;
                                        $quantity += $details->quantity;
                                    @endphp
                                    <tr>
                                        <td>
                                            <div class="list-p-title">
                                                @if($details->product_type==1)
                                                <a href="{{ url('show/details/' . $details->item_id) }}">
                                                    <h3>{{ $show_name }}</h3>
                                                </a>
                                                @else
                                                    <a href="javascript:void(0)">
                                                    <h3>{{ $show_name }}</h3>
                                                </a>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            <div class="list-p-title">
                                                @if($details->product_type==1)
                                                <span>{{ $details->quantity }}</span>
                                                @else
                                                        <span>{{ 1 }}</span>

                                                @endif
                                            </div>
                                        </td>
                                        {{--  <td>
                                            <div class="list-p-rating">
                                                <i class="fa-solid fa-star"></i>
                                                <i class="fa-solid fa-star"></i>
                                                <i class="fa-solid fa-star"></i>
                                                <i class="fa-solid fa-star d-star"></i>
                                                <i class="fa-solid fa-star d-star"></i>
                                            </div>
                                        </td>  --}}
                                        <td>
                                            ${{ number_format($details->price, 2) }}<br>
                                            <b>Discount</b> : <span>${{ $details->discount }}</span>
                                        </td>
                                        {{--  <td>{{ $product->no_of_mp3_cds }}</td>  --}}
                                        <td>
                                            <div class="list-p-btn">
                                                <span>${{ number_format($details->price, 2) * $details->quantity }}</span>
                                                {{--  <del>$5.00</del>  --}}
                                                {{--  @if (!empty($_REQUEST['type']) && $_REQUEST['type'] == 'instant_download')
                                                    <span> ${{ $product->instant_download_price }}</span>
                                                @elseif(!empty($_REQUEST['type']) && $_REQUEST['type'] == 'mp3_cd')
                                                    <span> ${{ $product->mp3_cd_price }}</span>
                                                @else
                                                    <span> ${{ $product->instant_download_price }}</span>
                                                @endif  --}}


                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <td colspan="4" class="text-center">
                                    <h6>No Product into the cart !!</h6>
                                </td>
                            @endif
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="5" class="text-right">
                                    @php
                                        if ($quantity == 0 || $quantity == '' || $quantity == null):
                                            $shipping = number_format(0, 2);
                                        elseif ($quantity == 1):
                                            $shipping = $shippingPrice->price_for_single_qty;
                                        elseif ($quantity == 2):
                                            $shipping = $shippingPrice->price_for_double_qty;
                                        else:
                                            $shipping = $shippingPrice->price_for_more_than_three_or_equal;
                                        endif;
                                    @endphp
                                    <h6><b>Shipping Charges</b> : ${{ $shipping }} </h6>
                                    <h4><strong>Total ${{ number_format(($total + $shipping), 2) }}</strong></h4>
                                </td>
                            </tr>

                        </tfoot>
                    </table>

                </div>
                <div class="text-right">
                    <a href="{{ url('/payment') }}" class="btn btn-success">
                                                Proceed to Payment</a>
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
            </div><br>
            
        </div>


    </div>
    
    {{--   <form action="{{ url('/shows-all') }}" method="GET" id="showForm">
        <input type="hidden" name="filter" value="{{ !empty($_REQUEST['filter']) ? $_REQUEST['filter'] : '' }}"
            id="filter">
        <input type="hidden" name="type" value="{{ !empty($_REQUEST['type']) ? $_REQUEST['type'] : '' }}"
            id="type">
    </form>  --}}
@stop
@push('scripts')


    <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.3.0/js/dataTables.responsive.min.js"></script>
    <script type="text/javascript">
        var stateId = "{{ Auth::user()->state_id ? Auth::user()->state_id : '' }}"

        $(document).ready(function() {
            $('#country_id').val("{{ !is_null(Auth::user()) ? Auth::user()->country_id : '' }}").change()
        })
        $(document).on('change', '#country_id', function() {
            let html = ''
            if ($(this).val() != "") {
                $.ajax({
                    type: "post",
                    url: "{{ route('state-list-by-country-id') }}",
                    data: {
                        _token: _token,
                        countryId: $(this).val()
                    },
                    dataType: "JSON",
                    beforeSend: function() {
                        $('#state_id').html('<option value="">Processing...</option>')
                    },
                    success: function(response) {

                        if (response.status) {
                            response.data.forEach(val => {
                                if (stateId == val.id) {
                                    var selected = "selected"
                                } else {
                                    var selected = ""

                                }
                                html += '<option value="' + val.id + '"' + selected + '>' + val
                                    .name + '</option>'
                            })
                            $('#state_id').html(html)
                        }
                    }
                })
            } else {
                $('#state_id').html('<option value="">Select State</option>');
            }
        })

        // This is your test publishable API key.

    </script>
@endpush
