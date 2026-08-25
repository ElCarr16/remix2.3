@php
    $currentStatus = \Remix\OrderFlow\Enums\FulfillmentStatus::from($order->fulfillment_status);
@endphp

<div class="px-[16px] py-[8px]">
    <h3 class="font-semibold text-gray-800 text-[16px] mb-4">
        Status Fulfillment: <span class="text-blue-600">{{ $currentStatus->label() }}</span>
    </h3>

    @if (session('success'))
        <div class="p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg" role="alert">
            {{ session('success') }}
        </div>
    @endif
    @if ($errors->any())
        <div class="p-4 mb-4 text-sm text-red-700 bg-red-100 rounded-lg" role="alert">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="mt-4 space-x-2">
        @if($currentStatus === \Remix\OrderFlow\Enums\FulfillmentStatus::WAITING_APPROVAL)
            <form action="{{ route('admin.orders.fulfillment.approve', $order->id) }}" method="POST" class="inline-block">
                @csrf
                <button type="submit" class="primary-button" onclick="return confirm('Setujui pesanan ini?')">Setujui Pesanan</button>
            </form>

            <form action="{{ route('admin.orders.fulfillment.reject', $order->id) }}" method="POST" class="inline-block">
                @csrf
                <input type="text" name="reason" placeholder="Alasan penolakan" required class="border rounded px-2 py-1 text-sm">
                <button type="submit" class="text-red-600 font-semibold px-2 hover:underline" onclick="return confirm('Tolak pesanan ini?')">Tolak Pesanan</button>
            </form>
        @elseif($currentStatus === \Remix\OrderFlow\Enums\FulfillmentStatus::PENDING_PROCESS)
            <form action="{{ route('admin.orders.fulfillment.processing', $order->id) }}" method="POST" class="inline-block">
                @csrf
                <button type="submit" class="primary-button" onclick="return confirm('Mulai proses pesanan?')">Proses Pesanan</button>
            </form>
        @elseif($currentStatus === \Remix\OrderFlow\Enums\FulfillmentStatus::PROCESSING)
            <form action="{{ route('admin.orders.fulfillment.waiting-pickup', $order->id) }}" method="POST" class="inline-block">
                @csrf
                <button type="submit" class="primary-button" onclick="return confirm('Tandai siap kirim?')">Siap Kirim</button>
            </form>
        @elseif($currentStatus === \Remix\OrderFlow\Enums\FulfillmentStatus::WAITING_COURIER_PICKUP)
            <div class="text-sm text-gray-600 p-3 bg-yellow-50 rounded">
                Menunggu Pickup Kurir. Silakan buat Shipment melalui tombol "Ship" di header halaman ini. Status akan otomatis berubah setelah resi diinput.
            </div>
        @elseif($currentStatus === \Remix\OrderFlow\Enums\FulfillmentStatus::WAITING_COMPLETION_CONFIRMATION)
            <form action="{{ route('admin.orders.fulfillment.confirm-completion', $order->id) }}" method="POST" class="inline-block">
                @csrf
                <button type="submit" class="primary-button" onclick="return confirm('Konfirmasi penyelesaian pesanan ini?')">Konfirmasi Selesai</button>
            </form>
        @elseif($currentStatus === \Remix\OrderFlow\Enums\FulfillmentStatus::COMPLETED)
            <div class="text-sm text-green-600 font-semibold">Pesanan Selesai</div>
        @elseif($currentStatus === \Remix\OrderFlow\Enums\FulfillmentStatus::REJECTED)
            <div class="text-sm text-red-600">Pesanan Ditolak (Alasan: {{ $order->admin_rejection_reason }})</div>
        @else
            <div class="text-sm text-gray-500">Menunggu pembayaran...</div>
        @endif
    </div>

    <hr class="my-6">
    <h4 class="font-semibold text-gray-800 text-[14px] mb-3">Timeline Fulfillment</h4>
    <div class="flex flex-col space-y-3 relative before:absolute before:inset-0 before:ml-2.5 before:-translate-x-px md:before:mx-auto md:before:translate-x-0 before:h-full before:w-0.5 before:bg-gradient-to-b before:from-transparent before:via-slate-300 before:to-transparent">
        @foreach(\Remix\OrderFlow\Models\OrderFulfillmentLog::where('order_id', $order->id)->orderBy('created_at', 'desc')->get() as $log)
            <div class="relative flex items-center justify-between md:justify-normal md:odd:flex-row-reverse group is-active">
                <div class="flex items-center justify-center w-5 h-5 rounded-full border border-white bg-slate-300 group-[.is-active]:bg-blue-500 text-slate-500 group-[.is-active]:text-emerald-50 shadow shrink-0 md:order-1 md:group-odd:-translate-x-1/2 md:group-even:translate-x-1/2">
                </div>
                <div class="w-[calc(100%-2rem)] md:w-[calc(50%-1.5rem)] bg-white border border-slate-200 p-3 rounded shadow-sm">
                    <div class="flex items-center justify-between space-x-2 mb-1">
                        <div class="font-bold text-slate-900 text-sm">
                            {{ \Remix\OrderFlow\Enums\FulfillmentStatus::from($log->to_status)->label() }}
                        </div>
                        <time class="font-caveat font-medium text-xs text-blue-500">{{ $log->created_at->format('d M Y, H:i') }}</time>
                    </div>
                    @if($log->note)
                        <div class="text-slate-500 text-xs">{{ $log->note }}</div>
                    @endif
                    <div class="text-slate-400 text-[10px] mt-1">Oleh: {{ ucfirst($log->changed_by_type) }}</div>
                </div>
            </div>
        @endforeach
    </div>
</div>
