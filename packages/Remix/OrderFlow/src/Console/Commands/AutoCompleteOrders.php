<?php

namespace Remix\OrderFlow\Console\Commands;

use Illuminate\Console\Command;
use Remix\OrderFlow\Enums\FulfillmentStatus;
use Remix\OrderFlow\Services\OrderFulfillmentService;
use Webkul\Sales\Models\Order;

class AutoCompleteOrders extends Command
{
    protected $signature = 'remix:auto-complete-orders {--days=}';
    protected $description = 'Auto-selesaikan pesanan yang sudah shipped tapi tidak dikonfirmasi user dalam N hari';

    public function handle(OrderFulfillmentService $fulfillment): int
    {
        // Use option if provided, else use config, else default to 3
        $days = $this->option('days') ? (int) $this->option('days') : config('order-flow.auto_complete_days', 3);

        $orders = Order::where('fulfillment_status', FulfillmentStatus::SHIPPED->value)
            ->where('shipped_at', '<=', now()->subDays($days))
            ->get();

        foreach ($orders as $order) {
            $fulfillment->transition($order, FulfillmentStatus::WAITING_COMPLETION_CONFIRMATION, 'system', null, 'Auto-transition setelah timeout');
            $fulfillment->transition($order->fresh(), FulfillmentStatus::COMPLETED, 'system', null, "Auto-complete setelah {$days} hari");
        }

        $this->info("Auto-completed {$orders->count()} orders.");

        return self::SUCCESS;
    }
}
