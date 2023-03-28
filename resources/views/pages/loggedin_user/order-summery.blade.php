@extends('layouts.master')
@section('content')
<div class="order-summery">
    <h4>Your order has been confirmed with referenc no. <strong>#{{ $info?->order_alt_id }}</strong></h4>
    <ul>
        <li>
            <h3>Order Date</h3>
            <p>{{ $info?->created_at? date('d-m-Y',strtotime($info?->created_at)):'' }}</p>
        </li>
        <li>
            <h3>Payment Method</h3>
            <p>{{ strtoupper($info?->transaction?->payment_method) }}</p>
        </li>
        <li>
            <h3>Billing Address</h3>
            <strong>{{ @$info->address->where('type','B')->first()->first_name . ' ' . @$info->address->where('type','B')->first()->last_name }}</strong><br>
                                            {{ @$info->address->where('type','B')->first()->street_address }}<br>{{ @$info->address->where('type','B')->first()->address_line_2 }}<br>{{ @$info->address->where('type','B')->first()->city }},
                                            {{ @$info->address->where('type','B')->first()->state->name }}, {{ @$info->address->where('type','B')->first()->country->name }},
                                            {{ @$info->address->where('type','B')->first()->zip_code }} <br><abbr title="Phone">Phone:</abbr>
                                            {{ @$info->address->where('type','B')->first()->phone }}
        </li>
        <li>
            <h3>Shipping Address</h3>
            <strong>{{ @$info->address->where('type','S')->first()->first_name . ' ' . @$info->address->where('type','S')->first()->last_name }}</strong><br>
                                            {{ @$info->address->where('type','S')->first()->street_address }}<br>{{ @$info->address->where('type','S')->first()->address_line_2 }}<br>{{ @$info->address->where('type','S')->first()->city }},
                                            {{ @$info->address->where('type','S')->first()->state->name }}, {{ @$info->address->where('type','S')->first()->country->name }},
                                            {{ @$info->address->where('type','S')->first()->zip_code }} <br><abbr title="Phone">Phone:</abbr>
                                            {{ @$info->address->where('type','S')->first()->phone }}
        </li>
    </ul>
    @forelse ($info->items as $value)
    <div class="product-l-s">
        <div class="product-l-s-img">
            @if (@$value->product_type == 1)

            <img src="{{ env('IMAGE_URL') }}uploads/categories/{{ @$value->show?->category?->slug }}/{{ @$value->show->image }}">
            @else
                    <img src="{{ env('IMAGE_URL') }}uploads/sample-files/{{ @$value->sample?->image }}"
                        width="30">
            @endif
        </div>
        <div class="product-l-info">
            @if (@$value->product_type == 1)
                    <h3>{{ @$value->show->title }}</h3>
                @else
                    <h3>{{ @$value->sample->title }}(Sample)</h3>
                @endif
                @if (@$value->type == 1)
                <span class="badge badge-primary">Instant Download</span>
                    
                @elseif(@$value->type == 2)
                    <span class="badge badge-secondary">CD</span>
                @else
                <span class="badge badge-warning">Sample</span>
                @endif
        </div>
        <div class="qty-p">
            <span>Qty @if (@$value->product_type == 1)
                {{ @$value->quantity }}
            @else
                {{ '1' }}
            @endif</span>
        </div>
        <div class="product-l-price">
            ${{ @$value->item_amount }}        </div>
    </div>
    @empty
    @endforelse
    
    
    <div class="product-subtotal">
        <ul>
            <li>Subtotal</li>
            <li>${{ number_format((float)($info?->oder_amount - $info?->shipping_cost),2) }}</li>
        </ul>
        <ul>
            <li>Shipping</li>
            <li>${{ $info?->shipping_cost }}</li>
        </ul>
        <ul>
            <li><b>Total</b></li>
            <li><b>${{ $info?->oder_amount }}</b></li>
        </ul>
    </div>
    <p>We will send you shipping confirmation when your items are dispatched, and hope you enjoy your purchase !</p>
    <strong>Thank You!</strong>
    <p><a href="{{ route('order-history') }}" class="btn btn-info" >Go to Orders</a></p>
</div>
@stop