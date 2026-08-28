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
        // 5 hari yang lalu
        $deadlineThreshold = now()->subDays(\Remix\RefundRequest\Services\RefundEligibilityService::CONFIRM_WINDOW_DAYS);

        // Ambil order yang sudah dikirim tapi belum dikonfirmasi selesai, dan sudah lewat masa tenggang refund
        $orders = \Webkul\Sales\Models\Order::whereNotNull('shipped_at')
            ->whereNull('completed_confirmed_at')
            ->where('shipped_at', '<=', $deadlineThreshold)
            ->get();

        foreach ($orders as $order) {
            $order->update([
                'completed_confirmed_at' => now(),
                'status' => 'completed',
                'fulfillment_status' => 'completed',
            ]);

            $this->info("Order #{$order->id} auto-confirmed (deadline refund lewat).");
        }

        $this->info("Selesai. Total order auto-confirmed: {$orders->count()}");

        return self::SUCCESS;
    }
}
