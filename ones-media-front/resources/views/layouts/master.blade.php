<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="icon" href="{{ asset('assets/img/fav-icon.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css"
        integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- Bootstrap core CSS -->
    <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/owl.carousel.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/animate.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/bootstrap-theme.min.css') }}">
    <link rel="stylesheet" type="text/css" media="all" href="{{ asset('assets/css/stellarnav.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/theme.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/responsive.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">
    <title>Onesmedia | {{ $title ? $title : '' }}</title>
    @stack('css')
    <script>
        let baseUrl = "{{ url('') }}/"
        let _token = "{{ csrf_token() }}"
    </script>
</head>

<body>
    @php
        $categories = DB::table('categories')
            ->where('status', '<>', 1)
            ->get();
        
        $yearlist = DB::table('shows')
            ->where('status', '<>', 1)
            ->orderByRaw('DATE(`show_start_year`) ASC')
            ->first();
        $dates = range($yearlist->show_start_year, date('Y'));
        $cartsCount = auth()->user() ? auth()->user()->carts : session()->get('cart', []);
    @endphp
    <header>
        <div class="main-header">
            <div class="container">
                <div class="logo-header">
                    <div class="row align-items-center">
                        <div class="col-md-4 col-lg-4 col-6">
                            <div class="logo">
                                <a href="#"><img id="logo" src="{{ asset('assets/img/logo.png') }}"></a>
                            </div>
                        </div>
                        <div class="col-md-8 col-lg-8 col-6">
                            <ul>
                                <li>
                                    @auth
                                        <a href="{{ url('/my-cart') }}">
                                            <div class="cart-header">
                                                <span><i class="fa-solid fa-cart-shopping"></i></span>
                                                <div>
                                                    <h4>Your Cart</h4>
                                                    <h5>{{ count($cartsCount) }} items</h5>
                                                </div>
                                            </div>
                                        </a>
                                    @endauth
                                    @guest
                                        <a href="{{ url('/cart') }}">
                                            <div class="cart-header">
                                                <span><i class="fa-solid fa-cart-shopping"></i></span>
                                                <div>
                                                    <h4>Your Cart</h4>
                                                    <h5>{{ count($cartsCount) }} items</h5>
                                                </div>
                                            </div>
                                        </a>
                                    @endguest

                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="nav-header">
                    <div class="stellarnav">
                        <ul>
                            <li><a href="{{ url('/') }}"
                                    class="@if (Request::segment(1) == '') active @endif">Home </a></li>
                            <li class="mega" data-columns="3"><a href="{{ url('/shows-all') }}"
                                    class="@if (Request::segment(1) == 'shows-all') active @endif">All Shows</a>
                                <ul>
                                    <li><a href="{{ url('/shows-all') }}"
                                            class="@if (Request::segment(1) == 'shows-all') active @endif">All Show</a></li>
                                    @forelse ($categories as $category)
                                        <li>
                                            <a
                                                href="{{ app()->router->has('show.by.category') ? route('show.by.category', $category->slug) : 'javascript:void(0)' }}">{{ $category->name }}</a>
                                        </li>
                                    @empty
                                    @endforelse
                                    {{--  <li><a href="#">Advanture</a></li>
                                    <li><a href="#">Children</a></li>
                                    <li><a href="#">Comedy</a></li>  --}}
                                </ul>
                            </li>
                            <li><a href="{{ url('/new-shows') }}"
                                    class="@if (Request::segment(1) == 'new-shows') active @endif">New Shows </a></li>
                            <li class="mega" data-columns="4"><a href="#">Shows by year </a>
                                <ul>
                                    @forelse ($dates as $d)
                                        <li>
                                            <a
                                                href="{{ app()->router->has('show.by.Year') ? route('show.by.Year', $d) : 'javascript:void(0)' }}">{{ $d }}</a>
                                        </li>
                                    @empty
                                    @endforelse
                                    {{--  <li><a href="#">1920</a></li>
                                    <li><a href="#">1947</a></li>
                                    <li><a href="#">2020</a></li>
                                    <li><a href="#">2021</a></li>
                                    <li><a href="#">2000</a></li>
                                    <li><a href="#">2010</a></li>
                                    <li><a href="#">2011</a></li>  --}}
                                </ul>
                            </li>
                            <li><a href="#">faq </a></li>
                            <li><a href="">Know More</a></li>
                            <li><a href="">onesmedia.com</a></li>
                            <li>
                                @auth
                                    <a href="{{ url('my-account') }}"
                                        class="@if (Request::segment(1) == 'my-account') active @endif"><i
                                            class="far fa-user-circle"></i> My Account</a>
                                @endauth
                                @guest
                                    <a href="{{ url('login') }}"
                                        class="@if (Request::segment(1) == 'login') active @endif"><i
                                            class="far fa-user-circle"></i> Login</a>
                                @endguest
                            </li>
                        </ul>
                    </div>
                </div>
                @if (
                    !in_array(request()->route()->getName(),
                        ['login', 'cart', 'my-cart', 'checkout', 'order-history', 'sample-file']))
                    <div class="search-header">
                        <form action="{{ url('search') }}" method="GET">
                            <input type="text" class="form-control" name="q" value="{{ !empty($_REQUEST['q']) ? $_REQUEST['q'] : '' }}"
                                placeholder="Enter any type of word here to find">
                            <button type="submit"><i class="fa-solid fa-magnifying-glass"></i></button>
                        </form>
                    </div>
                @endif
            </div>
        </div>
    </header>
    {{--  @endif  --}}
    @yield('content')
    <footer>
        <div class="main-footer">
            <div class="container">
                <div class="row">
                    <div class="col-lg-4 col-md-6">
                        <div class="footer-widget">
                            <h3>Importants Links</h3>
                            <ul class="links-list">
                                <li>
                                    <a href="#">Shipping Policy</a>
                                </li>
                                <li>
                                    <a href="#">Return Policy</a>
                                </li>
                                <li>
                                    <a href="#">Privacy Policy</a>
                                </li>
                                <li>
                                    <a href="#">Payment Methods</a>
                                </li>
                                <li>
                                    <a href="#">Rules</a>
                                </li>
                                <li>
                                    <a href="#">Help Centre</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6">
                        <div class="footer-widget">
                            <h3>Online Store</h3>
                            <ul class="links-list">
                                <li>
                                    <a href="{{ url('/') }}">Home</a>
                                </li>
                                <li>
                                    <a href="#">About Us</a>
                                </li>
                                <li>
                                    <a href="{{ url('/shows-all') }}">Shop</a>
                                </li>
                                <li>
                                    <a href="#">FAQ</a>
                                </li>
                                <li>
                                    <a href="#">Contact Us</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6">
                        <div class="footer-widget">
                            <h3>CUSTOMER SERVICES</h3>
                            <ul class="links-list">
                                <li>
                                    <a href="#">DIGITAL DOWNLOAD</a>
                                </li>
                                <li>
                                    <a href="#">SUBSCRIBE NEWSLETTER</a>
                                </li>
                                <li>
                                    <a href="#">FREE SAMPLER</a>
                                </li>
                                <li>
                                    <a href="#">Featured</a>
                                </li>
                                <li>
                                    <a href="#">Shopping Cart</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="copy-right">
                © 2022 Business name, ALL RIGHTS RESERVED
            </div>
        </div>
    </footer>
    <!-- Bootstrap core JavaScript -->
    <!--<script src="https://code.jquery.com/jquery-3.4.1.js"></script>-->
    <script src="{{ asset('assets/js/jquery.1.11.3.min.js') }}"></script>
    <script src="{{ asset('assets/js/wow.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/js/owl.carousel.js') }}"></script>
    <script src="http://cdnjs.cloudflare.com/ajax/libs/waypoints/2.0.3/waypoints.min.js"></script>
    <script src="{{ asset('assets/js/jquery.counterup.js') }}"></script>
    <script src="{{ asset('assets/js/theme.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/js/stellarnav.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>
    <script src="{{ asset('assets/js/front-common.js') }}"></script>
    @stack('scripts')
</body>

</html>
