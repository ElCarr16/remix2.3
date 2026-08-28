<?php

use Illuminate\Support\Facades\Route;
use Remix\RefundRequest\Http\Controllers\Admin\{
    RefundRequestController,
    OrderDeliveryController,
    RefundReasonController
};

Route::group([
    'middleware' => ['web', 'admin'],
    'prefix'     => config('app.admin_url'),
], function () {

    // Refund requests
    Route::prefix('refund-requests')->group(function () {
        Route::get('', [RefundRequestController::class, 'index'])
            ->name('remix.admin.refund-requests.index');

        Route::get('{refundRequest}', [RefundRequestController::class, 'show'])
            ->name('remix.admin.refund-requests.show');

        Route::post('{refundRequest}/approve', [RefundRequestController::class, 'approve'])
            ->name('remix.admin.refund-requests.approve');

        Route::post('{refundRequest}/reject', [RefundRequestController::class, 'reject'])
            ->name('remix.admin.refund-requests.reject');
    });

    // Tandai order terkirim
    Route::post('orders/{order}/mark-delivered', [OrderDeliveryController::class, 'markDelivered'])
        ->name('remix.admin.orders.mark-delivered');

    // CRUD alasan refund
    Route::resource('refund-reasons', RefundReasonController::class)
        ->except(['show', 'create', 'edit'])
        ->names('remix.admin.refund-reasons');
});
