<?php

use App\Http\Controllers\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Customer;
use App\Http\Controllers\Admin;
use App\Models\Meja;

Route::get('/', function () {
    $tableNumber = Meja::where('is_active', true)->inRandomOrder()->first()->table_number;
    return redirect()->route('scan.table', ['table_number' => $tableNumber]);
});

Route::get('profile', function () {
    $tableNumber = session('table_number');
    $table = Meja::where('table_number', $tableNumber)->first();
    return view('customer.profile', compact('table'));
})->name('customer.profile');

// Route::middleware(['web', 'setOrderDraft'])->group(function () {
//     Route::get('/menu/{token}', [Customer\MenuController::class, 'index'])
//         ->middleware('validateTable')
//         ->name('customer.menu');

//     Route::post('/cart/add', [Customer\CartController::class, 'add'])
//         ->name('cart.add');
//     Route::get('/cart/summary', [Customer\CartController::class, 'summary'])
//         ->name('cart.summary');

//     Route::get('/order', [Customer\OrderController::class, 'index'])
//         ->name('order.index');
// });

Route::get('/scan/{table_number}', [Customer\ScanController::class, 'handle'])
    ->name('scan.table');

Route::middleware(['web', 'ensureTableSession', 'setOrderDraft'])->group(function () {
    Route::get('/menu', [Customer\MenuController::class, 'index'])
        ->name('customer.menu');

    Route::get('/cart/summary', [Customer\CartController::class, 'summary'])
        ->name('cart.summary');
    Route::get('/cart/items', [Customer\CartController::class, 'items']);
    Route::post('/cart/add', [Customer\CartController::class, 'add'])
        ->name('cart.add');
    Route::post('/cart/update', [Customer\CartController::class, 'update']);

    Route::get('/order', [Customer\OrderController::class, 'index'])
        ->name('order.index');
    Route::get('/order/summary', [Customer\OrderController::class, 'orderSummary']);
    Route::post('/order/note', [Customer\OrderController::class, 'updateNote'])
        ->name('order.note');

    Route::get('/payment', [Customer\PaymentController::class, 'index'])
        ->name('payment.index');
    Route::get('/payment/cash/{qrToken}', [Customer\PaymentController::class, 'cash'])
        ->name('payment.cash');
    Route::post('/payment', [Customer\PaymentController::class, 'store'])
        ->name('payment.store');

    Route::get('/payment/failed/{order}', function ($orderId) {
        return view('payment.failed');
    })->name('payment.failed');
});

Route::get(
    '/order/cash/{token}/status',
    [Customer\PaymentController::class, 'status']
)->name('order.cash.status');

Route::get('/order-summary/{order}', [Customer\OrderController::class, 'show'])
    ->name('order.summary');

// Cashier Routes
Route::middleware('web')->group(function () {
    Route::get('/admin/auth', [Auth\AuthController::class, 'create'])
        ->name('login');

    Route::post('/admin/auth', [Auth\AuthController::class, 'store']);

    Route::middleware(['auth'])->prefix('admin')->group(function () {
        Route::name('admin.')->group(function () {
            Route::get('/dashboard', [Admin\DashboardController::class, 'index'])
                ->name('dashboard');

            Route::get('/notifications/check', [Admin\NotificationController::class, 'check'])
                ->name('notifications.check');

            Route::resource('/categories', Admin\CategoryController::class);

            Route::resource('/menus', Admin\MenuController::class);

            Route::middleware('roles:cashier')->group(function () {
                Route::get('/orders', [Admin\OrderController::class, 'index'])
                    ->name('orders.index');

                Route::get('/orders/list', [Admin\OrderController::class, 'orderList'])
                    ->name('orders.list');

                Route::get('/orders/{order}', [Admin\OrderController::class, 'show'])
                    ->name('orders.show');

                Route::get('/scan', [Admin\OrderController::class, 'scan'])
                    ->name('orders.scan');

                Route::post('/scan', [Admin\OrderController::class, 'process'])
                    ->name('orders.process');

                Route::post('/orders/{order}/confirm-payment', [Admin\OrderController::class, 'confirmPayment'])
                    ->name('orders.confirm-payment');

                Route::get('/orders/{order}/receipt', [Admin\OrderController::class, 'receipt'])
                    ->name('orders.receipt');
            });
        });

        Route::post('/logout', [Auth\AuthController::class, 'destroy'])
            ->name('logout');
    });
});

// Route::get('/cart/snackbar', [Customer\CartController::class, 'snackbar'])
//     ->name('cart.snackbar');
