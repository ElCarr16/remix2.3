<?php

namespace Remix\RefundRequest\Console\Commands;

use Illuminate\Console\Command;
use Remix\RefundRequest\Models\OrderDelivery;
use Webkul\Sales\Repositories\OrderRepository;

class AutoConfirmDeliveredOrders extends Command
{
    protected $signature = 'remix:auto-confirm-orders';

    protected $description = 'Auto-confirm pesanan yang lewat batas waktu konfirmasi penerima (mirip Shopee/Tokopedia)';

    public function handle(OrderRepository $orderRepository): int
    {
        $deliveries = OrderDelivery::whereNull('confirmed_at')
            ->whereNotNull('confirm_deadline_at')
            ->where('confirm_deadline_at', '<=', now())
            ->get();

        foreach ($deliveries as $delivery) {
            $delivery->update([
                'confirmed_at' => now(),
                'confirmed_by' => 'system',
            ]);

            $order = $orderRepository->find($delivery->order_id);
            $order?->update(['status' => 'completed']); // sesuaikan dgn konvensi status Bagisto kamu

            $this->info("Order #{$delivery->order_id} auto-confirmed (deadline lewat).");
        }

        $this->info("Selesai. Total order auto-confirmed: {$deliveries->count()}");

        return self::SUCCESS;
    }
}
