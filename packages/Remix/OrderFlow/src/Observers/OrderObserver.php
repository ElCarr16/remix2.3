<?php

namespace Remix\OrderFlow\Observers;

use Remix\OrderFlow\Enums\FulfillmentStatus;
use Remix\OrderFlow\Services\OrderFulfillmentService;
use Webkul\Sales\Models\Order;

class OrderObserver
{
    public function __construct(protected OrderFulfillmentService $fulfillment) {}

    public function updated(Order $order): void
    {
        if (! $order->wasChanged('status')) {
            return;
        }

        if ($order->status === Order::STATUS_PROCESSING
            && $order->fulfillment_status === FulfillmentStatus::WAITING_PAYMENT->value
        ) {
            $this->fulfillment->transition($order, FulfillmentStatus::WAITING_APPROVAL, 'system');
        }
    }
}
