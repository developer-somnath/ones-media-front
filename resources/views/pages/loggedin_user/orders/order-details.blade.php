@push('css')
    <link href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/responsive/2.3.0/css/responsive.dataTables.min.css" rel="stylesheet">
@endpush
@extends('layouts.logged_in_master')
@section('content')
    <div class="col-md-8">
        <div class="my-acc-right">
            <h4>{{ $title ? $title : '' }}</h4>
            <div class="order-details">
                <div class="row">
                    <div class="col-md-8">
                        <div class="card w-100">
                            <div class="card-header">
                                <h5>Order #{{ @$details->order_alt_id }}</h5>
                            </div>

                            <div class="card-body">
                                <table class="details-t">
                                    <tr>
                                        <th>Show Image</th>
                                        <th>Show Name</th>
                                        <th>Unit Price</th>
                                        {{--  <th>Discount</th>  --}}
                                        <th>Purchase Type</th>
                                        <th>Qunatity</th>
                                    </tr>
                                    @forelse ($details->items as $value)
                                        <tr>
                                            <td width="20">
                                                @if (@$value->product_type == 1)
                                                    <img src="{{ env('IMAGE_URL') }}uploads/categories/{{ @$value->show?->category?->slug }}/{{ @$value->show->image }}"
                                                        width="30">
                                                @else
                                                    <img src="{{ env('IMAGE_URL') }}uploads/sample-files/{{ @$value->sample?->image }}"
                                                        width="30">
                                                @endif
                                            </td>
                                            <td>
                                                @if (@$value->product_type == 1)
                                                    <h4>{{ @$value->show->title }}</h4>
                                                @else
                                                    <h4>{{ @$value->sample->title }}(Sample)</h4>
                                                @endif

                                            </td>
                                            <td>
                                                ${{ @$value->item_amount }}
                                            </td>
                                            {{--  <td>
                                                ${{ $value->discount_amount }}
                                            </td>  --}}
                                            <td>
                                                @if (@$value->type == 1)
                                                <span class="badge badge-primary">Instant Download</span>
                                                   
                                                @elseif(@$value->type == 2)
                                                   <span class="badge badge-secondary">CD</span>
                                                @else
                                                <span class="badge badge-warning">Sample</span>
                                                @endif

                                                {{--  {!! @$value->type == 1
                                                    ? '<span class="badge badge-primary">Instant Download</span>'
                                                    : '<span class="badge badge-secondary">CD</span>' !!}  --}}
                                            </td>
                                            <td>
                                                @if (@$value->product_type == 1)
                                                    {{ @$value->quantity }}
                                                @else
                                                    {{ '1' }}
                                                @endif
                                            </td>
                                            {{--  <td>
                                                $ {{ @$value->quantity * @$value->item_amount }}
                                            </td>  --}}
                                        </tr>
                                    @empty
                                    @endforelse


                                </table>
                            </div>
                        </div>
                        <div class="card mb-4 mt-3">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <h3 class="h6">Payment Method</h3>
                                        <p>{{ @$details->transaction->payment_method }} <br>
                                            Shipping Charge: ${{ @$details->shipping_cost }}<br>
                                            Total Discount: ${{ @$details->discount_amount }}<br>
                                            Total: ${{ @$details->oder_amount }} <span
                                                class="badge {{ @$details->payment_status === 'P' ? 'bg-success' : 'bg-danger' }}  rounded-pill">{{ @$details->payment_status === 'P' ? 'PAID' : 'UNPAID' }}</span>

                                        </p>
                                    </div>
                                    <div class="col-lg-6">
                                        <h3 class="h6">Billing address</h3>
                                        <address>
                                            <strong>{{ @$details->address->where('type','B')->first()->first_name . ' ' . @$details->address->where('type','B')->first()->last_name }}</strong><br>
                                            {{ @$details->address->where('type','B')->first()->street_address }}<br>{{ @$details->address->where('type','B')->first()->address_line_2 }}<br>{{ @$details->address->where('type','B')->first()->city }},
                                            {{ @$details->address->where('type','B')->first()->state->name }}, {{ @$details->address->where('type','B')->first()->country->name }},
                                            {{ @$details->address->where('type','B')->first()->zip_code }} <br><abbr title="Phone">Phone:</abbr>
                                            {{ @$details->address->where('type','B')->first()->phone }}
                                        </address>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card mb-4">
                            <div class="card-body">
                                <h3 class="h6">Shipping Information</h3>

                                <hr>
                                <h3 class="h6">Address</h3>
                                <address>
                                    <strong>{{ @$details->address->where('type','S')->first()->first_name . ' ' . @$details->address->where('type','S')->first()->last_name }}</strong><br>
                                    {{ @$details->address->where('type','S')->first()->street_address }}<br>{{ @$details->address->where('type','S')->first()->address_line_2 }}<br>{{ @$details->address->where('type','S')->first()->city }},
                                    {{ @$details->address->where('type','S')->first()->state->name }}, {{ @$details->address->where('type','S')->first()->country->name }},
                                    {{ @$details->address->where('type','S')->first()->zip_code }} <br><abbr title="Phone">Phone:</abbr>
                                    {{ @$details->address->where('type','S')->first()->phone }}
</li
                                </address>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@stop
@push('scripts')
@endpush
