@php
    $currentStatus = \Remix\OrderFlow\Enums\FulfillmentStatus::from($order->fulfillment_status);
@endphp

<div class="mt-4 mb-4 rounded-lg border border-zinc-200 bg-white p-6 shadow-sm">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-semibold text-gray-800">
            Fulfillment Status: 
            <span class="text-blue-600">{{ $currentStatus->label() }}</span>
        </h3>
        @if($currentStatus === \Remix\OrderFlow\Enums\FulfillmentStatus::SHIPPED)
            <form method="POST" action="{{ route('shop.customers.account.orders.mark_completed', $order->id) }}">
                @csrf
                <x-shop::ui.button
                    type="submit"
                    variant="primary"
                    color="primary"
                    size="sm"
                    onclick="return confirm('Are you sure you have received the order?');"
                >
                    Order Received
                </x-shop::ui.button>
            </form>
        @endif
    </div>

    @if($currentStatus === \Remix\OrderFlow\Enums\FulfillmentStatus::WAITING_COURIER_PICKUP)
        <p class="text-sm text-gray-600 mb-4 bg-yellow-50 p-3 rounded border border-yellow-100">
            Your order is being prepared and is waiting for the courier to pick up the package.
        </p>
    @elseif($currentStatus === \Remix\OrderFlow\Enums\FulfillmentStatus::SHIPPED)
        <p class="text-sm text-gray-600 mb-4 bg-blue-50 p-3 rounded border border-blue-100">
            Your order has been shipped! Tracking Number: <strong>{{ $order->courier_tracking_number ?? '-' }}</strong> ({{ $order->courier_name ?? '-' }})
        </p>
    @endif

    <div class="mt-4 border-t pt-4">
        <h4 class="text-sm font-semibold text-gray-800 mb-4">Order Timeline</h4>
        <div style="margin-left: 10px; border-left: 2px solid #e5e7eb; padding-left: 20px; position: relative;">
            @foreach(\Remix\OrderFlow\Models\OrderFulfillmentLog::where('order_id', $order->id)->orderBy('created_at', 'desc')->get() as $log)
                <div style="position: relative; margin-bottom: 1.5rem;">
                    <!-- Timeline Dot -->
                    <div style="position: absolute; left: -29px; top: 4px; width: 16px; height: 16px; border-radius: 50%; background-color: #2563eb; border: 3px solid #ffffff; box-shadow: 0 0 0 1px #e5e7eb;"></div>
                    
                    <!-- Timeline Content -->
                    <div class="bg-gray-50 border border-gray-200 rounded shadow-sm" style="padding: 12px;">
                        <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 4px;">
                            <div class="font-semibold text-gray-900" style="font-size: 14px;">
                                {{ \Remix\OrderFlow\Enums\FulfillmentStatus::from($log->to_status)->label() }}
                            </div>
                            <div class="text-blue-600" style="font-size: 12px; font-weight: 500;">
                                {{ $log->created_at->format('d M Y, H:i') }}
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
