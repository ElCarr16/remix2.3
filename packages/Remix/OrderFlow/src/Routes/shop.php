<?php

use Illuminate\Support\Facades\Route;
use Remix\OrderFlow\Http\Controllers\Shop\OrderFulfillmentController;

Route::group(['middleware' => ['web', 'theme', 'locale', 'currency', 'customer']], function () {
    Route::post('customer/account/orders/{id}/mark-completed', [OrderFulfillmentController::class, 'markCompleted'])->name('shop.customers.account.orders.mark_completed');
});
