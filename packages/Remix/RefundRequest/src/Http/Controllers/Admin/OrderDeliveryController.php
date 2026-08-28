<?php

namespace Remix\RefundRequest\Http\Controllers\Admin;

use Illuminate\Routing\Controller;
use Remix\RefundRequest\Models\OrderDelivery;
use Remix\RefundRequest\Services\RefundEligibilityService;

class OrderDeliveryController extends Controller
{
    public function markDelivered(int $orderId)
    {
        OrderDelivery::updateOrCreate(
            ['order_id' => $orderId],
            [
                'delivered_at'        => now(),
                'confirm_deadline_at' => now()->addDays(RefundEligibilityService::CONFIRM_WINDOW_DAYS),
            ]
        );

        session()->flash('success', 'Order ditandai terkirim. Auto-confirm dalam '
            . RefundEligibilityService::CONFIRM_WINDOW_DAYS . ' hari kalau customer tidak konfirmasi manual.');

        return redirect()->back();
    }
}
