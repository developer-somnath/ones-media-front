<link href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/responsive/2.3.0/css/responsive.dataTables.min.css" rel="stylesheet">
@extends('layouts.master')
@section('content')
    @php
        $cartsCount = auth()->user() ? auth()->user()->carts : session()->get('cart', []);
    @endphp
    <div class="container">
        <div class="bredcrames-sec">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $title ? $title : '' }}
                        ({{ count($cartsCount) }})
                    </li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="main-part-home">
        <div class="container">
            <div>
                <div class="page-title">
                    <h2>Cart</h2>
                    @if (!count($cartsCount) > 0)
                        <h6>Your Cart is Empty</h6>
                        <a href="{{ url('/') }}" class="btn btn-warning"><i class="fa fa-angle-left"></i>
                            Continue Shopping</a>
                    @endif
                </div>
                @if (count($cartsCount) > 0)
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
                                    //dd($shippingPrice);
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
                                            elseif ($details->type == 2):
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
                                                    @if ($details->product_type == 1)
                                                        <a href="{{ url('show/details/' . $details->item_id) }}">
                                                            <h3>{{ $show_name }}</h3>
                                                        </a>
                                                    @else
                                                        <a href="javascript:void(0)">
                                                            <h3>{{ $show_name }}</h3>
                                                        </a>
                                                    @endif
                                                    <form action="" method="" id="customCartForm"
                                                        style="{{ $details->product_type == 1 ? 'display:show' : 'display:none' }}">
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
                                                @if ($details->product_type == 1)
                                                    <form class="userFrm" data-action="update-my-cart-quantity" method="post"
                                                        data-validation="requiredCheck">
                                                        @csrf
                                                        <input type="hidden" name="item_id" value="{{ $details->item_id }}">
                                                        <input type="number" placeholder="Enter Quantity" name="quantity"
                                                            value="{{  $details->quantity  }}"
                                                            onkeypress="return (event.charCode !=8 && event.charCode ==0 || (event.charCode >= 48 && event.charCode <= 57))"
                                                            class="requiredCheck" data-check="Quantity" min="1" max="5">
                                                        <button class="btn btn-warning btn-sm" type="submit">Update</button>

                                                    </form>
                                                @else
                                                    {{  1  }}
                                                @endif
                                                {{--  <div class="list-p-title">
                                                    
                                                </div>  --}}
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
                                                    @if ($details->product_type == 1)
                                                        <button type="button" class="removeFromCart"
                                                            data-id="{{ $details->item_id }}"
                                                            data-ptype="{{ $details->product_type }}">Remove</button>
                                                    @else
                                                        <button type="button" class="removeFromCart"
                                                            data-id="{{ $details->item_id }}"
                                                            data-ptype="{{ $details->product_type }}">Remove</button>
                                                    @endif
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
                                        {{--  <p>{{ $shippingPrice->price_for_single_qty }}</p>  --}}
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
                                <tr>
                                    <td colspan="4" class="text-right">
                                        <a href="{{ url('/') }}" class="btn btn-warning"><i
                                                class="fa fa-angle-left"></i>
                                            Continue Shopping</a>
                                        @auth
                                            <a href="{{ url('/sample-file') }}" class="btn btn-success">
                                                Checkout</a>
                                        @endauth
                                        @guest
                                            <a href="{{ url('/login') }}" class="btn btn-success">Checkout</a>
                                        @endguest
                                    </td>
                                </tr>
                            </tfoot>
                        </table>

                    </div>
                @else
                    <div class="home-pro-list" style="display: none;">
                        <table class="w-100" id="showallDatatable">
                            <thead>
                                <th>Name</th>
                                <th>Quantity</th>
                                <th>Price</th>
                                <th>Subtotal</th>
                            </thead>
                            <tbody>
                                {{--   @forelse ($productList as $product)  --}}
                                @php $total = 0 @endphp
                                @if ($carts)
                                    @foreach ($carts as $cart => $details)
                                        @php
                                            //dd($details);
                                            if ($details->type == 1):
                                                //$price = $details->instant_download_price;
                                                $show_name = $details->title . '(' . 'Instant Download' . ')';
                                                //$price = $details->mp3_cd_price;
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
                                        <a href="{{ url('/') }}" class="btn btn-warning"><i
                                                class="fa fa-angle-left"></i>
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
                        </table>

                    </div>
                @endif
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
