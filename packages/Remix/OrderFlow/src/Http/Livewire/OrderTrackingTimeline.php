<?php

namespace Remix\OrderFlow\Http\Livewire;

use Livewire\Component;
use Remix\OrderFlow\Enums\FulfillmentStatus;
use Remix\OrderFlow\Models\OrderFulfillmentLog;
use Remix\OrderFlow\Services\OrderFulfillmentService;
use Webkul\Sales\Repositories\OrderRepository;

class OrderTrackingTimeline extends Component
{
    public int $orderId;

    public function requestCompletion(OrderFulfillmentService $fulfillment, OrderRepository $orderRepository)
    {
        $order = $orderRepository->findOrFail($this->orderId);

        // Guard kepemilikan order oleh customer yang sedang login.
        abort_unless($order->customer_id === auth()->guard('customer')->id(), 403);

        $fulfillment->transition($order, FulfillmentStatus::WAITING_COMPLETION_CONFIRMATION, 'customer');

        $this->dispatch('order-completion-requested');
    }

    public function render()
    {
        $order = app(OrderRepository::class)->findOrFail($this->orderId);

        $logs = OrderFulfillmentLog::where('order_id', $this->orderId)
            ->orderBy('created_at')
            ->get();

        return view('order-flow::shop.livewire.order-tracking-timeline', [
            'order' => $order,
            'currentStatus' => FulfillmentStatus::from($order->fulfillment_status),
            'logs' => $logs,
            'allStatuses' => FulfillmentStatus::cases(),
        ]);
    }
}
