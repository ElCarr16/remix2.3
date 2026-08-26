<?php

namespace Remix\OrderFlow\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use Webkul\Sales\Repositories\OrderRepository;
use Remix\OrderFlow\Enums\FulfillmentStatus;
use Remix\OrderFlow\Models\OrderFulfillmentLog;

class OrderFulfillmentController extends Controller
{
    public function __construct(protected OrderRepository $orderRepository)
    {
    }

    public function markCompleted($id)
    {
        $order = $this->orderRepository->findOrFail($id);

        if ($order->customer_id !== auth()->guard('customer')->id()) {
            abort(403);
        }

        if ($order->fulfillment_status !== FulfillmentStatus::SHIPPED->value) {
            session()->flash('error', 'Order has not been shipped yet, cannot be confirmed.');
            return redirect()->back();
        }

        $order->fulfillment_status = FulfillmentStatus::COMPLETED->value;
        $order->save();

        OrderFulfillmentLog::create([
            'order_id' => $order->id,
            'from_status' => FulfillmentStatus::SHIPPED->value,
            'to_status' => FulfillmentStatus::COMPLETED->value,
        ]);

        session()->flash('success', 'Order has been marked as received. Thank you!');
        return redirect()->back();
    }
}
