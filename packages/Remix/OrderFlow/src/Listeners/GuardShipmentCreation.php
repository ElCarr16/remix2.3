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
                'fulfillment_status' => 'Pesanan harus berstatus "Sedang Diproses Admin" sebelum bisa dibuatkan resi/shipment.',
            ]);
        }
        
        // Ensure single shipment (Decision #5)
        if ($order->shipments()->exists()) {
            throw ValidationException::withMessages([
                'fulfillment_status' => 'Pesanan ini sudah memiliki pengiriman (shipment). Hanya 1 pengiriman yang diizinkan per pesanan.',
            ]);
        }
    }
}
