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
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded shadow-sm text-sm" onclick="return confirm('Are you sure you have received the order?');">
                    Order Received
                </button>
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
        <h4 class="text-sm font-semibold text-gray-800 mb-3">Order Timeline</h4>
        <div class="flex flex-col space-y-3 relative before:absolute before:inset-0 before:ml-2.5 before:-translate-x-px md:before:mx-0 md:before:translate-x-0 before:h-full before:w-0.5 before:bg-gradient-to-b before:from-transparent before:via-zinc-300 before:to-transparent">
            @foreach(\Remix\OrderFlow\Models\OrderFulfillmentLog::where('order_id', $order->id)->orderBy('created_at', 'desc')->get() as $log)
                <div class="relative flex items-center justify-between md:justify-normal group is-active">
                    <div class="flex items-center justify-center w-5 h-5 rounded-full border border-white bg-zinc-200 group-[.is-active]:bg-blue-600 text-slate-500 shadow shrink-0 md:order-1 md:translate-x-0 ml-[1px]">
                    </div>
                    <div class="w-[calc(100%-2rem)] md:ml-4 bg-zinc-50 border border-zinc-200 p-3 rounded-lg shadow-sm">
                        <div class="flex items-center justify-between space-x-2 mb-1">
                            <div class="font-semibold text-zinc-800 text-sm">
                                {{ \Remix\OrderFlow\Enums\FulfillmentStatus::from($log->to_status)->label() }}
                            </div>
                            <time class="font-medium text-xs text-blue-600">{{ $log->created_at->format('d M Y, H:i') }}</time>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
