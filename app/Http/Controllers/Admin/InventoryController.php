<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InventoryTransaction;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InventoryController extends Controller
{
    public function index()
    {
        $variants = ProductVariant::with([
            'product',
            'size',
            'color',
        ])
            ->latest('id')
            ->paginate(20);

        return view(
            'admin.inventory.index',
            compact('variants')
        );
    }

    public function transactions()
    {
        $transactions = InventoryTransaction::with([
            'product',
            'variant.size',
            'variant.color',
        ])
            ->latest()
            ->paginate(20);

        return view(
            'admin.inventory.transactions',
            compact('transactions')
        );
    }

    public function adjust(Request $request, ProductVariant $variant)
    {
        $validated = $request->validate([
            'type' => [
                'required',
                'in:purchase,return,adjustment',
            ],

            'quantity' => [
                'required',
                'integer',
                'min:1',
            ],

            'description' => [
                'nullable',
                'string',
                'max:1000',
            ],
        ]);

        DB::transaction(function () use ($variant, $validated) {

            $variant = ProductVariant::where(
                'id',
                $variant->id
            )
                ->lockForUpdate()
                ->firstOrFail();

            $stockBefore = (int) $variant->stock;

            /*
             * purchase و return موجودی را افزایش می‌دهند.
             *
             * adjustment در این فرم نیز به عنوان
             * افزایش موجودی در نظر گرفته شده است.
             */

            $quantity = (int) $validated['quantity'];

            $stockAfter = $stockBefore + $quantity;

            $variant->update([
                'stock' => $stockAfter,
            ]);

            InventoryTransaction::create([
                'product_id' => $variant->product_id,
                'product_variant_id' => $variant->id,
                'type' => $validated['type'],
                'quantity' => $quantity,
                'stock_before' => $stockBefore,
                'stock_after' => $stockAfter,
                'description' => $validated['description'] ?? null,
            ]);
        });

        return back()->with(
            'success',
            'موجودی با موفقیت افزایش پیدا کرد.'
        );
    }

    public function decrease(Request $request, ProductVariant $variant)
    {
        $validated = $request->validate([
            'quantity' => [
                'required',
                'integer',
                'min:1',
            ],

            'description' => [
                'nullable',
                'string',
                'max:1000',
            ],
        ]);

        DB::transaction(function () use ($variant, $validated) {

            $variant = ProductVariant::where(
                'id',
                $variant->id
            )
                ->lockForUpdate()
                ->firstOrFail();

            $stockBefore = (int) $variant->stock;
            $quantity = (int) $validated['quantity'];

            if ($quantity > $stockBefore) {
                abort(
                    422,
                    'موجودی کافی نیست.'
                );
            }

            $stockAfter = $stockBefore - $quantity;

            $variant->update([
                'stock' => $stockAfter,
            ]);

            InventoryTransaction::create([
                'product_id' => $variant->product_id,
                'product_variant_id' => $variant->id,
                'type' => 'adjustment',
                'quantity' => -$quantity,
                'stock_before' => $stockBefore,
                'stock_after' => $stockAfter,
                'description' => $validated['description'] ?? null,
            ]);
        });

        return back()->with(
            'success',
            'موجودی با موفقیت کاهش پیدا کرد.'
        );
    }
}
