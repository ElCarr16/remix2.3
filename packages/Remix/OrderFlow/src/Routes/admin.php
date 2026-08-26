<?php

use Illuminate\Support\Facades\Route;
use Remix\OrderFlow\Http\Controllers\Admin\OrderFulfillmentController;

Route::group([
    'prefix' => config('app.admin_url', 'admin'),
    'middleware' => ['web', 'admin'],
], function () {
    Route::post('orders/{id}/fulfillment/approve', [OrderFulfillmentController::class, 'approve'])
        ->name('admin.orders.fulfillment.approve');

    Route::post('orders/{id}/fulfillment/reject', [OrderFulfillmentController::class, 'reject'])
        ->name('admin.orders.fulfillment.reject');

    Route::post('orders/{id}/fulfillment/processing', [OrderFulfillmentController::class, 'markProcessing'])
        ->name('admin.orders.fulfillment.processing');

    Route::post('orders/{id}/fulfillment/shipped', [OrderFulfillmentController::class, 'markShipped'])
        ->name('admin.orders.fulfillment.shipped');

    Route::post('orders/{id}/fulfillment/confirm-completion', [OrderFulfillmentController::class, 'confirmCompletion'])
        ->name('admin.orders.fulfillment.confirm-completion');
});
