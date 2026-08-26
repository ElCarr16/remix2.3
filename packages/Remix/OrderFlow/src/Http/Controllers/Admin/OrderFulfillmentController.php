<?php

namespace Remix\OrderFlow\Http\Controllers\Admin;

use Illuminate\Routing\Controller;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Remix\OrderFlow\Enums\FulfillmentStatus;
use Remix\OrderFlow\Services\OrderFulfillmentService;
use Webkul\Sales\Repositories\OrderRepository;

class OrderFulfillmentController extends Controller
{
    use AuthorizesRequests, ValidatesRequests;

    public function __construct(
        protected OrderFulfillmentService $fulfillment,
        protected OrderRepository $orderRepository
    ) {}

    public function approve(Request $request, int $orderId)
    {
        $order = $this->orderRepository->findOrFail($orderId);
        $this->fulfillment->transition($order, FulfillmentStatus::PENDING_PROCESS, 'admin', auth()->guard('admin')->id());

        return redirect()->back()->with('success', 'Order approved.');
    }

    public function reject(Request $request, int $orderId)
    {
        $request->validate(['reason' => 'required|string|max:255']);

        $order = $this->orderRepository->findOrFail($orderId);
        $this->fulfillment->reject($order, auth()->guard('admin')->id(), $request->reason);

        return redirect()->back()->with('success', 'Order rejected.');
    }

    public function markProcessing(int $orderId)
    {
        $order = $this->orderRepository->findOrFail($orderId);
        $this->fulfillment->transition($order, FulfillmentStatus::PROCESSING, 'admin', auth()->guard('admin')->id());

        return redirect()->back()->with('success', 'Order is now being processed.');
    }

    public function markShipped(int $orderId)
    {
        $order = $this->orderRepository->findOrFail($orderId);
        $this->fulfillment->transition($order, FulfillmentStatus::SHIPPED, 'admin', auth()->guard('admin')->id());

        return redirect()->back()->with('success', 'Order marked as Shipped.');
    }

    public function confirmCompletion(int $orderId)
    {
        $order = $this->orderRepository->findOrFail($orderId);
        $this->fulfillment->transition($order, FulfillmentStatus::COMPLETED, 'admin', auth()->guard('admin')->id());

        return redirect()->back()->with('success', 'Order marked as completed.');
    }
}
