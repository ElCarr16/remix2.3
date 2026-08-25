<?php

namespace Remix\OrderFlow\Listeners;

use Remix\OrderFlow\Enums\FulfillmentStatus;
use Illuminate\Validation\ValidationException;

class GuardShipmentCreation
{
    public function handle($order): void
    {
        if ($order->fulfillment_status !== FulfillmentStatus::WAITING_COURIER_PICKUP->value) {
            throw ValidationException::withMessages([
                'fulfillment_status' => 'Pesanan harus berstatus "Menunggu Pickup Kurir" sebelum bisa dibuatkan Shipment. Klik "Siap Kirim" dulu di tab Fulfillment.',
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
