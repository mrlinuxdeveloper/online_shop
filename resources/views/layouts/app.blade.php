<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name', 'Companymod'))</title>

    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    <style>
        :root {
            --shop-green: #2E5E4E;
            --shop-green-dark: #23483d;
            --shop-brick: #B24C3C;
            --shop-paper: #FAF7F1;
            --shop-text: #252525;
            --shop-muted: #777;
            --shop-border: #e8e3da;
        }

        body {
            margin: 0;
            background: var(--shop-paper);
            color: var(--shop-text);
            font-family: Nunito, sans-serif;
        }

        a {
            text-decoration: none;
            color: inherit;
        }

        .shop-topbar {
            background: var(--shop-green-dark);
            color: #fff;
            font-size: 14px;
        }

        .shop-header {
            background: #fff;
            border-bottom: 1px solid var(--shop-border);
        }

        .shop-brand {
            font-size: 25px;
            font-weight: 800;
            color: var(--shop-green);
        }

        .shop-brand:hover {
            color: var(--shop-brick);
        }

        .shop-search {
            position: relative;
            max-width: 550px;
            width: 100%;
        }

        .shop-search input {
            width: 100%;
            border: 1px solid var(--shop-border);
            border-radius: 12px;
            padding: 11px 45px 11px 15px;
            background: var(--shop-paper);
            outline: none;
        }

        .shop-search input:focus {
            border-color: var(--shop-green);
            box-shadow: 0 0 0 3px rgba(46,94,78,.08);
        }

        .shop-search button {
            position: absolute;
            right: 8px;
            top: 50%;
            transform: translateY(-50%);
            border: 0;
            background: transparent;
            color: var(--shop-green);
            font-size: 18px;
        }

        .shop-action {
            display: flex;
            align-items: center;
            gap: 8px;
            color: var(--shop-text);
            font-size: 14px;
            white-space: nowrap;
        }

        .shop-action:hover {
            color: var(--shop-green);
        }

        .shop-action-icon {
            font-size: 20px;
        }

        .shop-navbar {
            background: var(--shop-green);
            color: #fff;
        }

        .shop-navbar .nav-link {
            color: #fff;
            padding: 12px 16px;
            font-size: 14px;
        }

        .shop-navbar .nav-link:hover {
            background: var(--shop-green-dark);
            color: #fff;
        }

        .shop-main {
            min-height: 65vh;
        }

        .shop-footer {
            margin-top: 60px;
            background: #1f332d;
            color: #ddd;
        }

        .shop-footer h5 {
            color: #fff;
            font-weight: 700;
            margin-bottom: 18px;
        }

        .shop-footer a {
            color: #ccc;
            display: block;
            margin-bottom: 9px;
            font-size: 14px;
        }

        .shop-footer a:hover {
            color: #fff;
        }

        .shop-footer-bottom {
            border-top: 1px solid rgba(255,255,255,.1);
            padding: 18px 0;
            color: #aaa;
            font-size: 13px;
        }

        @media (max-width: 991px) {
            .shop-search {
                max-width: 100%;
            }
        }
    </style>
</head>

