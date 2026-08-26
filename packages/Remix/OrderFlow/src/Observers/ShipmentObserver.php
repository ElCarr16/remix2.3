<?php

namespace Remix\OrderFlow\Observers;

use Remix\OrderFlow\Enums\FulfillmentStatus;
use Remix\OrderFlow\Services\OrderFulfillmentService;
use Webkul\Sales\Models\Shipment;

class ShipmentObserver
{
    public function __construct(protected OrderFulfillmentService $fulfillment) {}

    public function created(Shipment $shipment): void
    {
        $order = $shipment->order;
        if ($order->fulfillment_status !== FulfillmentStatus::PROCESSING->value) {
            return;
        }

        $fulfillment = app(OrderFulfillmentService::class);
        $fulfillment->transition(
            $order,
            FulfillmentStatus::WAITING_COURIER_PICKUP,
            'system',
            null,
            "Shipment created: {$shipment->carrier_title} ({$shipment->track_number})"
        );

        $order->courier_name = $shipment->carrier_title;
        $order->courier_tracking_number = $shipment->track_number;
        $order->save();
    }
}
