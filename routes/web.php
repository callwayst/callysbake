<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| CONTROLLERS
|--------------------------------------------------------------------------
*/
use App\Http\Controllers\Shop\AddressController;
// USER / SHOP
use App\Http\Controllers\UserDashboardController;
use App\Http\Controllers\UserVoucherController;
use App\Http\Controllers\Shop\ProductController;
use App\Http\Controllers\Shop\CartController;
use App\Http\Controllers\Shop\OrderController;
use App\Http\Controllers\Shop\ReviewController;
use App\Http\Controllers\Shop\ProfileController as ShopProfileController;
use App\Http\Controllers\Shop\CheckoutController;

// ADMIN
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProfileController as AdminProfileController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\VoucherController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\ReviewController as AdminReviewController;


/*
|--------------------------------------------------------------------------
| PUBLIC
|--------------------------------------------------------------------------
*/

Route::get('/', fn () => view('welcome'));


/*
|--------------------------------------------------------------------------
| USER / SHOP
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    Route::get('/dashboard', [UserDashboardController::class, 'index'])
        ->name('dashboard');

    Route::get('/vouchers', [UserVoucherController::class, 'index'])
        ->name('user.vouchers.index');

    Route::post('/vouchers/{id}/claim', [UserVoucherController::class, 'claim'])
        ->name('user.vouchers.claim');

    Route::post('/addresses', [App\Http\Controllers\Shop\AddressController::class, 'store'])->name('addresses.store');
    Route::delete('/addresses/{address}', [App\Http\Controllers\Shop\AddressController::class, 'destroy'])->name('addresses.destroy');
    Route::patch('/addresses/{address}/default', [App\Http\Controllers\Shop\AddressController::class, 'setDefault'])->name('addresses.setDefault');

    // PRODUCTS
    Route::prefix('products')->group(function () {
        Route::get('/', [ProductController::class, 'index'])->name('products.index');
        Route::get('/{product}', [ProductController::class, 'show'])->name('products.show');
    });

    // CART
    Route::resource('cart', CartController::class)
        ->only(['index', 'store', 'update', 'destroy']);

    Route::post('/cart/apply-voucher', [CartController::class, 'applyVoucher'])->name('cart.applyVoucher');
    Route::post('/cart/remove-voucher', [CartController::class, 'removeVoucher'])->name('cart.removeVoucher');

    // ORDERS
    Route::resource('orders', OrderController::class)
        ->only(['index', 'show', 'store']);

    // REVIEWS
    Route::post('/reviews', [ReviewController::class, 'store'])
        ->name('reviews.store');

    Route::middleware('auth')->group(function () {

        Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
        Route::post('/cart/{cartItem}/increment', [CartController::class, 'increment'])->name('cart.increment');
        Route::post('/cart/{cartItem}/decrement', [CartController::class, 'decrement'])->name('cart.decrement');
        Route::post('/cart/{item}/claim/{voucher}', [CartController::class,'claimVoucher']);
        Route::get('/checkout', [CheckoutController::class,'index'])->name('checkout.index');
        Route::post('/checkout', [CheckoutController::class,'store'])->name('checkout.store');
        Route::delete('/cart/{cart}', [CartController::class, 'destroy'])->name('cart.destroy');
        Route::post('/checkout/select-address', [CheckoutController::class, 'selectAddress'])->name('checkout.select-address');
        Route::get('/orders', [App\Http\Controllers\Shop\OrderController::class,'index'])->name('orders.index');// Tambah di dalam Route::middleware('auth')->group
        Route::get('/orders/{id}', [App\Http\Controllers\Shop\OrderController::class, 'show'])->name('orders.show');
        Route::post('/orders/{id}/cancel', [App\Http\Controllers\Shop\OrderController::class, 'cancel'])->name('orders.cancel');
        Route::post('/orders/{id}/confirm', [App\Http\Controllers\Shop\OrderController::class, 'confirm'])->name('orders.confirm');
    });
    /*
    |--------------------------------------------------------------------------
    | SHOP PROFILE (FINAL & CLEAN — NO DUPLICATE)
    |--------------------------------------------------------------------------
    */
    Route::prefix('shop/profile')
        ->name('shop.profile.')
        ->group(function () {

            Route::get('/', [ShopProfileController::class, 'edit'])
                ->name('edit');

            Route::patch('/info', [ShopProfileController::class, 'updateInfo'])
                ->name('info.update');

            Route::patch('/password', [ShopProfileController::class, 'updatePassword'])
                ->name('password.update');

            Route::patch('/avatar', [ShopProfileController::class, 'updateAvatar'])
                ->name('avatar.update');

            Route::get('/orders', [ShopProfileController::class, 'orders'])
                ->name('orders');

            Route::delete('/delete', [ShopProfileController::class, 'destroy'])
                ->name('delete');
        });
});


/*
|--------------------------------------------------------------------------
| ADMIN
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get('/dashboard', [DashboardController::class, 'index'])
            ->name('dashboard');

        // Users
        Route::patch('users/{user}/toggle', [UserController::class, 'toggle'])
            ->name('users.toggle');
        Route::resource('users', UserController::class);

        // Profile
        Route::prefix('profile')->name('profile.')->group(function () {
            Route::get('/', [AdminProfileController::class, 'edit'])->name('edit');
            Route::patch('/avatar', [AdminProfileController::class, 'updateAvatar'])->name('avatar.update');
            Route::patch('/info', [AdminProfileController::class, 'updateInfo'])->name('info.update');
            Route::patch('/password', [AdminProfileController::class, 'updatePassword'])->name('password.update');
            Route::get('/orders', [AdminProfileController::class, 'orders'])->name('orders');
            Route::delete('/delete', [AdminProfileController::class, 'destroy'])->name('delete');
        });

        // Products
        Route::get('products/api', [AdminProductController::class, 'indexApi'])->name('products.api');
        Route::resource('products', AdminProductController::class);
        Route::patch('products/{product}/toggle', [AdminProductController::class, 'toggle'])->name('products.toggle');
        Route::post('products/bulk-delete', [AdminProductController::class, 'bulkDelete'])->name('products.bulkDelete');

        // Categories
        Route::resource('categories', CategoryController::class);

        // Vouchers
        Route::resource('vouchers', VoucherController::class);
        Route::patch('vouchers/{voucher}/toggle', [VoucherController::class, 'toggle'])->name('vouchers.toggle');

        // Orders
        Route::get('orders', [AdminOrderController::class, 'index'])->name('orders.index');
        Route::get('orders/{id}', [AdminOrderController::class, 'show'])->name('orders.show');
        Route::post('orders/{id}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.updateStatus');

        // Reports
        Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
        Route::get('reports/export/{type}', [ReportController::class, 'export'])->name('reports.export');

        // Reviews
        Route::get('reviews', [App\Http\Controllers\Admin\ReviewController::class, 'index'])->name('reviews.index');
        Route::patch('reviews/{id}/toggle', [App\Http\Controllers\Admin\ReviewController::class, 'toggle'])->name('reviews.toggle');
        Route::delete('reviews/{id}', [App\Http\Controllers\Admin\ReviewController::class, 'destroy'])->name('reviews.destroy');
        Route::get('reviews/{id}', [App\Http\Controllers\Admin\ReviewController::class, 'show'])->name('reviews.show');
    });

require __DIR__.'/auth.php';