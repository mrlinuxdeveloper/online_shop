@extends('layouts.app')

@section('title', 'حساب کاربری')

@section('content')
    <style>
        .account-page{max-width:1180px;margin:0 auto;padding:38px 20px 12px}.account-hero{position:relative;overflow:hidden;border-radius:20px;padding:30px 34px;color:#fff;background:linear-gradient(120deg,var(--shop-primary,#75172c),#371523)}.account-hero:after{content:'✦';position:absolute;left:34px;top:-35px;font-size:180px;line-height:1;color:#ffffff0c}.account-hero h1{font-size:25px;font-weight:800;margin:0 0 8px}.account-hero p{color:#f4e9ed;margin:0;font-size:14px}.account-hero a{display:inline-flex;align-items:center;gap:7px;border:1px solid #ffffff70;border-radius:8px;padding:9px 14px;color:white;font-size:13px}.account-hero a:hover{background:#ffffff18}.account-stats{display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:14px;margin:22px 0}.account-stat{border:1px solid #eee;border-radius:14px;background:#fff;padding:18px;box-shadow:0 5px 20px #15151506}.account-stat-label{font-size:12px;color:#777;margin-bottom:10px}.account-stat-value{font-size:25px;font-weight:800;line-height:1.2}.account-stat-icon{float:left;width:38px;height:38px;display:grid;place-items:center;border-radius:11px;background:#f8edf0;color:var(--shop-primary,#75172c);font-size:19px}.account-panel{border:1px solid #eee;border-radius:14px;background:#fff;box-shadow:0 5px 20px #15151506}.account-panel-head{display:flex;justify-content:space-between;align-items:center;gap:12px;padding:18px 20px;border-bottom:1px solid #eee}.account-panel-head h2{font-size:16px;font-weight:800;margin:0}.account-panel-head a,.account-link{font-size:12px;color:var(--shop-primary,#75172c);font-weight:700}.account-table{width:100%;border-collapse:collapse}.account-table th,.account-table td{padding:14px 20px;text-align:right;border-bottom:1px solid #f0f0f0;font-size:13px}.account-table th{font-size:11px;color:#777;font-weight:600;background:#fcfcfc}.account-table tr:last-child td{border-bottom:0}.account-order-number{font-weight:700;direction:ltr;display:inline-block}.account-badge{display:inline-block;padding:5px 9px;border-radius:20px;background:#f1f2f4;color:#555;font-size:11px;white-space:nowrap}.account-badge.paid,.account-badge.delivered{background:#e9f7ef;color:#16834e}.account-badge.pending,.account-badge.processing,.account-badge.shipped{background:#fff4df;color:#9a6500}.account-quick-links{display:grid;grid-template-columns:repeat(3,1fr);gap:12px;margin-top:18px}.account-quick-link{display:flex;align-items:center;gap:12px;padding:16px;border:1px solid #eee;border-radius:12px;background:white;transition:.2s}.account-quick-link:hover{border-color:#d9bdc5;transform:translateY(-2px);box-shadow:0 8px 22px #1515150a}.account-quick-icon{width:40px;height:40px;flex:0 0 40px;display:grid;place-items:center;border-radius:11px;background:#f8edf0;color:var(--shop-primary,#75172c);font-size:19px}.account-quick-link strong{display:block;font-size:13px}.account-quick-link small{color:#777;font-size:11px}.account-empty{padding:34px 18px;text-align:center;color:#777;font-size:13px}.account-empty a{display:inline-flex;margin-top:12px;padding:9px 14px;border-radius:8px;background:var(--shop-primary,#75172c);color:#fff}
        @media(max-width:767px){.account-page{padding:24px 14px 8px}.account-hero{padding:24px 20px}.account-hero h1{font-size:21px}.account-hero:after{left:12px}.account-stats{grid-template-columns:repeat(2,minmax(0,1fr));gap:10px}.account-stat{padding:14px}.account-stat-value{font-size:22px}.account-quick-links{grid-template-columns:repeat(2,minmax(0,1fr));gap:9px}.account-quick-link{padding:12px;gap:9px}.account-quick-icon{width:35px;height:35px;flex-basis:35px}.account-table-wrap{overflow:auto}.account-table th,.account-table td{padding:12px;white-space:nowrap}}
    </style>

    <section class="account-page">
        <div class="account-hero d-flex flex-wrap justify-content-between align-items-center gap-3">
            <div>
                <h1>سلام، {{ $user->name }} 👋</h1>
                <p>به حساب کاربری‌ات خوش آمدی؛ سفارش‌ها و انتخاب‌هایت را از اینجا پیگیری کن.</p>
            </div>
            <a href="{{ route('shop.products.index') }}"><span aria-hidden="true">＋</span> شروع خرید</a>
        </div>

        <div class="account-stats">
            <div class="account-stat"><span class="account-stat-icon" aria-hidden="true">▤</span><div class="account-stat-label">همه سفارش‌ها</div><div class="account-stat-value">{{ number_format($stats['orders']) }}</div></div>
            <div class="account-stat"><span class="account-stat-icon" aria-hidden="true">◷</span><div class="account-stat-label">در جریان</div><div class="account-stat-value">{{ number_format($stats['active_orders']) }}</div></div>
            <div class="account-stat"><span class="account-stat-icon" aria-hidden="true">⌖</span><div class="account-stat-label">نشانی‌ها</div><div class="account-stat-value">{{ number_format($stats['addresses']) }}</div></div>
            <div class="account-stat"><span class="account-stat-icon" aria-hidden="true">♡</span><div class="account-stat-label">علاقه‌مندی‌ها</div><div class="account-stat-value">{{ number_format($stats['wishlist_items']) }}</div></div>
        </div>

        <div class="account-panel">
            <div class="account-panel-head">
                <h2>آخرین سفارش‌ها</h2>
                <a href="{{ route('orders.index') }}">مشاهده همه سفارش‌ها ←</a>
            </div>
            @if($orders->isNotEmpty())
                <div class="account-table-wrap">
                    <table class="account-table">
                        <thead><tr><th>شماره سفارش</th><th>تاریخ</th><th>مبلغ</th><th>وضعیت</th><th>پرداخت</th><th></th></tr></thead>
                        <tbody>
                        @foreach($orders as $order)
                            @php
                                $statusLabels = ['pending' => 'در انتظار', 'processing' => 'در حال پردازش', 'shipped' => 'ارسال شده', 'delivered' => 'تحویل شده', 'cancelled' => 'لغو شده', 'returned' => 'مرجوع شده'];
                                $paymentLabels = ['pending' => 'در انتظار پرداخت', 'paid' => 'پرداخت‌شده', 'failed' => 'ناموفق', 'refunded' => 'بازپرداخت‌شده'];
                            @endphp
                            <tr>
                                <td><span class="account-order-number">{{ $order->order_number }}</span></td>
                                <td>{{ $order->created_at?->format('Y/m/d') }}</td>
                                <td>{{ number_format((float) $order->total_price) }} تومان</td>
                                <td><span class="account-badge {{ $order->status }}">{{ $statusLabels[$order->status] ?? $order->status }}</span></td>
                                <td><span class="account-badge {{ $order->payment_status === 'paid' ? 'paid' : $order->payment_status }}">{{ $paymentLabels[$order->payment_status] ?? $order->payment_status }}</span></td>
                                <td><a class="account-link" href="{{ route('orders.show', $order) }}">جزئیات</a></td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="account-empty">هنوز سفارشی ثبت نکرده‌ای.<br><a href="{{ route('shop.products.index') }}">دیدن محصولات</a></div>
            @endif
        </div>

        <nav class="account-quick-links" aria-label="دسترسی‌های حساب کاربری">
            <a class="account-quick-link" href="{{ route('orders.index') }}"><span class="account-quick-icon" aria-hidden="true">▤</span><span><strong>سفارش‌های من</strong><small>پیگیری و مشاهده جزئیات</small></span></a>
            <a class="account-quick-link" href="{{ route('addresses.index') }}"><span class="account-quick-icon" aria-hidden="true">⌖</span><span><strong>نشانی‌های من</strong><small>مدیریت نشانی‌های ارسال</small></span></a>
            <a class="account-quick-link" href="{{ route('wishlist.index') }}"><span class="account-quick-icon" aria-hidden="true">♡</span><span><strong>علاقه‌مندی‌ها</strong><small>محصولات ذخیره‌شده</small></span></a>
            <a class="account-quick-link" href="{{ route('cart.index') }}"><span class="account-quick-icon" aria-hidden="true">🛍</span><span><strong>سبد خرید</strong><small>ادامه خریدهای نیمه‌تمام</small></span></a>
            <a class="account-quick-link" href="{{ route('shop.products.index') }}"><span class="account-quick-icon" aria-hidden="true">⌕</span><span><strong>فروشگاه</strong><small>پیدا کردن انتخاب بعدی</small></span></a>
            @if(auth()->user()->adminUser)
                <a class="account-quick-link" href="{{ route('admin.dashboard') }}"><span class="account-quick-icon" aria-hidden="true">⚙</span><span><strong>مدیریت فروشگاه</strong><small>ورود به پنل مدیریت</small></span></a>
            @endif
        </nav>
    </section>
@endsection