<body>
<div id="app">

    {{-- Top Bar --}}
    <div class="shop-topbar">
        <div class="container py-2 text-center">
            ارسال سفارش‌ها با بسته‌بندی مناسب
        </div>
    </div>

    {{-- Header --}}
    <header class="shop-header">
        <div class="container py-3">
            <div class="row align-items-center g-3">

                {{-- Logo --}}
                <div class="col-lg-2 col-md-3">
                    <a href="{{ url('/') }}" class="shop-brand">
                        {{ config('app.name', 'Companymod') }}
                    </a>
                </div>

                {{-- Search --}}
                <div class="col-lg-6 col-md-5">
                    <form action="{{ route('shop.products.index') }}" method="GET" class="shop-search">
                        <input
                            type="text"
                            name="search"
                            value="{{ request('search') }}"
                            placeholder="جستجوی محصول..."
                        >
                        <button type="submit">🔍</button>
                    </form>
                </div>

                {{-- Actions --}}
                <div class="col-lg-4 col-md-4">
                    <div class="d-flex justify-content-end align-items-center gap-4">

                        @auth
                            <a href="{{ route('wishlist.index') }}" class="shop-action">
                                <span class="shop-action-icon">♡</span>
                                <span>علاقه‌مندی‌ها</span>
                            </a>

                            <a href="{{ route('orders.index') }}" class="shop-action">
                                <span class="shop-action-icon">📦</span>
                                <span>سفارش‌ها</span>
                            </a>
                        @endauth

                        @guest
                            @if (Route::has('login'))
                                <a href="{{ route('login') }}" class="shop-action">
                                    <span class="shop-action-icon">👤</span>
                                    <span>ورود</span>
                                </a>
                            @endif
                        @else
                            <div class="dropdown">
                                <a
                                    href="#"
                                    class="shop-action dropdown-toggle"
                                    data-bs-toggle="dropdown"
                                    aria-expanded="false"
                                >
                                    <span class="shop-action-icon">👤</span>
                                    <span>{{ Auth::user()->name }}</span>
                                </a>

                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                        <a class="dropdown-item" href="{{ route('dashboard') }}">
                                            حساب کاربری
                                        </a>
                                    </li>

                                    <li>
                                        <a class="dropdown-item" href="{{ route('orders.index') }}">
                                            سفارش‌های من
                                        </a>
                                    </li>

                                    <li><hr class="dropdown-divider"></li>

                                    <li>
                                        <a
                                            class="dropdown-item"
                                            href="{{ route('logout') }}"
                                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                                        >
                                            خروج
                                        </a>
                                    </li>
                                </ul>
                            </div>

                            <form
                                id="logout-form"
                                action="{{ route('logout') }}"
                                method="POST"
                                class="d-none"
                            >
                                @csrf
                            </form>
                        @endguest

                    </div>
                </div>

            </div>
        </div>
    </header>

    {{-- Navigation --}}
    <nav class="shop-navbar">
        <div class="container">
            <div class="d-flex align-items-center">

                <a href="{{ url('/') }}" class="nav-link">
                    خانه
                </a>

                <a href="{{ route('shop.products.index') }}" class="nav-link">
                    فروشگاه
                </a>

                @foreach($categories ?? [] as $category)
                    <a
                        href="{{ route('shop.products.index', ['category' => $category->id]) }}"
                        class="nav-link"
                    >
                        {{ $category->name }}
                    </a>
                @endforeach

            </div>
        </div>
    </nav>

    {{-- Main Content --}}
    <main class="shop-main">
        @if(session('success'))
            <div class="container mt-3">
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="container mt-3">
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            </div>
        @endif

        @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="shop-footer">
        <div class="container py-5">
            <div class="row g-4">

                <div class="col-md-4">
                    <h5>{{ config('app.name', 'Companymod') }}</h5>
                    <p class="small">
                        فروشگاه آنلاین پوشاک با امکان مشاهده محصولات،
                        ثبت سفارش و پرداخت آنلاین.
                    </p>
                </div>

                <div class="col-md-4">
                    <h5>دسترسی سریع</h5>

                    <a href="{{ url('/') }}">خانه</a>
                    <a href="{{ route('shop.products.index') }}">فروشگاه</a>

                    @auth
                        <a href="{{ route('orders.index') }}">سفارش‌های من</a>
                        <a href="{{ route('wishlist.index') }}">علاقه‌مندی‌ها</a>
                    @endauth
                </div>

                <div class="col-md-4">
                    <h5>حساب کاربری</h5>

                    @guest
                        <a href="{{ route('login') }}">ورود</a>

                        @if(Route::has('register'))
                            <a href="{{ route('register') }}">ثبت‌نام</a>
                        @endif
                    @else
                        <a href="{{ route('dashboard') }}">حساب کاربری</a>
                        <a href="{{ route('orders.index') }}">سفارش‌های من</a>
                    @endguest
                </div>

            </div>
        </div>

        <div class="shop-footer-bottom">
            <div class="container text-center">
                © {{ date('Y') }}
                {{ config('app.name', 'Companymod') }}
                - تمامی حقوق محفوظ است.
            </div>
        </div>
    </footer>

</div>
</body>
</html>
