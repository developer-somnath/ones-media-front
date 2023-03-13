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
                                $shippingPrice = DB::table('shipping_costs')
                                    ->where('id', '=', 1)
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
                                    <h4><strong>Total ${{ $total + $shipping }}</strong></h4>
                                </td>
                            </tr>

                        </tfoot>
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
            </div><br>
            <div class="row">
                <form class="userFrm" data-action="place-order" method="post" data-validation="requiredCheck">
                    @csrf
                    <div class="col-md-6">
                        {{--  <div class="login-form">  --}}
                        <h4>Shipping Address</h4>

                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>First Name <sup>*</sup></label>
                                    <input type="text" placeholder="First Name" class="form-control requiredCheck"
                                        id="billing_first_name" name="billing_first_name" data-check="First Name"
                                        value="{{ auth()->user()->first_name }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Last Name <sup>*</sup></label>
                                    <input type="text" placeholder="Last Name" class="form-control requiredCheck"
                                        id="billing_last_name" name="billing_last_name" data-check="Last Name"
                                        value="{{ auth()->user()->last_name }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Email <sup>*</sup></label>
                                    <input type="email" placeholder="Email" class="form-control requiredCheck"
                                        id="billing_email" name="billing_email" data-check="Email"
                                        value="{{ auth()->user()->email }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Phone <sup>*</sup></label>
                                    <input type="number" placeholder="Phone No" class="form-control requiredCheck"
                                        id="billing_phone" name="billing_phone" data-check="Phone Number"
                                        value="{{ auth()->user()->phone }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Street Address<sup>*</sup></label>
                                    <input type="text" placeholder="Street Address" class="form-control requiredCheck"
                                        id="street_address" name="street_address" data-check="Street Address"
                                        value="{{ auth()->user()->street_address }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Address Line 2 <sup>*</sup></label>
                                    <input type="text" placeholder="Address Line 2" class="form-control requiredCheck"
                                        id="billing_address_line_2" name="billing_address_line_2"
                                        data-check="Address Line 2">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Country <sup>*</sup></label>
                                    <select class="form-control requiredCheck" id="country_id" name="country_id"
                                        data-check="Country">
                                        <option value="">-Select Country-</option>
                                        @if (count($countryList) > 0):
                                            @foreach ($countryList as $value)
                                                <option value="{{ $value->id }}"
                                                    {{ !is_null(Auth::user()) && Auth::user()->country_id == $value->id ? 'selected' : '' }}>
                                                    {{ $value->name }}</option>
                                            @endforeach;
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>State <sup>*</sup></label>
                                    <select class="form-control requiredCheck" id="state_id" name="state_id"
                                        data-check="State">
                                        <option value="">-Select State-</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>City <sup>*</sup></label>
                                    <input name="city" id="city" placeholder="Enter City" type="text"
                                        class="form-control requiredCheck" data-check="City"
                                        value="{{ auth()->user()->city }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Zip Code <sup>*</sup></label>
                                    <input type="text" placeholder="Zip" class="form-control requiredCheck"
                                        id="zip_code" name="zip_code" data-check="Zip Code"
                                        value="{{ auth()->user()->zip_code }}">
                                </div>
                            </div>

                        </div>
                        {{--  </div>  --}}
                    </div><br>

                    <div class="col-md-6">
                        <input type="submit" value="Place Order" class="submit-btn">
                    </div>
                </form>
            </div>
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
