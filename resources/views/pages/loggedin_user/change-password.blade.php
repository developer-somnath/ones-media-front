@extends('layouts.logged_in_master')
@section('content')
    <div class="col-md-8">
        <div class="my-acc-right">
            <h4>{{ $title ? $title : '' }}</h4>
            <form class="userFrm" data-action="change-password" method="post" data-validation="requiredCheck">
                <div class="row">
                    {{--  <form class="userFrm" data-action="change-password" method="post" data-validation="requiredCheck">  --}}
                    @csrf
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Old Password <sup>*</sup></label>
                            <input type="password" name="oldpassword" id="oldpassword" class="form-control requiredCheck"
                                placeholder="Old Password" data-check="Old Password">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>New Password <sup>*</sup></label>
                            <input type="password" name="newpassword" id="newpassword" class="form-control requiredCheck"
                                placeholder="New Password" data-check="New Password">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Confirm Password <sup>*</sup></label>
                            <input type="password" name="confirmpassword" id="confirmpassword"
                                class="form-control requiredCheck" placeholder="Confirm Password"
                                data-check="Confirm Password">
                        </div>
                    </div>

                    <div class="col-md-12">
                        <input type="submit" value="Update" class="submit-btn">
                    </div>
                    {{--  </form>  --}}
                </div>
            </form>
        </div>
    </div>
@stop
