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
                    <h2>Address</h2>
                </div>
               
            </div><br>
                <form class="userFrm" data-action="place-order" method="post" data-validation="requiredCheck">
                    @csrf
                <div class="row">
                    <div class="col-md-6">
                        {{--  <div class="login-form">  --}}
                        <h4>Billing Address</h4>

                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>First Name <sup>*</sup></label>
                                    <input type="text" placeholder="First Name" class="form-control requiredCheck"
                                        id="billing_first_name" name="billing_first_name" data-check="Billing First Name"
                                        value="{{ auth()->user()->first_name }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Last Name <sup>*</sup></label>
                                    <input type="text" placeholder="Last Name" class="form-control requiredCheck"
                                        id="billing_last_name" name="billing_last_name" data-check="Billing Last Name"
                                        value="{{ auth()->user()->last_name }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Email <sup>*</sup></label>
                                    <input type="email" placeholder="Email" class="form-control requiredCheck"
                                        id="billing_email" name="billing_email" data-check="Billing Email"
                                        value="{{ auth()->user()->email }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Phone <sup>*</sup></label>
                                    <input type="number" placeholder="Phone No" class="form-control requiredCheck"
                                        id="billing_phone" name="billing_phone" data-check="Billing Phone Number"
                                        value="{{ auth()->user()->phone }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Street Address<sup>*</sup></label>
                                    <input type="text" placeholder="Street Address" class="form-control requiredCheck"
                                        id="billing_street_address" name="billing_street_address" data-check="Billing Street Address"
                                        value="{{ auth()->user()->street_address }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Address Line 2 <sup>*</sup></label>
                                    <input type="text" placeholder="Address Line 2" class="form-control requiredCheck"
                                        id="billing_address_line_2" name="billing_address_line_2"
                                        data-check="Billing Address Line 2">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Country <sup>*</sup></label>
                                    <select class="form-control requiredCheck" id="billing_country_id" name="billing_country_id"
                                        data-check="Billing Country">
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
                                    <select class="form-control requiredCheck" id="billing_state_id" name="billing_state_id"
                                        data-check="State">
                                        <option value="">-Select State-</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>City <sup>*</sup></label>
                                    <input name="billing_city" id="billing_city" placeholder="Enter City" type="text"
                                        class="form-control requiredCheck" data-check="Billing City"
                                        value="{{ auth()->user()->city }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Zip Code <sup>*</sup></label>
                                    <input type="text" placeholder="Zip" class="form-control requiredCheck"
                                        id="billing_zip_code" name="billing_zip_code" data-check="Billing Zip Code"
                                        value="{{ auth()->user()->zip_code }}">
                                </div>
                            </div>

                        </div>
                        {{--  </div>  --}}
                    </div>
                    <div class="col-md-6">
                        {{--  <div class="login-form">  --}}
                        <h4>Shipping Address</h4>
                        <label><input type="checkbox" name="same_as_billing_address" id="same_as_billing_address" value="0"> Same as Billing Address</label>
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>First Name <sup>*</sup></label>
                                    <input type="text" placeholder="First Name" class="form-control requiredCheck"
                                        id="shipping_first_name" name="shipping_first_name" data-check="Shipping First Name"
                                        value="">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Last Name <sup>*</sup></label>
                                    <input type="text" placeholder="Last Name" class="form-control requiredCheck"
                                        id="shipping_last_name" name="shipping_last_name" data-check="Shipping Last Name"
                                        value="">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Email <sup>*</sup></label>
                                    <input type="email" placeholder="Email" class="form-control requiredCheck"
                                        id="shipping_email" name="shipping_email" data-check="Shipping Email"
                                        value="">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Phone <sup>*</sup></label>
                                    <input type="number" placeholder="Phone No" class="form-control requiredCheck"
                                        id="shipping_phone" name="shipping_phone" data-check="Shipping Phone Number"
                                        value="">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Street Address<sup>*</sup></label>
                                    <input type="text" placeholder="Street Address" class="form-control requiredCheck"
                                        id="shipping_street_address" name="shipping_street_address" data-check="Shipping Street Address"
                                        value="">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Address Line 2 <sup>*</sup></label>
                                    <input type="text" placeholder="Address Line 2" class="form-control requiredCheck"
                                        id="shipping_address_line_2" name="shipping_address_line_2"
                                        data-check="Shipping Address Line 2">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Country <sup>*</sup></label>
                                    <select class="form-control requiredCheck" id="shipping_country_id" name="shipping_country_id"
                                        data-check="Shiiping Country">
                                        <option value="">-Select Country-</option>
                                        @if (count($countryList) > 0):
                                            @foreach ($countryList as $value)
                                                <option value="{{ $value->id }}">
                                                    {{ $value->name }}</option>
                                            @endforeach;
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>State <sup>*</sup></label>
                                    <select class="form-control requiredCheck" id="shipping_state_id" name="shipping_state_id"
                                        data-check="Shipping State">
                                        <option value="">-Select State-</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>City <sup>*</sup></label>
                                    <input name="shipping_city" id="shipping_city" placeholder="Enter City" type="text"
                                        class="form-control requiredCheck" data-check="Shipping City"
                                        value="">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Zip Code <sup>*</sup></label>
                                    <input type="text" placeholder="Zip" class="form-control requiredCheck"
                                        id="shipping_zip_code" name="shipping_zip_code" data-check="Shipping Zip Code"
                                        value="">
                                </div>
                            </div>

                        </div>
                        {{--  </div>  --}}
                    </div>
                </div>
                    

                    <div class="col-md-6">
                        <input type="submit" value="Continue to Checkout" class="submit-btn">
                    </div>
                </form>
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
            $('#billing_country_id').val("{{ !is_null(Auth::user()) ? Auth::user()->country_id : '' }}").change()
        })
        $(document).on('change', '#billing_country_id', function() {
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
                        $('#billing_state_id').html('<option value="">Processing...</option>')
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
                            $('#billing_state_id').html(html)
                        }
                    }
                })
            } else {
                $('#billing_state_id').html('<option value="">Select State</option>');
            }
        })
        $(document).on('change', '#shipping_country_id', function() {
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
                        $('#shipping_state_id').html('<option value="">Processing...</option>')
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
                            $('#shipping_state_id').html(html)
                        }
                    }
                })
            } else {
                $('#shipping_state_id').html('<option value="">Select State</option>');
            }
        });
        $(document).on('change','#same_as_billing_address',function(){
            if($(this).is(':checked')){
                copyAddress(true);
            }else{
                copyAddress(false);
            }

        })

        // This is your test publishable API key.
        function copyAddress(flag) {
            const textValues = ['first_name', 'last_name', 'email', 'phone','street_address','address_line_2','country_id','state_id','city','zip_code'];
            if(flag){
                textValues.forEach(function (item, index) {
                    document.getElementById(`shipping_${item}`).value = document.getElementById(`billing_${item}`).value;
                    
                });
                let countryId = $(`#billing_country_id`).val();
                $(`#shipping_country_id`).val(countryId).change();
                let stateId = $(`#billing_state_id`).val();
                $(`#shipping_state_id`).val(stateId);
            }else{
                textValues.forEach(function (item, index) {
                    document.getElementById(`shipping_${item}`).value ='';
                });
            }
            
            
        }

    </script>
@endpush
