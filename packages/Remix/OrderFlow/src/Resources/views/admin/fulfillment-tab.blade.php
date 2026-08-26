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

        <div class="flex flex-wrap gap-2 mt-4 items-center">
            @if($currentStatus === \Remix\OrderFlow\Enums\FulfillmentStatus::WAITING_APPROVAL)
                <form action="{{ route('admin.orders.fulfillment.approve', $order->id) }}" method="POST" class="inline-block mr-2">
                    @csrf
                    <button type="submit" style="background-color: #10b981; color: white; padding: 6px 16px; font-weight: 600; border-radius: 6px; border: none; cursor: pointer; transition: background-color 0.2s;" onmouseover="this.style.backgroundColor='#059669'" onmouseout="this.style.backgroundColor='#10b981'" onclick="return confirm('Approve this order?')">
                        Approve Order
                    </button>
                </form>

                <form action="{{ route('admin.orders.fulfillment.reject', $order->id) }}" method="POST" class="inline-flex gap-2 items-center">
                    @csrf
                    <input type="text" name="reason" placeholder="Rejection reason" required class="text-control px-3 py-1.5 rounded-md border dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300 text-sm" style="height: 36px;">
                    <button type="submit" style="background-color: #ef4444; color: white; padding: 6px 16px; font-weight: 600; border-radius: 6px; border: none; cursor: pointer; transition: background-color 0.2s;" onmouseover="this.style.backgroundColor='#dc2626'" onmouseout="this.style.backgroundColor='#ef4444'" onclick="return confirm('Reject this order?')">
                        Reject Order
                    </button>
                </form>
            @elseif($currentStatus === \Remix\OrderFlow\Enums\FulfillmentStatus::PENDING_PROCESS)
                <form action="{{ route('admin.orders.fulfillment.processing', $order->id) }}" method="POST" class="inline-block">
                    @csrf
                    <button type="submit" style="background-color: #3b82f6; color: white; padding: 6px 16px; font-weight: 600; border-radius: 6px; border: none; cursor: pointer; transition: background-color 0.2s;" onmouseover="this.style.backgroundColor='#2563eb'" onmouseout="this.style.backgroundColor='#3b82f6'" onclick="return confirm('Start processing this order?')">
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
                    <button type="submit" style="background-color: #3b82f6; color: white; padding: 6px 16px; font-weight: 600; border-radius: 6px; border: none; cursor: pointer; transition: background-color 0.2s;" onmouseover="this.style.backgroundColor='#2563eb'" onmouseout="this.style.backgroundColor='#3b82f6'" onclick="return confirm('Confirm that the courier has picked up the package?')">
                        Confirm Order Shipped
                    </button>
                </form>
            @elseif($currentStatus === \Remix\OrderFlow\Enums\FulfillmentStatus::WAITING_COMPLETION_CONFIRMATION)
                <form action="{{ route('admin.orders.fulfillment.confirm-completion', $order->id) }}" method="POST" class="inline-block">
                    @csrf
                    <button type="submit" style="background-color: #10b981; color: white; padding: 6px 16px; font-weight: 600; border-radius: 6px; border: none; cursor: pointer; transition: background-color 0.2s;" onmouseover="this.style.backgroundColor='#059669'" onmouseout="this.style.backgroundColor='#10b981'" onclick="return confirm('Confirm order completion?')">
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
                        <button type="submit" style="background-color: #10b981; color: white; padding: 6px 16px; font-weight: 600; border-radius: 6px; border: none; cursor: pointer; transition: background-color 0.2s;" onmouseover="this.style.backgroundColor='#059669'" onmouseout="this.style.backgroundColor='#10b981'" onclick="return confirm('Force complete this order now?')">
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
            <div style="margin-left: 10px; border-left: 2px solid #e5e7eb; padding-left: 20px; position: relative;">
                @foreach(\Remix\OrderFlow\Models\OrderFulfillmentLog::where('order_id', $order->id)->orderBy('created_at', 'desc')->get() as $log)
                    <div style="position: relative; margin-bottom: 1.5rem;">
                        <!-- Timeline Dot -->
                        <div style="position: absolute; left: -29px; top: 4px; width: 16px; height: 16px; border-radius: 50%; background-color: #2563eb; border: 3px solid #ffffff; box-shadow: 0 0 0 1px #e5e7eb;"></div>
                        
                        <!-- Timeline Content -->
                        <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded shadow-sm" style="padding: 12px;">
                            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 4px;">
                                <div class="font-semibold text-gray-900 dark:text-white" style="font-size: 14px;">
                                    {{ \Remix\OrderFlow\Enums\FulfillmentStatus::from($log->to_status)->label() }}
                                </div>
                                <div class="text-blue-600 dark:text-blue-400" style="font-size: 12px; font-weight: 500;">
                                    {{ $log->created_at->format('d M Y, H:i') }}
                                </div>
                            </div>
                            
                            @if($log->note)
                                <div class="text-gray-600 dark:text-gray-400" style="font-size: 13px; margin-bottom: 4px;">
                                    {{ $log->note }}
                                </div>
                            @endif
                            
                            <div class="text-gray-400 dark:text-gray-500" style="font-size: 11px;">
                                By: {{ ucfirst($log->changed_by_type) }}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
