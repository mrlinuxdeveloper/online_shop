@extends('admin.layouts.AdminMasterLayout')

@section('title', 'ظاهر سایت')
@section('page-title', 'مدیریت ظاهر سایت')

@section('content')
    <style>
        .appearance-help{color:#667085;font-size:12px}.appearance-section-title{font-size:15px;font-weight:800}.slide-card{border:1px solid #e5e9ef;border-radius:12px;background:#fff}.slide-card .card-header{background:#f8fafb;border-bottom:1px solid #e5e9ef}.slide-preview{width:100%;max-height:210px;object-fit:cover;border-radius:8px;background:#f2f2f2}.color-control{width:52px;height:40px;padding:4px}
    </style>

    <form action="{{ route('admin.appearance.update') }}" method="POST" enctype="multipart/form-data" id="appearance-form">
        @csrf
        @method('PUT')

        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
            <div><div class="appearance-section-title">تنظیمات فروشگاه</div><div class="appearance-help">تغییرات پس از ذخیره در سایت نمایش داده می‌شوند.</div></div>
            <button class="btn btn-primary" type="submit">ذخیره تغییرات</button>
        </div>

        <div class="card mb-4"><div class="card-body p-4">
            <h2 class="appearance-section-title mb-3">هویت و رنگ‌بندی</h2>
            <div class="row g-3">
                <div class="col-md-6"><label for="logo_text" class="form-label">نام نمایشی فروشگاه</label><input id="logo_text" name="logo_text" class="form-control" value="{{ old('logo_text', $appearance['logo_text']) }}" maxlength="80" required><div class="appearance-help mt-1">برای عنوان صفحه و متن جایگزین لوگو استفاده می‌شود.</div></div>
                <div class="col-md-6"><label for="logo_image" class="form-label">تصویر لوگو (اختیاری)</label><input id="logo_image" name="logo_image" type="file" class="form-control" accept="image/jpeg,image/png,image/webp"><div class="appearance-help mt-1">JPG، PNG یا WebP؛ حداکثر ۲ مگابایت.</div>
                    @if(!empty($appearance['logo_image']))<div class="d-flex align-items-center gap-3 mt-2"><img src="{{ asset('storage/' . $appearance['logo_image']) }}" alt="لوگوی فعلی" style="max-width:150px;max-height:58px;object-fit:contain"><div class="form-check"><input type="checkbox" class="form-check-input" name="remove_logo_image" value="1" id="remove-logo-image"><label for="remove-logo-image" class="form-check-label">حذف لوگوی فعلی</label></div></div>@endif
                </div>
                <div class="col-md-6"><label for="promo_text" class="form-label">متن نوار بالای سایت</label><input id="promo_text" name="promo_text" class="form-control" value="{{ old('promo_text', $appearance['promo_text']) }}" maxlength="180"><div class="appearance-help mt-1">برای اطلاع‌رسانی، کمپین یا پیشنهاد ویژه.</div></div>
                <div class="col-md-6"><label for="primary_color" class="form-label">رنگ اصلی سایت</label><div class="d-flex gap-2"><input id="primary_color" name="primary_color" type="color" class="form-control form-control-color color-control" value="{{ old('primary_color', $appearance['primary_color']) }}" title="انتخاب رنگ اصلی"><input class="form-control" value="{{ old('primary_color', $appearance['primary_color']) }}" data-color-text="primary_color" aria-label="کد رنگ اصلی"></div></div>
                <div class="col-md-6"><label for="accent_color" class="form-label">رنگ مکمل</label><div class="d-flex gap-2"><input id="accent_color" name="accent_color" type="color" class="form-control form-control-color color-control" value="{{ old('accent_color', $appearance['accent_color']) }}" title="انتخاب رنگ مکمل"><input class="form-control" value="{{ old('accent_color', $appearance['accent_color']) }}" data-color-text="accent_color" aria-label="کد رنگ مکمل"></div></div>
                <div class="col-12"><label for="footer_tagline" class="form-label">توضیح کوتاه پایین سایت</label><textarea id="footer_tagline" name="footer_tagline" rows="2" class="form-control" maxlength="250">{{ old('footer_tagline', $appearance['footer_tagline']) }}</textarea></div>
                <div class="col-12"><hr class="my-1"><h3 class="appearance-section-title mb-0">شبکه‌های اجتماعی</h3><div class="appearance-help">نشانی هر شبکه را وارد کنید تا پیوند آن در فوتر فروشگاه نمایش داده شود.</div></div>
                <div class="col-md-4"><label for="instagram_url" class="form-label">Instagram</label><input id="instagram_url" name="instagram_url" type="url" dir="ltr" class="form-control" value="{{ old('instagram_url', $appearance['instagram_url'] ?? '') }}" placeholder="https://instagram.com/yourshop"></div>
                <div class="col-md-4"><label for="telegram_url" class="form-label">Telegram</label><input id="telegram_url" name="telegram_url" type="url" dir="ltr" class="form-control" value="{{ old('telegram_url', $appearance['telegram_url'] ?? '') }}" placeholder="https://t.me/yourshop"></div>
                <div class="col-md-4"><label for="youtube_url" class="form-label">YouTube</label><input id="youtube_url" name="youtube_url" type="url" dir="ltr" class="form-control" value="{{ old('youtube_url', $appearance['youtube_url'] ?? '') }}" placeholder="https://youtube.com/@yourshop"></div>
            </div>
        </div></div>

        <div class="card mb-4"><div class="card-body p-4">
            <h2 class="appearance-section-title mb-1">بخش‌های صفحهٔ اصلی</h2><p class="appearance-help mb-3">بخش‌هایی را که می‌خواهید در صفحهٔ خانه نمایش داده شوند روشن بگذارید.</p>
            <div class="row g-3">
                @foreach(['show_categories' => 'دسته‌بندی‌ها', 'show_featured' => 'محصولات ویژه', 'show_benefits' => 'مزیت‌های خرید', 'show_latest' => 'تازه‌های فروشگاه', 'show_brands' => 'برندها'] as $key => $label)
                    <div class="col-sm-6 col-lg-4"><div class="form-check form-switch border rounded p-3 pe-5"><input class="form-check-input" type="checkbox" role="switch" id="{{ $key }}" name="{{ $key }}" value="1" @checked(old($key, $appearance[$key]))><label class="form-check-label fw-semibold" for="{{ $key }}">{{ $label }}</label></div></div>
                @endforeach
            </div>
        </div></div>

        <div class="d-flex flex-wrap justify-content-between align-items-end gap-2 mb-3">
            <div><h2 class="appearance-section-title mb-1">اسلایدر صفحهٔ اصلی</h2><p class="appearance-help mb-0">عنوان، متن، پیوند و تصویر هر اسلاید را تنظیم کنید. حداکثر پنج اسلاید می‌توانید داشته باشید.</p></div>
            <button type="button" id="add-slide" class="btn btn-outline-primary btn-sm">＋ افزودن اسلاید</button>
        </div>

        <div id="slides-list" class="d-grid gap-3">
            @foreach(old('slides', $appearance['slides']) as $index => $slide)
                <div class="card slide-card" data-slide-card>
                    <div class="card-header d-flex align-items-center justify-content-between py-3 px-4"><strong>اسلاید <span data-slide-number>{{ $loop->iteration }}</span></strong><button type="button" class="btn btn-sm btn-outline-danger" data-remove-slide>حذف اسلاید</button></div>
                    <div class="card-body p-4"><div class="row g-3">
                        <div class="col-lg-8"><div class="row g-3">
                            <div class="col-md-6"><label class="form-label">متن کوچک بالای عنوان</label><input name="slides[{{ $index }}][eyebrow]" class="form-control" value="{{ $slide['eyebrow'] ?? '' }}" maxlength="80"></div>
                            <div class="col-md-6"><label class="form-label">عنوان اسلاید</label><input name="slides[{{ $index }}][title]" class="form-control" value="{{ $slide['title'] ?? '' }}" maxlength="140" required></div>
                            <div class="col-12"><label class="form-label">توضیح</label><textarea name="slides[{{ $index }}][description]" class="form-control" rows="3" maxlength="300">{{ $slide['description'] ?? '' }}</textarea></div>
                            <div class="col-md-6"><label class="form-label">متن دکمه</label><input name="slides[{{ $index }}][button_text]" class="form-control" value="{{ $slide['button_text'] ?? 'مشاهده محصولات' }}" maxlength="50" required></div>
                            <div class="col-md-6"><label class="form-label">پیوند دکمه</label><input name="slides[{{ $index }}][button_url]" class="form-control" value="{{ $slide['button_url'] ?? '/products' }}" placeholder="/products" maxlength="255" required><div class="appearance-help mt-1">پیوند داخلی مثل /products یا نشانی کامل https://…</div></div>
                            <div class="col-12"><div class="form-check form-switch"><input class="form-check-input" type="checkbox" role="switch" name="slides[{{ $index }}][active]" value="1" id="slide-active-{{ $index }}" @checked($slide['active'] ?? false)><label class="form-check-label" for="slide-active-{{ $index }}">نمایش این اسلاید</label></div></div>
                        </div></div>
                        <div class="col-lg-4"><label class="form-label">تصویر اسلاید</label>
                            @if(!empty($slide['image']))<img class="slide-preview mb-2" src="{{ asset('storage/' . $slide['image']) }}" alt="پیش‌نمایش اسلاید">@else<div class="slide-preview mb-2 d-flex align-items-center justify-content-center text-muted" style="height:150px">بدون تصویر</div>@endif
                            <input type="file" name="slides[{{ $index }}][image]" class="form-control" accept="image/jpeg,image/png,image/webp"><div class="appearance-help mt-1">JPG، PNG یا WebP؛ حداکثر ۵ مگابایت.</div>
                            @if(!empty($slide['image']))<div class="form-check mt-2"><input type="checkbox" class="form-check-input" name="slides[{{ $index }}][remove_image]" value="1" id="remove-image-{{ $index }}"><label for="remove-image-{{ $index }}" class="form-check-label">حذف تصویر فعلی</label></div>@endif
                        </div>
                    </div></div>
                </div>
            @endforeach
        </div>
        <div class="d-flex justify-content-end mt-4"><button class="btn btn-primary px-4" type="submit">ذخیره تنظیمات ظاهر</button></div>
    </form>
@endsection

@push('scripts')
<script>
(() => {
    const list = document.getElementById('slides-list');
    const addButton = document.getElementById('add-slide');
    let nextIndex = Math.max(-1, ...[...list.querySelectorAll('[name^="slides["]')].map((field) => Number(field.name.match(/^slides\[(\d+)\]/)?.[1] ?? -1))) + 1;
    const refreshNumbers = () => {
        list.querySelectorAll('[data-slide-card]').forEach((card, index) => card.querySelector('[data-slide-number]').textContent = index + 1);
        addButton.disabled = list.querySelectorAll('[data-slide-card]').length >= 5;
    };
    list.addEventListener('click', (event) => {
        const button = event.target.closest('[data-remove-slide]');
        if (!button) return;
        if (list.querySelectorAll('[data-slide-card]').length <= 1) return;
        button.closest('[data-slide-card]').remove();
        refreshNumbers();
    });
    addButton.addEventListener('click', () => {
        if (list.querySelectorAll('[data-slide-card]').length >= 5) return;
        const index = nextIndex++;
        const card = document.createElement('div');
        card.className = 'card slide-card';
        card.dataset.slideCard = '';
        card.innerHTML = `<div class="card-header d-flex align-items-center justify-content-between py-3 px-4"><strong>اسلاید <span data-slide-number></span></strong><button type="button" class="btn btn-sm btn-outline-danger" data-remove-slide>حذف اسلاید</button></div><div class="card-body p-4"><div class="row g-3"><div class="col-lg-8"><div class="row g-3"><div class="col-md-6"><label class="form-label">متن کوچک بالای عنوان</label><input name="slides[${index}][eyebrow]" class="form-control" maxlength="80"></div><div class="col-md-6"><label class="form-label">عنوان اسلاید</label><input name="slides[${index}][title]" class="form-control" maxlength="140" required></div><div class="col-12"><label class="form-label">توضیح</label><textarea name="slides[${index}][description]" class="form-control" rows="3" maxlength="300"></textarea></div><div class="col-md-6"><label class="form-label">متن دکمه</label><input name="slides[${index}][button_text]" class="form-control" value="مشاهده محصولات" maxlength="50" required></div><div class="col-md-6"><label class="form-label">پیوند دکمه</label><input name="slides[${index}][button_url]" class="form-control" value="/products" maxlength="255" required></div><div class="col-12"><div class="form-check form-switch"><input class="form-check-input" type="checkbox" role="switch" name="slides[${index}][active]" value="1" id="slide-active-${index}" checked><label class="form-check-label" for="slide-active-${index}">نمایش این اسلاید</label></div></div></div></div><div class="col-lg-4"><label class="form-label">تصویر اسلاید</label><div class="slide-preview mb-2 d-flex align-items-center justify-content-center text-muted" style="height:150px">بدون تصویر</div><input type="file" name="slides[${index}][image]" class="form-control" accept="image/jpeg,image/png,image/webp"><div class="appearance-help mt-1">JPG، PNG یا WebP؛ حداکثر ۵ مگابایت.</div></div></div></div>`;
        list.append(card);
        refreshNumbers();
    });
    document.querySelectorAll('[data-color-text]').forEach((textInput) => {
        const colorInput = document.getElementById(textInput.dataset.colorText);
        textInput.addEventListener('input', () => { if (/^#[0-9a-f]{6}$/i.test(textInput.value)) colorInput.value = textInput.value; });
        colorInput.addEventListener('input', () => textInput.value = colorInput.value);
    });
    refreshNumbers();
})();
</script>
@endpush
