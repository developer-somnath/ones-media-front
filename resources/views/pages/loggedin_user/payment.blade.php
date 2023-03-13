
@push('css')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/checkout.css') }}">
@endpush
@extends('layouts.master')
@section('content')
<div class="container">
  <div class="bredcrames-sec">
      <nav aria-label="breadcrumb">
          <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
              <li class="breadcrumb-item active" aria-current="page">{{ $title ? $title : '' }}
              </li>
          </ol>
      </nav>
  </div>
</div>
<div class="main-part-home">
  <div class="container">
      <div>
        <form id="payment-form">
            <div id="link-authentication-element">
              <!--Stripe.js injects the Link Authentication Element-->
            </div>
            <div id="payment-element">
              <!--Stripe.js injects the Payment Element-->
            </div>
            <button id="submit">
              <div class="spinner hidden" id="spinner"></div>
              <span id="button-text">Pay now</span>
            </button>
            <div id="payment-message" class="hidden"></div>
          </form>      
    </div>
  </div>
</div>
@stop
@push('scripts')
<script src="https://js.stripe.com/v3/"></script>
<script>
const stripe = Stripe("{{ env('STRIPE_PK') }}");
let emailAddress = '{{ auth()->user()->email }}';
</script>
<script src="{{ asset('assets/js/checkout.js') }}"></script>
@endpush