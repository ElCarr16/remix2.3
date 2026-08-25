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

        if ($order->fulfillment_status !== FulfillmentStatus::WAITING_COURIER_PICKUP->value) {
            return;
        }

        $this->fulfillment->markShipped(
            $order,
            $shipment->carrier_title ?: ($shipment->carrier_code ?: 'Kurir'),
            $shipment->track_number ?: '-',
            $shipment->carrier_code
        );
    }
}
