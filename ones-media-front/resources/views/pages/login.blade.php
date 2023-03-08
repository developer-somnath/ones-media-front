@extends('layouts.master')
@section('content')
    <div class="m-account-page">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <form class="userFrm" data-action="user-check" method="post" data-validation="requiredCheck1">
                        @csrf
                        <div class="login-form">
                            <h3>Sign in to your Account</h3>
                            <p>Sign in to access your account, check your order status, and manage your wish list.</p>
                            <div class="form-group">
                                <label>Email <sup>*</sup></label>
                                <input type="email" placeholder="Email" class="form-control requiredCheck1" id="email"
                                    name="email" data-check="Email">
                            </div>
                            <div class="form-group">
                                <label>Password <sup>*</sup></label>
                                <input type="password" placeholder="****" class="form-control requiredCheck1" id="password"
                                    name="password" data-check="Password">
                            </div>
                            <div class="row align-items-center">
                                <div class="col-md-6">
                                    <input type="submit" value="Login" class="submit-btn">
                                </div>
                                <div class="col-md-6 text-right">
                                    <a href="#">Forgot Your Password ?</a>
                                </div>
                            </div>

                        </div>
                    </form>
                </div>
                <div class="col-md-6">
                    <div class="login-form">
                        <h3>Not registered? Create now!</h3>
                        <p>Sign up to access your account, check your order status, and manage your wish list.</p>
                        <form class="userFrm" data-action="register" method="post" data-validation="requiredCheck2">
                            @csrf
                            <div class="row align-items-center">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>First Name <sup>*</sup></label>
                                        <input type="text" placeholder="First Name" class="form-control requiredCheck2"
                                            id="first_name" name="first_name" data-check="First Name">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Last Name <sup>*</sup></label>
                                        <input type="text" placeholder="Last Name"
                                            class="form-control requiredCheck requiredCheck2" id="last_name"
                                            name="last_name" data-check="Last Name">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Email <sup>*</sup></label>
                                        <input type="email" placeholder="Email" class="form-control requiredCheck2"
                                            id="user_email" name="user_email" data-check="Email">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Phone <sup>*</sup></label>
                                        <input type="number" placeholder="Phone No" class="form-control requiredCheck2"
                                            id="user_phone" name="user_phone" data-check="Phone Number">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Country <sup>*</sup></label>
                                        <select class="form-control requiredCheck2" id="country_id" name="country_id"
                                            data-check="Country">
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
                                        <select class="form-control requiredCheck2" id="state_id" name="state_id"
                                            data-check="State">
                                            <option value="">-Select State-</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>City <sup>*</sup></label>
                                        <input name="city" id="city" placeholder="Enter City" type="text"
                                            class="form-control requiredCheck2" data-check="City" value="">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Zip Code <sup>*</sup></label>
                                        <input type="text" placeholder="Zip" class="form-control requiredCheck2"
                                            id="zip_code" name="zip_code" data-check="Zip Code" value="">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Password <sup>*</sup></label>
                                        <input type="password" placeholder="****" class="form-control requiredCheck2"
                                            id="user_password" name="user_password" data-check="Password">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Confirm Password <sup>*</sup></label>
                                        <input type="password" placeholder="****" class="form-control requiredCheck2"
                                            id="confirmpassword" name="confirmpassword" data-check="Confirm Password">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <input type="submit" value="Register Now" class="submit-btn">
                                </div>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
@push('scripts')
    <script type="text/javascript">
        var stateId = '';
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
    </script>
@endpush
