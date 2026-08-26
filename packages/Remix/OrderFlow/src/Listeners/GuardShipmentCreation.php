<?php

namespace Remix\OrderFlow\Listeners;

use Remix\OrderFlow\Enums\FulfillmentStatus;
use Illuminate\Validation\ValidationException;

class GuardShipmentCreation
{
    public function handle($order): void
    {
        if ($order->fulfillment_status !== FulfillmentStatus::PROCESSING->value) {
            throw ValidationException::withMessages([
                'fulfillment_status' => 'Order must be in "Being Processed by Admin" status before a shipment can be created.',
            ]);
        }
        
        // Ensure single shipment (Decision #5)
        if ($order->shipments()->exists()) {
            throw ValidationException::withMessages([
                'fulfillment_status' => 'This order already has a shipment. Only 1 shipment is allowed per order.',
            ]);
        }
    }
}
