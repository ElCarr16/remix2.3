<?php

use Illuminate\Support\Facades\Route;
use Remix\RefundRequest\Http\Controllers\Customer\RefundRequestController;

Route::group(['middleware' => ['web', 'theme', 'locale', 'customer'], 'prefix' => 'customer'], function () {
    Route::get('refund-requests', [RefundRequestController::class, 'index'])
        ->name('remix.refund-requests.index');

    // Halaman pembungkus wizard Livewire (langkah 1 & 2)
    Route::get('refund-requests/create/{order}', function (int $order) {
        return view('remix::customer.wizard-page', ['orderId' => $order]);
    })->name('remix.refund-requests.create');

    // Halaman status (langkah 3)
    Route::get('refund-requests/{refundRequest}', [RefundRequestController::class, 'show'])
        ->name('remix.refund-requests.show');
});
