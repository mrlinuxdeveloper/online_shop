<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    /**
     * لیست Coupon ها
     */
    public function index()
    {
        $coupons = Coupon::latest()
            ->paginate(15);

        return view(
            'admin.coupons.index',
            compact('coupons')
        );
    }

    /**
     * فرم ایجاد Coupon
     */
    public function create()
    {
        return view('admin.coupons.create');
    }

    /**
     * ذخیره Coupon جدید
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => [
                'required',
                'string',
                'max:255',
                'unique:coupons,code',
            ],

            'type' => [
                'required',
                'in:fixed,percent',
            ],

            'value' => [
                'required',
                'numeric',
                'min:0',
            ],

            'min_order_amount' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'usage_limit' => [
                'nullable',
                'integer',
                'min:1',
            ],

            'starts_at' => [
                'nullable',
                'date',
            ],

            'expires_at' => [
                'nullable',
                'date',
                'after_or_equal:starts_at',
            ],

            'status' => [
                'nullable',
                'boolean',
            ],
        ]);

        if (
            $validated['type'] === 'percent' &&
            $validated['value'] > 100
        ) {
            return back()
                ->withErrors([
                    'value' =>
                        'درصد تخفیف نمی‌تواند بیشتر از 100 باشد.'
                ])
                ->withInput();
        }

        Coupon::create([
            'code' => strtoupper(
                trim($validated['code'])
            ),

            'type' => $validated['type'],

            'value' => $validated['value'],

            'min_order_amount' =>
                $validated['min_order_amount'] ?? null,

            'usage_limit' =>
                $validated['usage_limit'] ?? null,

            'used_count' => 0,

            'starts_at' =>
                $validated['starts_at'] ?? null,

            'expires_at' =>
                $validated['expires_at'] ?? null,

            'status' =>
                $request->boolean('status'),
        ]);

        return redirect()
            ->route('admin.coupons.index')
            ->with(
                'success',
                'کد تخفیف با موفقیت ایجاد شد.'
            );
    }

    /**
     * فرم ویرایش Coupon
     */
    public function edit(Coupon $coupon)
    {
        return view(
            'admin.coupons.edit',
            compact('coupon')
        );
    }

    /**
     * بروزرسانی Coupon
     */
    public function update(
        Request $request,
        Coupon $coupon
    ) {
        $validated = $request->validate([
            'code' => [
                'required',
                'string',
                'max:255',
                'unique:coupons,code,' . $coupon->id,
            ],

            'type' => [
                'required',
                'in:fixed,percent',
            ],

            'value' => [
                'required',
                'numeric',
                'min:0',
            ],

            'min_order_amount' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'usage_limit' => [
                'nullable',
                'integer',
                'min:1',
            ],

            'starts_at' => [
                'nullable',
                'date',
            ],

            'expires_at' => [
                'nullable',
                'date',
                'after_or_equal:starts_at',
            ],

            'status' => [
                'nullable',
                'boolean',
            ],
        ]);

        if (
            $validated['type'] === 'percent' &&
            $validated['value'] > 100
        ) {
            return back()
                ->withErrors([
                    'value' =>
                        'درصد تخفیف نمی‌تواند بیشتر از 100 باشد.'
                ])
                ->withInput();
        }

        $coupon->update([
            'code' => strtoupper(
                trim($validated['code'])
            ),

            'type' => $validated['type'],

            'value' => $validated['value'],

            'min_order_amount' =>
                $validated['min_order_amount'] ?? null,

            'usage_limit' =>
                $validated['usage_limit'] ?? null,

            'starts_at' =>
                $validated['starts_at'] ?? null,

            'expires_at' =>
                $validated['expires_at'] ?? null,

            'status' =>
                $request->boolean('status'),
        ]);

        return redirect()
            ->route('admin.coupons.index')
            ->with(
                'success',
                'کد تخفیف با موفقیت بروزرسانی شد.'
            );
    }

    /**
     * فعال / غیرفعال کردن Coupon
     */
    public function toggleStatus(Coupon $coupon)
    {
        $coupon->update([
            'status' => !$coupon->status,
        ]);

        return back()->with(
            'success',
            'وضعیت کد تخفیف تغییر کرد.'
        );
    }

    /**
     * حذف Coupon
     */
    public function destroy(Coupon $coupon)
    {
        if ($coupon->usages()->exists()) {
            return back()->with(
                'error',
                'این کد تخفیف استفاده شده و قابل حذف نیست.'
            );
        }

        $coupon->delete();

        return back()->with(
            'success',
            'کد تخفیف با موفقیت حذف شد.'
        );
    }
}
