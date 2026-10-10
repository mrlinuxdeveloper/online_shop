<!doctype html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'مدیریت فروشگاه') | {{ config('app.name', 'فروشگاه') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Vazirmatn:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <style>
        :root { --admin-ink:#1d2939; --admin-muted:#667085; --admin-line:#e7ebf0; --admin-bg:#f5f7fa; --admin-brand:#176b57; --admin-brand-dark:#115443; }
        * { box-sizing:border-box; }
        body { margin:0; background:var(--admin-bg); color:var(--admin-ink); font-family:'Vazirmatn',Tahoma,sans-serif; font-size:14px; }
        a { text-decoration:none; }
        .admin-sidebar { position:fixed; inset:0 0 0 auto; z-index:1040; display:flex; flex-direction:column; width:270px; background:#142b27; color:#f2f6f4; transition:transform .25s ease; }
        .admin-brand { display:flex; align-items:center; gap:12px; min-height:78px; padding:16px 22px; border-bottom:1px solid #ffffff17; color:#fff; font-size:17px; font-weight:800; }
        .admin-brand-mark { display:grid; width:38px; height:38px; place-items:center; border-radius:12px; background:#ffffff17; font-size:20px; }
        .admin-nav { overflow:auto; padding:16px 12px 24px; }
        .admin-nav-label { padding:14px 12px 7px; color:#91aaa4; font-size:11px; font-weight:700; }
        .admin-nav-link { display:flex; align-items:center; gap:11px; min-height:43px; margin:3px 0; padding:9px 12px; border-radius:10px; color:#c5d4d0; transition:background .15s,color .15s; }
        .admin-nav-link:hover,.admin-nav-link.active { background:#ffffff16; color:#fff; }
        .admin-nav-icon { width:23px; text-align:center; font-size:16px; }
        .admin-main { min-height:100vh; margin-right:270px; }
        .admin-topbar { position:sticky; top:0; z-index:1020; display:flex; align-items:center; justify-content:space-between; min-height:72px; padding:12px 30px; border-bottom:1px solid var(--admin-line); background:#fff; }
        .admin-page-title { margin:0; font-size:17px; font-weight:800; }
        .admin-page-caption { margin-top:3px; color:var(--admin-muted); font-size:12px; }
        .admin-content { padding:26px 30px 42px; }
        .admin-content .card,.admin-content .table-responsive,.admin-content .shadow-sm { border-color:var(--admin-line); border-radius:14px; }
        .admin-content .card { box-shadow:0 3px 12px #172b4d08; }
        .admin-content .table { --bs-table-bg:transparent; }
        .admin-content .table thead th { color:#667085; background:#f8fafb; font-size:12px; white-space:nowrap; }
        .admin-content .btn-primary { border-color:var(--admin-brand); background:var(--admin-brand); }
        .admin-content .btn-primary:hover { border-color:var(--admin-brand-dark); background:var(--admin-brand-dark); }
        .admin-content input,.admin-content select,.admin-content textarea { border-color:#d8dee7; }
        .admin-content input:focus,.admin-content select:focus,.admin-content textarea:focus { border-color:#62a796; box-shadow:0 0 0 .2rem #176b571a; }
        .admin-overlay { display:none; }
        .admin-sidebar-toggle { display:none; border:0; background:transparent; color:var(--admin-ink); font-size:23px; }
        @media(max-width:991.98px) {
            .admin-sidebar { transform:translateX(100%); }
            body.admin-menu-open .admin-sidebar { transform:translateX(0); }
            body.admin-menu-open .admin-overlay { position:fixed; inset:0; z-index:1030; display:block; background:#10182880; }
            .admin-main { margin-right:0; }
            .admin-sidebar-toggle { display:inline-flex; align-items:center; justify-content:center; margin-left:10px; }
            .admin-topbar { justify-content:flex-start; padding:12px 18px; }
            .admin-topbar-actions { margin-right:auto; }
            .admin-content { padding:20px 16px 32px; }
        }
        @media(max-width:575.98px) { .admin-page-caption,.admin-user-name { display:none; } .admin-content { padding:16px 12px 28px; } }
    </style>
    @stack('styles')
</head>
<body>
<aside class="admin-sidebar" id="adminSidebar" aria-label="منوی مدیریت">
    <a class="admin-brand" href="{{ route('admin.dashboard') }}">
        <span class="admin-brand-mark">🛍</span>
        <span>{{ config('app.name', 'مدیریت فروشگاه') }}</span>
    </a>
    <nav class="admin-nav">
        <div class="admin-nav-label">نمای کلی</div>
        <a href="{{ route('admin.dashboard') }}" class="admin-nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"><span class="admin-nav-icon">▦</span>داشبورد</a>

        <div class="admin-nav-label">فروشگاه</div>
        <a href="{{ route('admin.products.index') }}" class="admin-nav-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}"><span class="admin-nav-icon">◈</span>محصولات</a>
        <a href="{{ route('admin.categories.index') }}" class="admin-nav-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}"><span class="admin-nav-icon">▤</span>دسته‌بندی‌ها</a>
        <a href="{{ route('admin.brands.index') }}" class="admin-nav-link {{ request()->routeIs('admin.brands.*') ? 'active' : '' }}"><span class="admin-nav-icon">✦</span>برندها</a>
        <a href="{{ route('admin.sizes.index') }}" class="admin-nav-link {{ request()->routeIs('admin.sizes.*') ? 'active' : '' }}"><span class="admin-nav-icon">↔</span>سایزها</a>
        <a href="{{ route('admin.colors.index') }}" class="admin-nav-link {{ request()->routeIs('admin.colors.*') ? 'active' : '' }}"><span class="admin-nav-icon">◉</span>رنگ‌ها</a>
        <a href="{{ route('admin.inventory.index') }}" class="admin-nav-link {{ request()->routeIs('admin.inventory.*') ? 'active' : '' }}"><span class="admin-nav-icon">▣</span>موجودی انبار</a>

        <div class="admin-nav-label">سفارش و ارتباط</div>
        <a href="{{ route('admin.orders.index') }}" class="admin-nav-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}"><span class="admin-nav-icon">▧</span>سفارش‌ها</a>
        <a href="{{ route('admin.shipments.index') }}" class="admin-nav-link {{ request()->routeIs('admin.shipments.*') ? 'active' : '' }}"><span class="admin-nav-icon">➤</span>ارسال‌ها</a>
        <a href="{{ route('admin.reviews.index') }}" class="admin-nav-link {{ request()->routeIs('admin.reviews.*') ? 'active' : '' }}"><span class="admin-nav-icon">☆</span>نظرات</a>

        <div class="admin-nav-label">مدیریت</div>
        <a href="{{ route('admin.coupons.index') }}" class="admin-nav-link {{ request()->routeIs('admin.coupons.*') ? 'active' : '' }}"><span class="admin-nav-icon">％</span>کدهای تخفیف</a>
        <a href="{{ route('admin.users.index') }}" class="admin-nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}"><span class="admin-nav-icon">♙</span>کاربران</a>
        <a href="{{ route('admin.reports.index') }}" class="admin-nav-link {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}"><span class="admin-nav-icon">▥</span>گزارش‌ها</a>
        <a href="{{ route('admin.appearance.edit') }}" class="admin-nav-link {{ request()->routeIs('admin.appearance.*') ? 'active' : '' }}"><span class="admin-nav-icon">◐</span>ظاهر سایت</a>
    </nav>
</aside>
<button class="admin-overlay" type="button" aria-label="بستن منو" onclick="document.body.classList.remove('admin-menu-open')"></button>

<main class="admin-main">
    <header class="admin-topbar">
        <button class="admin-sidebar-toggle" type="button" aria-label="باز کردن منو" aria-controls="adminSidebar" aria-expanded="false" onclick="document.body.classList.toggle('admin-menu-open'); this.setAttribute('aria-expanded', document.body.classList.contains('admin-menu-open'))">☰</button>
        <div>
            <h1 class="admin-page-title">@yield('page-title', 'داشبورد مدیریت')</h1>
            <div class="admin-page-caption">مدیریت و پیگیری فروشگاه آنلاین</div>
        </div>
        <div class="admin-topbar-actions d-flex align-items-center gap-3">
            <a href="{{ route('home') }}" class="btn btn-sm btn-outline-secondary d-none d-sm-inline-flex">مشاهده فروشگاه</a>
            <span class="admin-user-name fw-semibold">{{ auth()->user()->name }}</span>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-sm btn-outline-danger">خروج</button>
            </form>
        </div>
    </header>

    <section class="admin-content">
        @if(session('success'))<div class="alert alert-success alert-dismissible fade show" role="alert">{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="بستن"></button></div>@endif
        @if(session('error'))<div class="alert alert-danger alert-dismissible fade show" role="alert">{{ session('error') }}<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="بستن"></button></div>@endif
        @if($errors->any())<div class="alert alert-danger" role="alert"><div class="fw-bold mb-1">لطفاً موارد زیر را بررسی کنید:</div><ul class="mb-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
        @yield('content')
    </section>
</main>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
