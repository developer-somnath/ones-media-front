@extends('layouts.logged_in_master')
@section('content')
    <div class="col-md-8">
        <div class="my-acc-right">
            <h4>{{ $title ? $title : '' }}</h4>
            <form class="userFrm" data-action="my-account" method="post" data-validation="requiredCheck">
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>First Name <sup>*</sup></label>
                            <input type="text" placeholder="First Name" class="form-control requiredCheck" id="first_name"
                                name="first_name" data-check="First Name" value="{{ Auth::user()->first_name }}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Last Name <sup>*</sup></label>
                            <input type="text" placeholder="Last Name" class="form-control requiredCheck" id="last_name"
                                name="last_name" data-check="Last Name" value="{{ Auth::user()->last_name }}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Email <sup>*</sup></label>
                            <input type="email" placeholder="Email" class="form-control requiredCheck" id="email"
                                name="email" data-check="Email" value="{{ Auth::user()->email }}" readonly>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Phone <sup>*</sup></label>
                            <input type="number" placeholder="Phone No" class="form-control requiredCheck" id="phone"
                                name="phone" data-check="Phone Number" value="{{ Auth::user()->phone }}">
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
                            <select class="form-control requiredCheck" id="state_id" name="state_id" data-check="State">
                                <option value="">-Select State-</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>City <sup>*</sup></label>
                            <input name="city" id="city" placeholder="Enter City" type="text" class="form-control requiredCheck" data-check="City" value="{{!is_null(Auth::user())?Auth::user()->city:''}}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Zip Code <sup>*</sup></label>
                            <input type="text" placeholder="Zip" class="form-control requiredCheck" id="zip_code"
                                name="zip_code" data-check="Zip Code" value="{{ Auth::user()->zip_code }}">
                        </div>
                    </div>
                    <div class="col-md-12">
                        <input type="submit" value="Update" class="submit-btn">
                    </div>
                </div>
            </form>
        </div>
    </div>
@stop
@push('scripts')
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
            }else{
                $('#state_id').html('<option value="">Select State</option>'); 
            }
        })
    </script>
@endpush
