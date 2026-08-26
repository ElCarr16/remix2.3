<div class="order-tracking-timeline">
    <h3 class="text-lg font-semibold mb-4">Status Pesanan: {{ $currentStatus->label() }}</h3>

    <ol class="space-y-3">
        @foreach ($allStatuses as $status)
            @if ($status === \Remix\OrderFlow\Enums\FulfillmentStatus::REJECTED)
                @continue
            @endif
            <li class="flex items-center gap-3 {{ $status->stepIndex() <= $currentStatus->stepIndex() ? 'text-green-600 font-medium' : 'text-gray-400' }}">
                <span class="w-2 h-2 rounded-full {{ $status->stepIndex() <= $currentStatus->stepIndex() ? 'bg-green-600' : 'bg-gray-300' }}"></span>
                {{ $status->label() }}
            </li>
        @endforeach
    </ol>

    @if ($currentStatus === \Remix\OrderFlow\Enums\FulfillmentStatus::SHIPPED)
        <button
            wire:click="requestCompletion"
            wire:confirm="Konfirmasi bahwa pesanan sudah kamu terima?"
            class="mt-4 px-4 py-2 bg-[#ff0000] text-white rounded-md hover:opacity-90"
        >
            Pesanan Diterima
        </button>
    @endif

    @if ($currentStatus === \Remix\OrderFlow\Enums\FulfillmentStatus::WAITING_COMPLETION_CONFIRMATION)
        <p class="mt-4 text-sm text-gray-500">
            Konfirmasi kamu sudah diterima, menunggu verifikasi akhir dari admin.
        </p>
    @endif
</div>
