<?php

use Illuminate\Support\Facades\Route;
use Remix\OrderFlow\Http\Controllers\Admin\OrderFulfillmentController;

Route::group([
    'prefix' => config('app.admin_url', 'admin'),
    'middleware' => ['web', 'admin'],
], function () {
    Route::post('orders/{id}/fulfillment/approve', [OrderFulfillmentController::class, 'approve'])
        ->name('admin.orders.fulfillment.approve')
        ->middleware('bouncer:sales.orders.edit');

    Route::post('orders/{id}/fulfillment/reject', [OrderFulfillmentController::class, 'reject'])
        ->name('admin.orders.fulfillment.reject')
        ->middleware('bouncer:sales.orders.edit');

    Route::post('orders/{id}/fulfillment/processing', [OrderFulfillmentController::class, 'markProcessing'])
        ->name('admin.orders.fulfillment.processing')
        ->middleware('bouncer:sales.orders.edit');

    Route::post('orders/{id}/fulfillment/waiting-pickup', [OrderFulfillmentController::class, 'markWaitingPickup'])
        ->name('admin.orders.fulfillment.waiting-pickup')
        ->middleware('bouncer:sales.orders.edit');

    Route::post('orders/{id}/fulfillment/confirm-completion', [OrderFulfillmentController::class, 'confirmCompletion'])
        ->name('admin.orders.fulfillment.confirm-completion')
        ->middleware('bouncer:sales.orders.edit');
});
