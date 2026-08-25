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

        return redirect()->back()->with('success', 'Pesanan disetujui.');
    }

    public function reject(Request $request, int $orderId)
    {
        $request->validate(['reason' => 'required|string|max:255']);

        $order = $this->orderRepository->findOrFail($orderId);
        $this->fulfillment->reject($order, auth()->guard('admin')->id(), $request->reason);

        return redirect()->back()->with('success', 'Pesanan ditolak.');
    }

    public function markProcessing(int $orderId)
    {
        $order = $this->orderRepository->findOrFail($orderId);
        $this->fulfillment->transition($order, FulfillmentStatus::PROCESSING, 'admin', auth()->guard('admin')->id());

        return redirect()->back()->with('success', 'Pesanan mulai diproses.');
    }

    public function markWaitingPickup(int $orderId)
    {
        $order = $this->orderRepository->findOrFail($orderId);
        $this->fulfillment->transition($order, FulfillmentStatus::WAITING_COURIER_PICKUP, 'admin', auth()->guard('admin')->id());

        return redirect()->back()->with('success', 'Menunggu pickup kurir. Silakan buat Shipment di halaman order.');
    }

    public function confirmCompletion(int $orderId)
    {
        $order = $this->orderRepository->findOrFail($orderId);
        $this->fulfillment->transition($order, FulfillmentStatus::COMPLETED, 'admin', auth()->guard('admin')->id());

        return redirect()->back()->with('success', 'Pesanan ditandai selesai.');
    }
}
