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
            type="datetime-local"
            name="starts_at"
            value="{{
                old(
                    'starts_at',
                    isset($coupon->starts_at)
                        ? $coupon->starts_at->format('Y-m-d\TH:i')
                        : ''
                )
            }}"
            class="form-control"
        >

    </div>


    <div class="col-md-6 mb-3">

        <label class="form-label">
            پایان اعتبار
        </label>

        <input
            type="datetime-local"
            name="expires_at"
            value="{{
                old(
                    'expires_at',
                    isset($coupon->expires_at)
                        ? $coupon->expires_at->format('Y-m-d\TH:i')
                        : ''
                )
            }}"
            class="form-control"
        >

    </div>

</div>


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
