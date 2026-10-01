<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\PaymentController;

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\SizeController;
use App\Http\Controllers\Admin\ColorController;
use App\Http\Controllers\Admin\ProductVariantController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\Admin\OrderController;

use App\Http\Controllers\Shop\ProductController as ShopProductController;
use App\Http\Controllers\AddressController;

/*
|--------------------------------------------------------------------------
| Authentication
|--------------------------------------------------------------------------
*/

Auth::routes();


/*
|--------------------------------------------------------------------------
| Home
|--------------------------------------------------------------------------
*/

Route::get('/', [
    HomeController::class,
    'index'
])->name('home');


/*
|--------------------------------------------------------------------------
| Shop
|--------------------------------------------------------------------------
*/

/*
|--------------------------------------------------------------------------
| Product Listing
|--------------------------------------------------------------------------
*/

Route::get('/products', [
    ShopProductController::class,
    'index'
])->name('shop.products.index');


/*
|--------------------------------------------------------------------------
| Product Details
|--------------------------------------------------------------------------
*/

Route::get('/products/{product}', [
    ShopProductController::class,
    'show'
])->name('shop.products.show');


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


    Route::middleware('auth')->group(function () {


        Route::get('/addresses', [AddressController::class, 'index'])
            ->name('addresses.index');

        Route::get('/addresses/create', [AddressController::class, 'create'])
            ->name('addresses.create');

        Route::post('/addresses', [AddressController::class, 'store'])
            ->name('addresses.store');

        Route::get('/addresses/{address}/edit', [AddressController::class, 'edit'])
            ->name('addresses.edit');

        Route::put('/addresses/{address}', [AddressController::class, 'update'])
            ->name('addresses.update');

        Route::delete('/addresses/{address}', [AddressController::class, 'destroy'])
            ->name('addresses.destroy');


    });


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
    | Payment
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
| این Route نباید داخل auth باشد.
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

        Route::resource(
            'categories',
            CategoryController::class
        )->except(['show']);


        /*
        |--------------------------------------------------------------------------
        | Brands
        |--------------------------------------------------------------------------
        */

        Route::resource(
            'brands',
            BrandController::class
        )->except(['show']);


        /*
        |--------------------------------------------------------------------------
        | Products
        |--------------------------------------------------------------------------
        */

        Route::resource(
            'products',
            ProductController::class
        )->except(['show']);


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
        | Sizes
        |--------------------------------------------------------------------------
        */

        Route::resource(
            'sizes',
            SizeController::class
        )->except(['show']);


        /*
        |--------------------------------------------------------------------------
        | Colors
        |--------------------------------------------------------------------------
        */

        Route::resource(
            'colors',
            ColorController::class
        )->except(['show']);


        /*
        |--------------------------------------------------------------------------
        | Orders
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/orders',
            [OrderController::class, 'index']
        )->name('orders.index');

        Route::get(
            '/orders/{order}',
            [OrderController::class, 'show']
        )->name('orders.show');

        Route::patch(
            '/orders/{order}/status',
            [OrderController::class, 'updateStatus']
        )->name('orders.status');

        Route::patch(
            '/orders/{order}/payment-status',
            [OrderController::class, 'updatePaymentStatus']
        )->name('orders.payment-status');

        Route::get(
            '/orders/{order}/invoice',
            [OrderController::class, 'invoice']
        )->name('orders.invoice');

        Route::patch(
            '/orders/{order}/shipment',
            [OrderController::class, 'updateShipment']
        )->name('orders.shipment');

    });
