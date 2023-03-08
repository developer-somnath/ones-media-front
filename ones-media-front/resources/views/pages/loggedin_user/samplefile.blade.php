@push('css')
<link href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/responsive/2.3.0/css/responsive.dataTables.min.css" rel="stylesheet">
@endpush
@extends('layouts.master')
@section('content')

    <div class="main-part-home">
        <div class="container">
            <div>
                <div class="page-title">
                    <h2>Please Choose A Sample File</h2>

                </div>
                <div class="row">
                    @forelse ($sampleFiles as $file)
                        <div class="col-md-3">
                            <div class="sample-file">
                                <a href="javascript:void(0)" class="addToCartSampleFile"  data-id="{{ $file->id }}">
                                    
                                        <img src="{{ env('IMAGE_URL') }}uploads/sample-files/{{ $file->image }}">
                                   
                                    <h3>{{ $file->title }}</h3>
                                    <h2>ADD THIS FREEBIE</h2>
                                </a>
                                
                            </div>
                        </div>
                    @empty
                        <div class="col-md-12">
                            <div class="popular-box">
                                <a href="product-details.html">

                                    <h3>No Data Found !!</h3>
                                </a>
                            </div>
                        </div>
                    @endforelse
                </div><br>
                <div class="text-right">
                    <a href="{{ url('/checkout') }}" class="btn btn-success">
                                                No Thanks. Proceed to Checkout</a>
                </div>
                

                {{--  <table class="w-100" id="showallDatatable">
                        <thead>
                            <th>Tiltle</th>
                            <th>Audio File</th>
                            <th>Description</th>
                            <th>Created At</th>
                        </thead>
                        <tbody>

                            @php $total = 0 @endphp
                            @if ($carts)
                                @foreach ($carts as $cart => $details)
                                    @php
                                        if ($details->type == 1):
                                            $show_name = $details->title . '(' . 'Instant Download' . ')';
                                        else:
                                            $show_name = $details->title . '(' . $details->no_of_mp3_cds . ' ' . 'Mp3 Cd' . ')';
                                        endif;
                                        $total += number_format($details->price, 2) * $details->quantity;
                                    @endphp
                                    <tr>
                                        <td>
                                            <div class="list-p-title">
                                                <a href="{{ url('show/details/' . $details->item_id) }}">
                                                    <h3>{{ $show_name }}</h3>
                                                </a>
                                                <form action="" method="" id="customCartForm">
                                                    @csrf
                                                    <select class="type"
                                                        onchange=getPrice({{ $details->item_id }},this.value)>
                                                        <option data-id="{{ $details->item_id }}" value="1"
                                                            @if ($details->type == 1) selected @endif>
                                                            Instant Download
                                                        </option>
                                                        <option data-id="{{ $details->item_id }}" value="2"
                                                            @if ($details->type == 2) selected @endif>
                                                            Mp3 Cd
                                                        </option>

                                                    </select>
                                                </form>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="list-p-title">
                                                <input type="number" id="quantity" name="quantity"
                                                    class="form-control input-number text-center"
                                                    value="{{ $details->quantity }}" min="1" max="100">
                                            </div>
                                        </td>

                                        <td>
                                            ${{ number_format($details->price, 2) }}<br>
                                            <b>Discount</b> : <span>${{ $details->discount }}</span>
                                        </td>

                                        <td>
                                            <div class="list-p-btn">
                                                <span>${{ number_format($details->price, 2) * $details->quantity }}</span>


                                                <button type="button" class="removeFromCart"
                                                    data-id="{{ $details->id }}">Remove</button>
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
                                    <h4><strong>Total ${{ $total }}</strong></h4>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="4" class="text-right">
                                    <a href="{{ url('/') }}" class="btn btn-warning"><i class="fa fa-angle-left"></i>
                                        Continue Shopping</a>
                                    @auth
                                        <a href="{{ url('/checkout') }}" class="btn btn-success">
                                            Checkout</a>
                                    @endauth
                                    @guest
                                        <a href="{{ url('/login') }}" class="btn btn-success">Checkout</a>
                                    @endguest
                                </td>
                            </tr>
                        </tfoot>
                    </table>  --}}




            </div>

        </div>

    @stop
    @push('scripts')
        <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/responsive/2.3.0/js/dataTables.responsive.min.js"></script>

        <script>
            function getPrice(item_id, type) {
                console.log(item_id + '--' + type)
                var formData = new FormData($("form#customCartForm")[0]);
                formData.append('item_id', item_id);
                formData.append('type', type);
                $.ajax({
                    method: 'post',
                    url: baseUrl + 'filter-by-my-cart',
                    processData: false,
                    contentType: false,
                    data: formData,
                    success: function(res) {
                        console.log(res);
                        location.reload();

                    }
                });
            }
            $(document).ready(function() {
                {{--  $('.type').on('change', function() {
                var type = $(this).val();
                console.log(type);
                var item_id = $(this).find(':selected').attr('data-id');
                console.log(item_id);
                var formData = new FormData($("form#customForm")[0]);
                //var guestbookSendMessage = new FormData();
                //var files = $('#noteFormFile')[0].note_file;
                //formData.append('type', type);
                formData.append('item_id', item_id);
                $.ajax({
                    method: 'post',
                    url: baseUrl + 'filter-by-cart',
                    processData: false,
                    contentType: false,
                    data: formData,
                    success: function(res) {
                        console.log(res);
                        location.reload();
                        
                    }
                });
            });  --}}
            })
        </script>
    @endpush
