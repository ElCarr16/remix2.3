@php
    $currentStatus = \Remix\OrderFlow\Enums\FulfillmentStatus::from($order->fulfillment_status);
@endphp

<div class="bg-white dark:bg-gray-900 rounded box-shadow">
    <div class="p-4">
        <p class="mb-4 text-base text-gray-800 dark:text-white font-semibold">
            Order Fulfillment Status: 
            <span class="text-blue-600 dark:text-blue-400">{{ $currentStatus->label() }}</span>
        </p>

        @if (session('success'))
            <div class="p-4 mb-4 text-sm text-green-700 bg-green-100 dark:bg-green-900 dark:text-green-300 rounded-lg" role="alert">
                {{ session('success') }}
            </div>
        @endif
        @if ($errors->any())
            <div class="p-4 mb-4 text-sm text-red-700 bg-red-100 dark:bg-red-900 dark:text-red-300 rounded-lg" role="alert">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="flex flex-wrap gap-2 mt-4">
            @if($currentStatus === \Remix\OrderFlow\Enums\FulfillmentStatus::WAITING_APPROVAL)
                <form action="{{ route('admin.orders.fulfillment.approve', $order->id) }}" method="POST" class="inline-block">
                    @csrf
                    <button type="submit" class="transparent-button hover:bg-gray-200 dark:hover:bg-gray-800 dark:text-white" onclick="return confirm('Approve this order?')">
                        Approve Order
                    </button>
                </form>

                <form action="{{ route('admin.orders.fulfillment.reject', $order->id) }}" method="POST" class="inline-flex gap-2 items-center">
                    @csrf
                    <input type="text" name="reason" placeholder="Rejection reason" required class="text-control px-3 py-1.5 rounded-md border dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300 text-sm">
                    <button type="submit" class="text-red-600 font-semibold px-2 py-1.5 hover:bg-red-50 dark:hover:bg-red-900 rounded-md transition-all" onclick="return confirm('Reject this order?')">
                        Reject Order
                    </button>
                </form>
            @elseif($currentStatus === \Remix\OrderFlow\Enums\FulfillmentStatus::PENDING_PROCESS)
                <form action="{{ route('admin.orders.fulfillment.processing', $order->id) }}" method="POST" class="inline-block">
                    @csrf
                    <button type="submit" class="transparent-button hover:bg-gray-200 dark:hover:bg-gray-800 dark:text-white" onclick="return confirm('Start processing this order?')">
                        Process Order
                    </button>
                </form>
            @elseif($currentStatus === \Remix\OrderFlow\Enums\FulfillmentStatus::PROCESSING)
                <div class="text-sm text-gray-600 dark:text-gray-300 p-3 bg-yellow-50 dark:bg-yellow-900/30 rounded border border-yellow-200 dark:border-yellow-800 w-full">
                    Order is being processed. Please pack the items and create a <strong>Shipment</strong> (using the Ship button at the top) to input the tracking number. The status will then automatically update to <em>Waiting for Courier Pickup</em>.
                </div>
            @elseif($currentStatus === \Remix\OrderFlow\Enums\FulfillmentStatus::WAITING_COURIER_PICKUP)
                <form action="{{ route('admin.orders.fulfillment.shipped', $order->id) }}" method="POST" class="inline-block">
                    @csrf
                    <button type="submit" class="transparent-button hover:bg-gray-200 dark:hover:bg-gray-800 dark:text-white" onclick="return confirm('Confirm that the courier has picked up the package?')">
                        Confirm Order Shipped
                    </button>
                </form>
            @elseif($currentStatus === \Remix\OrderFlow\Enums\FulfillmentStatus::WAITING_COMPLETION_CONFIRMATION)
                <form action="{{ route('admin.orders.fulfillment.confirm-completion', $order->id) }}" method="POST" class="inline-block">
                    @csrf
                    <button type="submit" class="transparent-button hover:bg-gray-200 dark:hover:bg-gray-800 dark:text-white" onclick="return confirm('Confirm order completion?')">
                        Confirm Completion
                    </button>
                </form>
            @elseif($currentStatus === \Remix\OrderFlow\Enums\FulfillmentStatus::SHIPPED)
                <div class="flex gap-4 items-center">
                    <div class="text-sm text-blue-600 dark:text-blue-400 p-3 bg-blue-50 dark:bg-blue-900/30 rounded border border-blue-200 dark:border-blue-800">
                        Order has been shipped. Waiting for the customer to receive the package.
                    </div>
                    <form action="{{ route('admin.orders.fulfillment.confirm-completion', $order->id) }}" method="POST" class="inline-block">
                        @csrf
                        <button type="submit" class="transparent-button hover:bg-gray-200 dark:hover:bg-gray-800 dark:text-white" onclick="return confirm('Force complete this order now?')">
                            Mark as Completed
                        </button>
                    </form>
                </div>
            @elseif($currentStatus === \Remix\OrderFlow\Enums\FulfillmentStatus::COMPLETED)
                <div class="text-sm text-green-600 font-semibold">Order Completed</div>
            @elseif($currentStatus === \Remix\OrderFlow\Enums\FulfillmentStatus::REJECTED)
                <div class="text-sm text-red-600">Order Rejected (Reason: {{ $order->admin_rejection_reason }})</div>
            @else
                <div class="text-sm text-gray-500">Waiting for payment...</div>
            @endif
        </div>

        <div class="mt-8 border-t dark:border-gray-800 pt-4">
            <h4 class="mb-4 text-sm text-gray-800 dark:text-white font-semibold">Fulfillment Timeline</h4>
            <div class="flex flex-col space-y-3 relative before:absolute before:inset-0 before:ml-2.5 before:-translate-x-px md:before:mx-0 md:before:translate-x-0 before:h-full before:w-0.5 before:bg-gradient-to-b before:from-transparent before:via-slate-300 dark:before:via-slate-700 before:to-transparent">
                @foreach(\Remix\OrderFlow\Models\OrderFulfillmentLog::where('order_id', $order->id)->orderBy('created_at', 'desc')->get() as $log)
                    <div class="relative flex items-center justify-between md:justify-normal group is-active">
                        <div class="flex items-center justify-center w-5 h-5 rounded-full border border-white dark:border-gray-900 bg-slate-300 dark:bg-slate-700 group-[.is-active]:bg-blue-600 text-slate-500 group-[.is-active]:text-white shadow shrink-0 md:order-1 md:translate-x-0 ml-[1px]">
                        </div>
                        <div class="w-[calc(100%-2rem)] md:ml-4 bg-white dark:bg-gray-800 border border-slate-200 dark:border-gray-700 p-3 rounded shadow-sm">
                            <div class="flex items-center justify-between space-x-2 mb-1">
                                <div class="font-bold text-slate-900 dark:text-gray-100 text-sm">
                                    {{ \Remix\OrderFlow\Enums\FulfillmentStatus::from($log->to_status)->label() }}
                                </div>
                                <time class="font-medium text-xs text-blue-600 dark:text-blue-400">{{ $log->created_at->format('d M Y, H:i') }}</time>
                            </div>
                            @if($log->note)
                                <div class="text-slate-500 dark:text-slate-400 text-xs">{{ $log->note }}</div>
                            @endif
                            <div class="text-slate-400 dark:text-slate-500 text-[10px] mt-1">By: {{ ucfirst($log->changed_by_type) }}</div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
