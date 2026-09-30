<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\SizeController;
use App\Http\Controllers\Admin\ColorController;
use App\Http\Controllers\Admin\ProductVariantController;
use App\Http\Controllers\Admin\CartController;
use App\Http\Controllers\Shop\ProductController as ShopProductController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\Admin\OrderController;


/*
|--------------------------------------------------------------------------
| Authentication
|--------------------------------------------------------------------------
*/

Auth::routes();


/*
|--------------------------------------------------------------------------
| User Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Dashboard
    |--------------------------------------------------------------------------
    */

    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');


    /*
    |--------------------------------------------------------------------------
    | Cart
    |--------------------------------------------------------------------------
    */

    Route::get('/cart', [
        CartController::class,
        'index'
    ])->name('cart.index');

    Route::post('/cart/{product}', [
        CartController::class,
        'store'
    ])->name('cart.store');

    Route::patch('/cart/item/{cartItem}', [
        CartController::class,
        'update'
    ])->name('cart.update');

    Route::delete('/cart/item/{cartItem}', [
        CartController::class,
        'destroy'
    ])->name('cart.destroy');


    /*
    |--------------------------------------------------------------------------
    | Checkout
    |--------------------------------------------------------------------------
    */

    Route::get('/checkout', [
        CheckoutController::class,
        'index'
    ])->name('checkout.index');

    Route::post('/checkout', [
        CheckoutController::class,
        'store'
    ])->name('checkout.store');

    Route::get('/checkout/success/{order}', [
        CheckoutController::class,
        'success'
    ])->name('checkout.success');


    /*
    |--------------------------------------------------------------------------
    | Start Payment
    |--------------------------------------------------------------------------
    */

    Route::get('/payment/{order}', [
        PaymentController::class,
        'payment'
    ])->name('payment.start');

});


/*
|--------------------------------------------------------------------------
| Payment Callback
|--------------------------------------------------------------------------
|
| این route نباید داخل auth باشد.
| زرین‌پال بعد از پرداخت کاربر را به این URL برمی‌گرداند.
|
*/

Route::get('/payment/verify', [
    PaymentController::class,
    'verify'
])->name('payment.verify');


/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | Dashboard
        |--------------------------------------------------------------------------
        */

        Route::get('/dashboard', [
            AdminDashboardController::class,
            'index'
        ])->name('dashboard');


        /*
        |--------------------------------------------------------------------------
        | Categories
        |--------------------------------------------------------------------------
        */

        Route::resource('categories', CategoryController::class)
            ->except(['show']);


        /*
        |--------------------------------------------------------------------------
        | Brands
        |--------------------------------------------------------------------------
        */

        Route::resource('brands', BrandController::class)
            ->except(['show']);


        /*
        |--------------------------------------------------------------------------
        | Products
        |--------------------------------------------------------------------------
        */

        Route::resource('products', ProductController::class)
            ->except(['show']);


        /*
        |--------------------------------------------------------------------------
        | Sizes
        |--------------------------------------------------------------------------
        */

        Route::resource('sizes', SizeController::class)
            ->except(['show']);


        /*
        |--------------------------------------------------------------------------
        | Colors
        |--------------------------------------------------------------------------
        */

        Route::resource('colors', ColorController::class)
            ->except(['show']);


        /*
        |--------------------------------------------------------------------------
        | Product Images
        |--------------------------------------------------------------------------
        */

        Route::get(
            'products/{product}/images',
            [ProductController::class, 'images']
        )->name('products.images');

        Route::post(
            'products/{product}/images',
            [ProductController::class, 'storeImage']
        )->name('products.images.store');

        Route::delete(
            'products/{product}/images/{image}',
            [ProductController::class, 'destroyImage']
        )->name('products.images.destroy');

        Route::patch(
            'products/{product}/images/{image}/primary',
            [ProductController::class, 'setPrimaryImage']
        )->name('products.images.primary');


        /*
        |--------------------------------------------------------------------------
        | Product Variants
        |--------------------------------------------------------------------------
        */

        Route::get(
            'products/{product}/variants',
            [ProductVariantController::class, 'index']
        )->name('products.variants.index');

        Route::get(
            'products/{product}/variants/create',
            [ProductVariantController::class, 'create']
        )->name('products.variants.create');

        Route::post(
            'products/{product}/variants',
            [ProductVariantController::class, 'store']
        )->name('products.variants.store');

        Route::get(
            'products/{product}/variants/{variant}/edit',
            [ProductVariantController::class, 'edit']
        )->name('products.variants.edit');

        Route::put(
            'products/{product}/variants/{variant}',
            [ProductVariantController::class, 'update']
        )->name('products.variants.update');

        Route::delete(
            'products/{product}/variants/{variant}',
            [ProductVariantController::class, 'destroy']
        )->name('products.variants.destroy');

        /*
|--------------------------------------------------------------------------
| Orders
|--------------------------------------------------------------------------
*/

        Route::get('/orders', [
            OrderController::class,
            'index'
        ])->name('orders.index');

        Route::get('/orders/{order}', [
            OrderController::class,
            'show'
        ])->name('orders.show');

        Route::patch('/orders/{order}/status', [
            OrderController::class,
            'updateStatus'
        ])->name('orders.status');

    });


/*
|--------------------------------------------------------------------------
| Shop
|--------------------------------------------------------------------------
*/

Route::get('/products/{product}', [
    ShopProductController::class,
    'show'
])->name('shop.products.show');

/*
|--------------------------------------------------------------------------
| Orders
|--------------------------------------------------------------------------
*/

Route::get('/orders', [
    OrderController::class,
    'index'
])->name('orders.index');

Route::get('/orders/{order}', [
    OrderController::class,
    'show'
])->name('orders.show');

Route::patch('/orders/{order}/status', [
    OrderController::class,
    'updateStatus'
])->name('orders.status');
