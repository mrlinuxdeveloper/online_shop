<!doctype html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name', 'فروشگاه آنلاین'))</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Vazirmatn:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    <style>
        :root{--ink:#191919;--muted:#777;--line:#e9e9e9;--soft:#f7f7f7;--wine:#75172c;--wine-dark:#5d1021;--sale:#d7193f}
        *{box-sizing:border-box} body{margin:0;background:#fff;color:var(--ink);font-family:'Vazirmatn',Tahoma,sans-serif} a{color:inherit;text-decoration:none}
        .container{max-width:1320px}.shop-promo{min-height:42px;background:var(--wine);color:white;display:flex;align-items:center;justify-content:center;text-align:center;padding:8px 16px;font-size:13px;font-weight:600;letter-spacing:-.1px}
        .shop-header{background:#fff;border-bottom:1px solid var(--line)}.shop-head-main{min-height:80px;display:grid;grid-template-columns:auto minmax(220px,1fr) auto;align-items:center;gap:32px}
        .shop-brand{font-size:27px;line-height:1;font-weight:800;letter-spacing:-1.5px;white-space:nowrap}.shop-brand span{font-weight:400}
        .shop-search{position:relative;width:100%;max-width:560px}.shop-search input{width:100%;height:44px;border:0;border-radius:24px;background:#f4f4f4;padding:0 46px 0 18px;font:inherit;font-size:13px;outline:0}.shop-search input:focus{box-shadow:0 0 0 2px #ddd}.shop-search button{position:absolute;right:14px;top:50%;transform:translateY(-50%);border:0;background:none;font-size:17px;color:#666}
        .shop-actions{display:flex;align-items:center;justify-content:flex-end;gap:20px}.shop-action{display:flex;align-items:center;gap:7px;white-space:nowrap;font-size:12px}.shop-action:hover{color:var(--shop-primary,var(--wine))}.shop-action-icon{width:26px;height:26px;flex:0 0 26px;display:inline-grid;place-items:center;font-size:20px;line-height:1}.shop-login{border:1px solid #dedede;padding:10px 13px;border-radius:5px}
        .shop-navbar{background:#fff}.shop-navbar .nav-links{display:flex;align-items:stretch;gap:0;min-height:48px;overflow-x:auto;scrollbar-width:none}.shop-navbar .nav-links::-webkit-scrollbar{display:none}.shop-navbar .nav-link{display:flex;align-items:center;flex:0 0 auto;padding:0 16px;font-size:12px;color:#444;border-bottom:2px solid transparent;white-space:nowrap}.shop-navbar .nav-link:hover,.shop-navbar .nav-link.active{color:var(--ink);border-bottom-color:var(--ink);font-weight:700}
        .shop-main{min-height:55vh}.shop-footer{margin-top:72px;background:linear-gradient(135deg,#25141a 0%,#401523 58%,#57182b 100%);color:#fff}.shop-footer-top{padding:54px 0 42px}.shop-footer h5{font-weight:800;margin:0 0 17px;font-size:15px;color:#fff}.shop-footer-brand{font-size:23px;font-weight:800;letter-spacing:-.4px;margin-bottom:12px}.shop-footer-description{max-width:360px;color:#e1d6da;font-size:13px;line-height:2}.shop-footer a{display:block;width:max-content;margin:0 0 11px;color:#e1d6da;font-size:13px;transition:color .2s,transform .2s}.shop-footer a:hover{color:#fff;transform:translateX(-3px)}.shop-footer-socials{display:flex;gap:10px;margin-top:20px}.shop-footer .shop-social-link{width:42px;height:42px;display:grid;place-items:center;margin:0;border:1px solid #ffffff45;border-radius:50%;color:#fff;transition:background .2s,border-color .2s,transform .2s}.shop-footer .shop-social-link:hover{background:#ffffff20;border-color:#fff;transform:translateY(-3px)}.shop-social-link svg{width:19px;height:19px;fill:currentColor}.shop-footer-benefits{display:flex;flex-wrap:wrap;gap:9px 22px;margin-top:22px;color:#e1d6da;font-size:11px}.shop-footer-benefits span{display:flex;align-items:center;gap:7px}.shop-footer-bottom{border-top:1px solid #ffffff24;padding:17px 0;color:#d5c7cc;font-size:12px}
        .alert{border-radius:6px;font-size:13px}
        @media(max-width:767px){.shop-promo{font-size:11px;min-height:36px}.shop-head-main{grid-template-columns:1fr auto;gap:12px;padding-top:13px!important;padding-bottom:13px!important}.shop-brand{font-size:23px}.shop-search{grid-column:1/-1;grid-row:2;max-width:none}.shop-actions{gap:13px}.shop-action{font-size:0}.shop-action-icon{width:28px;height:28px;flex-basis:28px;font-size:21px}.shop-login{padding:8px}.shop-navbar .nav-link{padding:0 12px;font-size:11px}.shop-footer{margin-top:48px}.shop-footer-top{padding:38px 0 30px}}
    </style>
</head>
<body>
@php
    $siteAppearance = \App\Models\SiteSetting::getValue('appearance', []);
    $sitePrimaryColor = $siteAppearance['primary_color'] ?? '#75172c';
    $sitePrimaryColor = preg_match('/^#[0-9A-Fa-f]{6}$/', $sitePrimaryColor) ? $sitePrimaryColor : '#75172c';
    $siteAccentColor = $siteAppearance['accent_color'] ?? '#d7193f';
    $siteAccentColor = preg_match('/^#[0-9A-Fa-f]{6}$/', $siteAccentColor) ? $siteAccentColor : '#d7193f';
@endphp
<style>:root{--shop-primary:{{ $sitePrimaryColor }};--shop-accent:{{ $siteAccentColor }};}</style>
<div id="app">
    <div class="shop-promo" style="background:{{ $sitePrimaryColor }}">{{ $siteAppearance['promo_text'] ?? 'تخفیف‌های تازه و انتخاب‌های جذاب؛ استایل بعدی‌تان را پیدا کنید' }}</div>
    <header class="shop-header">
        <div class="container shop-head-main py-3">
            <a href="{{ url('/') }}" class="shop-brand" aria-label="صفحه اصلی">@if(!empty($siteAppearance['logo_image']))<img src="{{ asset('storage/' . $siteAppearance['logo_image']) }}" alt="{{ $siteAppearance['logo_text'] ?? config('app.name', 'فروشگاه') }}" style="max-width:170px;max-height:48px;object-fit:contain">@else{{ $siteAppearance['logo_text'] ?? config('app.name', 'فروشگاه') }}@endif</a>
            <form action="{{ route('shop.products.index') }}" method="GET" class="shop-search">
                <input type="search" name="search" value="{{ request('search') }}" placeholder="جستجوی محصول، دسته‌بندی یا برند" aria-label="جستجو">
                <button type="submit" aria-label="جستجو">⌕</button>
            </form>
            <div class="shop-actions">
                @guest
                    @if(Route::has('login'))<a href="{{ route('login') }}" class="shop-action shop-login"><span class="shop-action-icon">♙</span><span>ورود | ثبت‌نام</span></a>@endif
                @else
                    <a href="{{ route('dashboard') }}" class="shop-action"><span class="shop-action-icon">♙</span><span>حساب کاربری</span></a>
                @endguest
                @auth
                    <a href="{{ route('wishlist.index') }}" class="shop-action" aria-label="علاقه‌مندی‌ها"><span class="shop-action-icon">♡</span><span>علاقه‌مندی‌ها</span></a>
                    <a href="{{ route('cart.index') }}" class="shop-action" aria-label="سبد خرید"><span class="shop-action-icon">♧</span><span>سبد خرید</span></a>
                @endauth
            </div>
        </div>
        <nav class="shop-navbar" aria-label="دسته‌بندی محصولات"><div class="container nav-links">
            <a href="{{ url('/') }}" class="nav-link {{ request()->is('/') ? 'active' : '' }}">همه استایل‌ها</a>
            <a href="{{ route('shop.products.index') }}" class="nav-link">تازه‌ها</a>
            @foreach($categories ?? [] as $category)
                <a href="{{ route('shop.products.index', ['category' => $category->id]) }}" class="nav-link">{{ $category->name }}</a>
            @endforeach
            <a href="{{ route('shop.products.index', ['sort' => 'price_low']) }}" class="nav-link">پیشنهادهای اقتصادی</a>
        </div></nav>
    </header>
    <main class="shop-main">
        @if(session('success'))<div class="container mt-3"><div class="alert alert-success">{{ session('success') }}</div></div>@endif
        @if(session('error'))<div class="container mt-3"><div class="alert alert-danger">{{ session('error') }}</div></div>@endif
        @yield('content')
    </main>
    <footer class="shop-footer">
        <div class="container shop-footer-top"><div class="row g-4 g-lg-5">
            <div class="col-lg-5 col-md-6">
                <div class="shop-footer-brand">{{ $siteAppearance['logo_text'] ?? config('app.name', 'فروشگاه آنلاین') }}</div>
                <p class="shop-footer-description mb-0">{{ $siteAppearance['footer_tagline'] ?? 'برای هر روز، برای هر سلیقه؛ انتخابی تازه برای استایل شما.' }}</p>
                <div class="shop-footer-socials" aria-label="شبکه‌های اجتماعی">
                    @if(!empty($siteAppearance['instagram_url']))
                        <a class="shop-social-link" href="{{ $siteAppearance['instagram_url'] }}" target="_blank" rel="noopener noreferrer" aria-label="Instagram" title="Instagram"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M7 2h10a5 5 0 0 1 5 5v10a5 5 0 0 1-5 5H7a5 5 0 0 1-5-5V7a5 5 0 0 1 5-5zm0 2.2A2.8 2.8 0 0 0 4.2 7v10A2.8 2.8 0 0 0 7 19.8h10a2.8 2.8 0 0 0 2.8-2.8V7A2.8 2.8 0 0 0 17 4.2H7zm5 3.1a4.7 4.7 0 1 1 0 9.4 4.7 4.7 0 0 1 0-9.4zm0 2.2a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zm5.1-3.5a1.1 1.1 0 1 1 0 2.2 1.1 1.1 0 0 1 0-2.2z"/></svg></a>
                    @endif
                    @if(!empty($siteAppearance['telegram_url']))
                        <a class="shop-social-link" href="{{ $siteAppearance['telegram_url'] }}" target="_blank" rel="noopener noreferrer" aria-label="Telegram" title="Telegram"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M21.8 4.2 18.6 20c-.2 1.1-.9 1.4-1.8.9l-5-3.7-2.4 2.3c-.3.3-.5.5-1 .5l.4-5.1 9.3-8.4c.4-.4-.1-.6-.6-.3L6 13.4l-5-1.6c-1.1-.4-1.1-1.1.2-1.6L20.6 2c1-.4 1.8.2 1.2 2.2z"/></svg></a>
                    @endif
                    @if(!empty($siteAppearance['youtube_url']))
                        <a class="shop-social-link" href="{{ $siteAppearance['youtube_url'] }}" target="_blank" rel="noopener noreferrer" aria-label="YouTube" title="YouTube"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M23.5 6.2a3 3 0 0 0-2.1-2.1C19.5 3.6 12 3.6 12 3.6s-7.5 0-9.4.5A3 3 0 0 0 .5 6.2 31 31 0 0 0 0 12a31 31 0 0 0 .5 5.8 3 3 0 0 0 2.1 2.1c1.9.5 9.4.5 9.4.5s7.5 0 9.4-.5a3 3 0 0 0 2.1-2.1A31 31 0 0 0 24 12a31 31 0 0 0-.5-5.8zM9.6 15.6V8.4l6.3 3.6-6.3 3.6z"/></svg></a>
                    @endif
                </div>
                <div class="shop-footer-benefits"><span>✓ خرید امن</span><span>↺ ضمانت بازگشت</span><span>□ پشتیبانی همراه شما</span></div>
            </div>
            <div class="col-6 col-lg-3 col-md-3"><h5>دسترسی سریع</h5><a href="{{ url('/') }}">خانه</a><a href="{{ route('shop.products.index') }}">همه محصولات</a>@auth<a href="{{ route('orders.index') }}">سفارش‌های من</a>@endauth</div>
            <div class="col-6 col-lg-4 col-md-3"><h5>حساب کاربری</h5>@guest<a href="{{ route('login') }}">ورود</a>@if(Route::has('register'))<a href="{{ route('register') }}">ثبت‌نام</a>@endif @else<a href="{{ route('dashboard') }}">حساب کاربری</a><a href="{{ route('wishlist.index') }}">علاقه‌مندی‌ها</a>@endguest</div>
        </div></div>
        <div class="shop-footer-bottom"><div class="container text-center">© {{ date('Y') }} {{ $siteAppearance['logo_text'] ?? config('app.name', 'فروشگاه آنلاین') }} — تمامی حقوق محفوظ است.</div></div>
    </footer>
</div>
</body>
</html>
