<div class="mb-3">

    <label class="form-label">
        کد تخفیف
    </label>

    <input
        type="text"
        name="code"
        value="{{ old('code', $coupon->code ?? '') }}"
        class="form-control @error('code') is-invalid @enderror"
        required
    >

    @error('code')
    <div class="invalid-feedback">
        {{ $message }}
    </div>
    @enderror

</div>


<div class="row">

    <div class="col-md-6 mb-3">

        <label class="form-label">
            نوع تخفیف
        </label>

        <select
            name="type"
            class="form-select"
            required
        >

            <option
                value="percent"
                @selected(old('type', $coupon->type ?? '') === 'percent')
            >
                درصدی
            </option>

            <option
                value="fixed"
                @selected(old('type', $coupon->type ?? '') === 'fixed')
            >
                مبلغ ثابت
            </option>

        </select>

    </div>


    <div class="col-md-6 mb-3">

        <label class="form-label">
            مقدار
        </label>

        <input
            type="number"
            name="value"
            min="0"
            step="0.01"
            value="{{ old('value', $coupon->value ?? '') }}"
            class="form-control"
            required
        >

    </div>

</div>


<div class="row">

    <div class="col-md-6 mb-3">

        <label class="form-label">
            حداقل مبلغ سفارش
        </label>

        <input
            type="number"
            name="min_order_amount"
            min="0"
            step="0.01"
            value="{{ old('min_order_amount', $coupon->min_order_amount ?? '') }}"
            class="form-control"
        >

    </div>


    <div class="col-md-6 mb-3">

        <label class="form-label">
            محدودیت استفاده
        </label>

        <input
            type="number"
            name="usage_limit"
            min="1"
            value="{{ old('usage_limit', $coupon->usage_limit ?? '') }}"
            class="form-control"
        >

        <small class="text-muted">
            خالی = بدون محدودیت
        </small>

    </div>

</div>


<div class="row">

    <div class="col-md-6 mb-3">

        <label class="form-label">
            شروع اعتبار
        </label>

        <input
            type="text"
            id="starts_at_jalali"
            class="form-control jalali-datetime-input @error('starts_at') is-invalid @enderror"
            placeholder="انتخاب تاریخ و ساعت شمسی"
            autocomplete="off"
            readonly
            data-jalali-date-input
            data-target="starts_at"
        >
        <input
            type="hidden"
            name="starts_at"
            id="starts_at"
            value="{{ old('starts_at', isset($coupon->starts_at) ? $coupon->starts_at->format('Y-m-d\TH:i') : '') }}"
            data-gregorian-date-value
        >
        @error('starts_at')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror

    </div>


    <div class="col-md-6 mb-3">

        <label class="form-label">
            پایان اعتبار
        </label>

        <input
            type="text"
            id="expires_at_jalali"
            class="form-control jalali-datetime-input @error('expires_at') is-invalid @enderror"
            placeholder="انتخاب تاریخ و ساعت شمسی"
            autocomplete="off"
            readonly
            data-jalali-date-input
            data-target="expires_at"
        >
        <input
            type="hidden"
            name="expires_at"
            id="expires_at"
            value="{{ old('expires_at', isset($coupon->expires_at) ? $coupon->expires_at->format('Y-m-d\TH:i') : '') }}"
            data-gregorian-date-value
        >
        @error('expires_at')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror

    </div>

</div>

@once
    @push('styles')
        <link rel="stylesheet" href="{{ asset('vendor/persian-datepicker/persian-datepicker.min.css') }}">
        <style>
            .jalali-datetime-input { direction: ltr; text-align: right; }
            .datepicker-plot-area { font-family: 'Vazirmatn', Tahoma, sans-serif; z-index: 1090; }
        </style>
    @endpush
    @push('scripts')
        <script src="{{ asset('vendor/persian-datepicker/jquery.min.js') }}"></script>
        <script src="{{ asset('vendor/persian-datepicker/persian-date.min.js') }}"></script>
        <script src="{{ asset('vendor/persian-datepicker/persian-datepicker.min.js') }}"></script>
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                document.querySelectorAll('[data-jalali-date-input]').forEach((input) => {
                    const hiddenInput = document.getElementById(input.dataset.target);
                    const existingValue = hiddenInput.value;

                    if (existingValue) {
                        const gregorianDate = new Date(existingValue);
                        if (!Number.isNaN(gregorianDate.getTime())) {
                            input.value = new persianDate(gregorianDate.getTime())
                                .format('YYYY/MM/DD HH:mm');
                        }
                    }

                    $(input).persianDatepicker({
                        format: 'YYYY/MM/DD HH:mm',
                        persianDigit: true,
                        autoClose: true,
                        observer: true,
                        timePicker: { enabled: true },
                        altField: `#${hiddenInput.id}`,
                        altFormat: 'gregorian',
                        altFieldFormatter: (timestamp) => {
                            const date = new Date(timestamp);
                            const pad = (value) => String(value).padStart(2, '0');
                            return `${date.getFullYear()}-${pad(date.getMonth() + 1)}-${pad(date.getDate())}T${pad(date.getHours())}:${pad(date.getMinutes())}`;
                        },
                    });
                });
            });
        </script>
    @endpush
@endonce


<div class="form-check mb-4">

    <input
        type="checkbox"
        name="status"
        value="1"
        class="form-check-input"
        id="status"
        @checked(old('status', $coupon->status ?? true))
    >

    <label
        class="form-check-label"
        for="status"
    >
        فعال باشد
    </label>

</div>
