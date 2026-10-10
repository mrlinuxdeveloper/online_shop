<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

//use App\Http\Controllers\HomeController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\AddressController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\SizeController;
use App\Http\Controllers\Admin\ColorController;
use App\Http\Controllers\Admin\ProductVariantController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\User\OrderController as UserOrderController;
use App\Http\Controllers\User\DashboardController as UserDashboardController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\Shop\ProductController as ShopProductController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\Admin\ReviewController as AdminReviewController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\InventoryController;
use App\Http\Controllers\Admin\CouponController;
use App\Http\Controllers\Admin\ShipmentController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\AppearanceController;
use App\Http\Controllers\Shop\HomeController;



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

Route::get('/', [HomeController::class,'index'])->name('home');



/*
|--------------------------------------------------------------------------
| Shop
|--------------------------------------------------------------------------
*/

Route::get('/products', [
    ShopProductController::class,
    'index'
])->name('shop.products.index');

Route::get('/products/{product}', [
    ShopProductController::class,
    'show'
])->name('shop.products.show');


/*
|--------------------------------------------------------------------------
| Payment Callback
|--------------------------------------------------------------------------
|
| این Route نباید داخل auth باشد.
| مهم: باید قبل از /payment/{order} قرار بگیرد.
|
*/

Route::get('/payment/verify', [
    PaymentController::class,
    'verify'
])->name('payment.verify');


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

    Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('dashboard');


    /*
    |--------------------------------------------------------------------------
    | Addresses
    |--------------------------------------------------------------------------
    */

    Route::get('/addresses', [
        AddressController::class,
        'index'
    ])->name('addresses.index');

    Route::get('/addresses/create', [
        AddressController::class,
        'create'
    ])->name('addresses.create');

    Route::post('/addresses', [
        AddressController::class,
        'store'
    ])->name('addresses.store');

    Route::get('/addresses/{address}/edit', [
        AddressController::class,
        'edit'
    ])->name('addresses.edit');

    Route::put('/addresses/{address}', [
        AddressController::class,
        'update'
    ])->name('addresses.update');

    Route::delete('/addresses/{address}', [
        AddressController::class,
        'destroy'
    ])->name('addresses.destroy');


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
    | Payment Start
    |--------------------------------------------------------------------------
    */
// payment
    Route::get('/payment/{order}', [
        PaymentController::class,
        'payment'
    ])->name('payment.start');

    Route::get('/payment/failed/{order}', [
        PaymentController::class,
        'failed'
    ])->name('payment.failed');

    Route::get('/payment/retry/{order}', [
        PaymentController::class,
        'retry'
    ])->name('payment.retry');


// My Orders
    Route::get('/orders', [
        UserOrderController::class,
        'index'
    ])->name('orders.index');

    Route::get('/orders/{order}/invoice', [
        UserOrderController::class,
        'invoice'
    ])->name('orders.invoice');


    Route::get('/orders/{order}', [
        UserOrderController::class,
        'show'
    ])->name('orders.show');

// wishlist
    Route::get('/wishlist', [
        WishlistController::class,
        'index'
    ])->name('wishlist.index');

    Route::post('/wishlist/{product}', [
        WishlistController::class,
        'store'
    ])->name('wishlist.store');

    Route::delete('/wishlist/{product}', [
        WishlistController::class,
        'destroy'
    ])->name('wishlist.destroy');

    Route::post('/products/{product}/reviews', [
        ReviewController::class,
        'store'
    ])->name('reviews.store');

    Route::post(
        '/checkout/coupon',
        [CheckoutController::class, 'applyCoupon']
    )->name('checkout.coupon');

// end auth
});


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

//        Route::get('/dashboard', [
//            AdminDashboardController::class,
//            'index'
//        ])->name('dashboard');

        Route::get('/dashboard', [ DashboardController::class, 'index' ])->name('dashboard');

        Route::get('/appearance', [AppearanceController::class, 'edit'])->name('appearance.edit');
        Route::put('/appearance', [AppearanceController::class, 'update'])->name('appearance.update');

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


// reviews

        Route::get('/reviews', [
            AdminReviewController::class,
            'index'
        ])->name('reviews.index');

        Route::patch('/reviews/{review}/approve', [
            AdminReviewController::class,
            'approve'
        ])->name('reviews.approve');

        Route::patch('/reviews/{review}/reject', [
            AdminReviewController::class,
            'reject'
        ])->name('reviews.reject');

        Route::delete('/reviews/{review}', [
            AdminReviewController::class,
            'destroy'
        ])->name('reviews.destroy');

// user

        Route::get('/users', [
            AdminUserController::class,
            'index'
        ])->name('users.index');

        Route::patch('/users/{user}/make-admin', [
            AdminUserController::class,
            'makeAdmin'
        ])->name('users.make-admin');

        Route::patch('/users/{user}/remove-admin', [
            AdminUserController::class,
            'removeAdmin'
        ])->name('users.remove-admin');

// inventory
        Route::get('/inventory', [
            InventoryController::class,
            'index'
        ])->name('inventory.index');

        Route::get('/inventory/transactions', [
            InventoryController::class,
            'transactions'
        ])->name('inventory.transactions');

        Route::patch('/inventory/{variant}/increase', [
            InventoryController::class,
            'adjust'
        ])->name('inventory.increase');

        Route::patch('/inventory/{variant}/decrease', [
            InventoryController::class,
            'decrease'
        ])->name('inventory.decrease');


        Route::get('/coupons', [
            CouponController::class,
            'index'
        ])->name('coupons.index');

        Route::get('/coupons/create', [
            CouponController::class,
            'create'
        ])->name('coupons.create');

        Route::post('/coupons', [
            CouponController::class,
            'store'
        ])->name('coupons.store');

        Route::get('/coupons/{coupon}/edit', [
            CouponController::class,
            'edit'
        ])->name('coupons.edit');

        Route::put('/coupons/{coupon}', [
            CouponController::class,
            'update'
        ])->name('coupons.update');

        Route::patch('/coupons/{coupon}/toggle-status', [
            CouponController::class,
            'toggleStatus'
        ])->name('coupons.toggle-status');

        Route::delete('/coupons/{coupon}', [
            CouponController::class,
            'destroy'
        ])->name('coupons.destroy');


        Route::get('/shipments', [
            ShipmentController::class,
            'index'
        ])->name('shipments.index');

        Route::get('/shipments/create', [
            ShipmentController::class,
            'create'
        ])->name('shipments.create');

        Route::post('/shipments', [
            ShipmentController::class,
            'store'
        ])->name('shipments.store');

        Route::get('/shipments/{shipment}', [
            ShipmentController::class,
            'show'
        ])->name('shipments.show');

        Route::get('/shipments/{shipment}/edit', [
            ShipmentController::class,
            'edit'
        ])->name('shipments.edit');

        Route::put('/shipments/{shipment}', [
            ShipmentController::class,
            'update'
        ])->name('shipments.update');

        Route::patch('/shipments/{shipment}/status', [
            ShipmentController::class,
            'updateStatus'
        ])->name('shipments.update-status');

        Route::delete('/shipments/{shipment}', [
            ShipmentController::class,
            'destroy'
        ])->name('shipments.destroy');


        Route::get('/reports', [ ReportController::class, 'index' ])->name('reports.index');





// end admin route
    });
