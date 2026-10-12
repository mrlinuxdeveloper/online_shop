@extends('layouts.app')

@section('title', config('app.name', 'فروشگاه آنلاین'))
@section('meta_description', 'خرید آنلاین پوشاک و استایل روز؛ جدیدترین محصولات، برندهای منتخب و پیشنهادهای ویژه را با ارسال به سراسر ایران در ' . config('app.name', 'فروشگاه آنلاین') . ' ببینید.')
@section('canonical_url', route('home'))
@push('seo')
    <script type="application/ld+json">{!! json_encode([
        '@context' => 'https://schema.org',
        '@type' => 'WebSite',
        'name' => config('app.name', 'فروشگاه آنلاین'),
        'url' => route('home'),
        'potentialAction' => [
            '@type' => 'SearchAction',
            'target' => route('shop.products.index') . '?search={search_term_string}',
            'query-input' => 'required name=search_term_string',
        ],
    ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) !!}</script>
@endpush

@section('content')
    @php
        $heroProduct = $featuredProducts->first() ?? $latestProducts->first();
        $heroImage = $heroProduct?->images?->firstWhere('is_primary', true) ?? $heroProduct?->images?->first();
        if ($slides->isEmpty()) {
            $slides = collect([[
                'eyebrow' => 'تازه رسیده‌ها · انتخاب فصل',
                'title' => 'هر روز، یک استایل تازه',
                'description' => 'از میان انتخاب‌های جدید فروشگاه، چیزی را پیدا کن که به حال‌وهوای تو نزدیک‌تر است.',
                'button_text' => 'دیدن همه محصولات',
                'button_url' => '/products',
                'image' => null,
                'active' => true,
            ]]);
        }
    @endphp
    <style>
        .fashion-home{--wine:{{ $primaryColor }};--accent:{{ preg_match('/^#[0-9A-Fa-f]{6}$/', $appearance['accent_color'] ?? '') ? $appearance['accent_color'] : '#d7193f' }};--ink:#191919;--muted:#777;--line:#e9e9e9}.fashion-wrap{max-width:1320px;margin:auto;padding-left:24px;padding-right:24px}
        .fashion-hero{position:relative;min-height:440px;margin:22px auto 0;overflow:hidden;background:#f3eeeb;border-radius:3px}.fashion-slide{display:none;grid-template-columns:1fr 1fr;min-height:440px}.fashion-slide.active{display:grid}
        .fashion-hero-copy{position:relative;z-index:1;padding:70px 8% 65px 4%;display:flex;flex-direction:column;align-items:flex-start;justify-content:center}
        .fashion-kicker{font-size:12px;color:var(--wine);font-weight:700;letter-spacing:.4px;margin-bottom:18px}.fashion-hero h1{font-size:clamp(32px,4vw,55px);line-height:1.5;letter-spacing:-1.3px;font-weight:800;margin:0 0 10px;max-width:570px}.fashion-hero p{color:#626262;font-size:15px;line-height:2;margin:0;max-width:450px}.fashion-cta{display:inline-flex;align-items:center;gap:12px;margin-top:28px;background:var(--ink);color:white;padding:13px 24px;font-size:13px;font-weight:600;transition:.2s}.fashion-cta:hover{background:var(--wine);color:#fff;transform:translateY(-2px)}
        .fashion-hero-visual{position:relative;min-height:440px;background:linear-gradient(135deg,#e9dfdb,#c5b2aa);overflow:hidden}.fashion-hero-visual img{height:100%;width:100%;position:absolute;inset:0;object-fit:cover;object-position:center 32%}.fashion-hero-visual:after{content:"";position:absolute;inset:0;background:linear-gradient(90deg,#f3eeeb 0%,rgba(243,238,235,.12) 35%,transparent 72%)}.fashion-hero-mark{position:absolute;left:7%;bottom:8%;z-index:1;color:white;font-size:clamp(45px,8vw,100px);font-weight:800;line-height:1;opacity:.85;text-shadow:0 2px 20px #0003}
        .fashion-slider-controls{position:absolute;z-index:3;bottom:18px;right:25px;display:flex;gap:7px}.fashion-slider-controls button{border:1px solid #222;background:#fff;color:#222;width:34px;height:34px;border-radius:50%;font-size:16px;line-height:1}.fashion-slider-controls button:hover{background:#222;color:white}.fashion-slider-dots{display:flex;gap:5px;align-items:center;padding:0 6px}.fashion-slider-dots button{width:7px;height:7px;border:0;padding:0;background:#aaa}.fashion-slider-dots button.active{width:20px;border-radius:5px;background:#222}
        .fashion-cats{display:flex;gap:12px;overflow-x:auto;padding:18px 0 5px;scrollbar-width:none}.fashion-cats::-webkit-scrollbar{display:none}.fashion-cat{flex:0 0 155px;height:92px;border:1px solid var(--line);padding:16px;display:flex;flex-direction:column;justify-content:space-between;transition:.2s;background:#fff}.fashion-cat:hover{border-color:#222;transform:translateY(-2px)}.fashion-cat span:first-child{font-size:14px;font-weight:700;overflow:hidden;text-overflow:ellipsis;white-space:nowrap}.fashion-cat span:last-child{font-size:11px;color:#888;white-space:nowrap}
        .fashion-section{padding-top:54px}.fashion-section-head{display:flex;align-items:flex-end;justify-content:space-between;margin-bottom:20px}.fashion-section-head h2{font-size:22px;margin:0;font-weight:800;letter-spacing:-.4px}.fashion-section-head p{font-size:12px;color:var(--muted);margin:5px 0 0}.fashion-more{font-size:12px;font-weight:700;border-bottom:1px solid #222;padding-bottom:4px;white-space:nowrap}.fashion-grid{display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:20px}
        .fashion-product{min-width:0;display:flex;flex-direction:column}.fashion-product-image{position:relative;display:block;aspect-ratio:3/4;background:#f4f4f4;overflow:hidden}.fashion-product-image img{width:100%;height:100%;object-fit:cover;transition:transform .45s}.fashion-product:hover .fashion-product-image img{transform:scale(1.035)}.fashion-product-placeholder{height:100%;display:flex;align-items:center;justify-content:center;color:#bbb;font-size:45px}.fashion-discount{position:absolute;top:12px;right:12px;padding:5px 8px;background:var(--accent);color:white;font-size:11px;font-weight:700}.fashion-product-info{padding:12px 2px 0;display:flex;flex:1;flex-direction:column}.fashion-product-category{font-size:11px;color:#858585;margin-bottom:5px;height:18px;overflow:hidden}.fashion-product-name{display:-webkit-box;-webkit-box-orient:vertical;-webkit-line-clamp:2;overflow:hidden;height:46px;font-size:13px;font-weight:600;line-height:1.8}.fashion-product-price{display:flex;align-items:center;gap:9px;margin-top:auto;padding-top:8px;font-size:14px;font-weight:800}.fashion-old-price{font-size:11px;color:#999;text-decoration:line-through;font-weight:400}.fashion-toman{font-size:10px;color:#777;font-weight:500}
        .fashion-benefits{display:grid;grid-template-columns:repeat(3,1fr);gap:12px;margin-top:60px;border-top:1px solid var(--line);border-bottom:1px solid var(--line);padding:22px 0}.fashion-benefit{display:flex;align-items:center;justify-content:center;gap:11px;font-size:12px;font-weight:600}.fashion-benefit b{width:38px;height:38px;flex:0 0 38px;display:grid;place-items:center;border-radius:50%;background:#f6ecef;font-size:19px;line-height:1;color:var(--wine)}.fashion-benefit span{color:#777;font-size:11px;font-weight:400;display:block;margin-top:3px}
        .fashion-brands{display:grid;grid-template-columns:repeat(6,minmax(0,1fr));grid-auto-rows:96px;gap:12px}.fashion-brand{height:96px;border:1px solid var(--line);display:flex;align-items:center;justify-content:center;text-align:center;padding:12px;font-size:13px;font-weight:700;transition:.2s;overflow:hidden}.fashion-brand:hover{border-color:#222}.fashion-brand img{max-width:100%;max-height:55px;object-fit:contain}
        .fashion-empty{grid-column:1/-1;border:1px dashed #ddd;padding:34px;text-align:center;font-size:13px;color:#777}
        @media(max-width:900px){.fashion-grid{grid-template-columns:repeat(3,minmax(0,1fr));gap:14px}.fashion-hero{min-height:380px}.fashion-slide{min-height:380px}.fashion-hero-visual{min-height:380px}.fashion-hero-copy{padding-right:7%}.fashion-brands{grid-template-columns:repeat(4,minmax(0,1fr))}}
        @media(max-width:600px){.fashion-wrap{padding-left:15px;padding-right:15px}.fashion-hero{min-height:0;margin-top:14px}.fashion-slide,.fashion-slide.active{grid-template-columns:1fr;min-height:0}.fashion-hero-copy{grid-row:2;padding:24px 22px 42px}.fashion-hero h1{font-size:31px}.fashion-hero p{font-size:13px}.fashion-hero-visual{grid-row:1;min-height:240px;max-height:280px}.fashion-hero-visual:after{background:linear-gradient(0deg,rgba(0,0,0,.14),transparent 50%)}.fashion-hero-mark{font-size:56px}.fashion-slider-controls{bottom:10px;right:12px}.fashion-cat{flex-basis:135px;height:82px;padding:13px}.fashion-section{padding-top:38px}.fashion-section-head h2{font-size:19px}.fashion-section-head p{font-size:10px}.fashion-grid{grid-template-columns:repeat(2,minmax(0,1fr));gap:18px 11px}.fashion-product-info{padding-top:9px}.fashion-product-name{font-size:12px;height:44px}.fashion-product-price{font-size:12px;gap:6px}.fashion-benefits{grid-template-columns:1fr;gap:15px;margin-top:40px;padding:18px 0}.fashion-benefit{justify-content:flex-start}.fashion-brands{grid-template-columns:repeat(3,minmax(0,1fr));grid-auto-rows:86px}.fashion-brand{height:86px}}
    </style>

    <div class="fashion-home">
        <section class="fashion-wrap fashion-hero" aria-label="اسلایدر پیشنهادهای فروشگاه" data-fashion-slider>
            @foreach($slides as $index => $slide)
                <div class="fashion-slide {{ $index === 0 ? 'active' : '' }}" data-slide="{{ $index }}">
                    <div class="fashion-hero-copy">
                        @if(!empty($slide['eyebrow']))<div class="fashion-kicker">{{ $slide['eyebrow'] }}</div>@endif
                        <h1>{{ $slide['title'] }}</h1>
                        @if(!empty($slide['description']))<p>{{ $slide['description'] }}</p>@endif
                        <a href="{{ $slide['button_url'] }}" class="fashion-cta">{{ $slide['button_text'] }} <span aria-hidden="true">←</span></a>
                    </div>
                    <div class="fashion-hero-visual">
                        @php $slideImage = $slide['image'] ?? null; @endphp
                        @if($slideImage)<img src="{{ asset('storage/' . $slideImage) }}" alt="{{ $slide['title'] }}">@elseif($heroImage)<img src="{{ asset('storage/' . $heroImage->image) }}" alt="{{ $heroProduct->name }}">@endif
                        <div class="fashion-hero-mark">استایل<br>تازه</div>
                    </div>
                </div>
            @endforeach
            @if($slides->count() > 1)
                <div class="fashion-slider-controls" aria-label="کنترل اسلایدر">
                    <button type="button" data-slider-prev aria-label="اسلاید قبلی">›</button>
                    <div class="fashion-slider-dots">@foreach($slides as $index => $slide)<button type="button" class="{{ $index === 0 ? 'active' : '' }}" data-slider-dot="{{ $index }}" aria-label="رفتن به اسلاید {{ $index + 1 }}"></button>@endforeach</div>
                    <button type="button" data-slider-next aria-label="اسلاید بعدی">‹</button>
                </div>
            @endif
        </section>

        @if(($appearance['show_categories'] ?? true) && $categories->isNotEmpty())
            <section class="fashion-wrap" aria-label="دسته‌بندی‌ها">
                <div class="fashion-cats">
                    @foreach($categories as $category)
                        <a class="fashion-cat" href="{{ route('shop.products.index', ['category' => $category->id]) }}">
                            <span>{{ $category->name }}</span><span>{{ $category->products_count }} محصول <b aria-hidden="true">←</b></span>
                        </a>
                    @endforeach
                    <a class="fashion-cat" href="{{ route('shop.products.index') }}"><span>همه محصولات</span><span>مشاهده فروشگاه <b aria-hidden="true">←</b></span></a>
                </div>
            </section>
        @endif

        @if(($appearance['show_featured'] ?? true) && $featuredProducts->isNotEmpty())
            <section class="fashion-wrap fashion-section">
                <div class="fashion-section-head"><div><h2>انتخاب‌های ویژه</h2><p>محصولاتی که برای دیدن انتخاب شده‌اند</p></div><a class="fashion-more" href="{{ route('shop.products.index') }}">مشاهده همه</a></div>
                <div class="fashion-grid">
                    @foreach($featuredProducts as $product)
                        @php $primaryImage = $product->images->firstWhere('is_primary', true) ?? $product->images->first(); $finalPrice = $product->price * (1 - $product->discount / 100); @endphp
                        <article class="fashion-product">
                            <a href="{{ route('shop.products.show', $product) }}" class="fashion-product-image">
                                @if($primaryImage)<img src="{{ asset('storage/' . $primaryImage->image) }}" alt="{{ $product->name }}" loading="lazy">@else<div class="fashion-product-placeholder">✧</div>@endif
                                @if($product->discount > 0)<span class="fashion-discount">{{ number_format($product->discount) }}٪ تخفیف</span>@endif
                            </a>
                            <div class="fashion-product-info">
                                @if($product->category)<div class="fashion-product-category">{{ $product->category->name }}</div>@endif
                                <a class="fashion-product-name" href="{{ route('shop.products.show', $product) }}">{{ $product->name }}</a>
                                <div class="fashion-product-price"><span>{{ number_format($finalPrice) }} <small class="fashion-toman">تومان</small></span>@if($product->discount > 0)<span class="fashion-old-price">{{ number_format($product->price) }}</span>@endif</div>
                            </div>
                        </article>
                    @endforeach
                </div>
            </section>
        @endif

        @if($appearance['show_benefits'] ?? true)<section class="fashion-wrap fashion-benefits" aria-label="خدمات فروشگاه">
            <div class="fashion-benefit"><b>↺</b><div>خرید آسان<span>انتخاب راحت از میان محصولات</span></div></div>
            <div class="fashion-benefit"><b>◇</b><div>پرداخت امن<span>پرداخت آنلاین از درگاه بانکی</span></div></div>
            <div class="fashion-benefit"><b>♧</b><div>پشتیبانی همراه شما<span>پیگیری سفارش در حساب کاربری</span></div></div>
        </section>@endif

        @if($appearance['show_latest'] ?? true)<section class="fashion-wrap fashion-section">
            <div class="fashion-section-head"><div><h2>تازه‌های فروشگاه</h2><p>جدیدترین محصولاتی که به فروشگاه اضافه شده‌اند</p></div><a class="fashion-more" href="{{ route('shop.products.index') }}">همه محصولات</a></div>
            <div class="fashion-grid">
                @forelse($latestProducts as $product)
                    @php $primaryImage = $product->images->firstWhere('is_primary', true) ?? $product->images->first(); $finalPrice = $product->price * (1 - $product->discount / 100); @endphp
                    <article class="fashion-product">
                        <a href="{{ route('shop.products.show', $product) }}" class="fashion-product-image">
                            @if($primaryImage)<img src="{{ asset('storage/' . $primaryImage->image) }}" alt="{{ $product->name }}" loading="lazy">@else<div class="fashion-product-placeholder">✧</div>@endif
                            @if($product->discount > 0)<span class="fashion-discount">{{ number_format($product->discount) }}٪ تخفیف</span>@endif
                        </a>
                        <div class="fashion-product-info">
                            @if($product->category)<div class="fashion-product-category">{{ $product->category->name }}</div>@endif
                            <a class="fashion-product-name" href="{{ route('shop.products.show', $product) }}">{{ $product->name }}</a>
                            <div class="fashion-product-price"><span>{{ number_format($finalPrice) }} <small class="fashion-toman">تومان</small></span>@if($product->discount > 0)<span class="fashion-old-price">{{ number_format($product->price) }}</span>@endif</div>
                        </div>
                    </article>
                @empty
                    <div class="fashion-empty">هنوز محصولی به فروشگاه اضافه نشده است.</div>
                @endforelse
            </div>
        </section>@endif

        @if(($appearance['show_brands'] ?? true) && $brands->isNotEmpty())
            <section class="fashion-wrap fashion-section">
                <div class="fashion-section-head"><div><h2>برندهای فروشگاه</h2><p>انتخاب از میان برندهای موجود</p></div><a class="fashion-more" href="{{ route('shop.products.index') }}">مشاهده فروشگاه</a></div>
                <div class="fashion-brands">
                    @foreach($brands as $brand)
                        <a class="fashion-brand" href="{{ route('shop.products.index', ['brand' => $brand->id]) }}">
                            @if($brand->logo)<img src="{{ asset('storage/' . $brand->logo) }}" alt="{{ $brand->name }}" loading="lazy">@else{{ $brand->name }}@endif
                        </a>
                    @endforeach
                </div>
            </section>
        @endif
    </div>
    @if($slides->count() > 1)
        <script>
            (() => {
                const slider = document.querySelector('[data-fashion-slider]');
                if (!slider) return;
                const slides = [...slider.querySelectorAll('[data-slide]')];
                const dots = [...slider.querySelectorAll('[data-slider-dot]')];
                let current = 0;
                const show = (index) => {
                    current = (index + slides.length) % slides.length;
                    slides.forEach((slide, i) => slide.classList.toggle('active', i === current));
                    dots.forEach((dot, i) => dot.classList.toggle('active', i === current));
                };
                slider.querySelector('[data-slider-prev]').addEventListener('click', () => show(current - 1));
                slider.querySelector('[data-slider-next]').addEventListener('click', () => show(current + 1));
                dots.forEach((dot, i) => dot.addEventListener('click', () => show(i)));
                if (!window.matchMedia('(prefers-reduced-motion: reduce)').matches) window.setInterval(() => show(current + 1), 6500);
            })();
        </script>
    @endif
@endsection
