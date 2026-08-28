
<x-shop::layouts.account>
    <x-slot:title>
        Status Refund #{{ $refundRequest->id }}
    </x-slot>

    <div class="refund-status">
        <h2 class="text-2xl font-semibold mb-6">Status Refund #{{ $refundRequest->id }}</h2>

        <div class="flex items-center justify-between mb-8">
            <div class="step {{ in_array($refundRequest->status, ['pending','processing','refunded','failed','rejected']) ? 'text-blue-600 font-bold' : 'text-gray-400' }}">
                1. Diajukan
            </div>
            <div class="flex-1 border-t-2 mx-4 {{ in_array($refundRequest->status, ['processing','refunded','failed','rejected']) ? 'border-blue-600' : 'border-gray-200' }}"></div>
            <div class="step {{ in_array($refundRequest->status, ['processing','refunded','failed']) ? 'text-blue-600 font-bold' : 'text-gray-400' }} {{ $refundRequest->status === 'rejected' ? 'text-red-600 font-bold' : '' }}">
                2. {{ $refundRequest->status === 'rejected' ? 'Ditolak' : 'Disetujui Admin' }}
            </div>
            <div class="flex-1 border-t-2 mx-4 {{ in_array($refundRequest->status, ['refunded']) ? 'border-blue-600' : 'border-gray-200' }}"></div>
            <div class="step {{ in_array($refundRequest->status, ['refunded']) ? 'text-blue-600 font-bold' : 'text-gray-400' }}">
                3. Dana Dikembalikan
            </div>
        </div>

        @if($refundRequest->status === 'rejected')
            <p class="text-red-600 bg-red-50 p-4 rounded mb-4">Alasan penolakan: {{ $refundRequest->admin_note }}</p>
        @endif

        @if($refundRequest->midtrans_status)
            <p class="mb-4">Status Midtrans: <span class="font-semibold">{{ $refundRequest->midtrans_status }}</span></p>
        @endif

        <div class="mt-8">
            <h3 class="text-xl font-semibold mb-4">Bukti yang Diupload</h3>
            <div class="flex gap-4 flex-wrap">
                @foreach($refundRequest->media as $file)
                    @if($file->type === 'image')
                        <a href="{{ $file->url }}" target="_blank" class="block border rounded p-1 hover:border-blue-500">
                            <img src="{{ $file->url }}" class="w-32 h-32 object-cover rounded">
                        </a>
                    @else
                        <div class="border rounded p-1">
                            <video src="{{ $file->url }}" class="w-32 h-32" controls></video>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
        
        <div class="mt-8">
            <a href="{{ route('remix.customer.refund-requests.index') }}" class="text-blue-600 hover:underline">&larr; Kembali ke Daftar Refund</a>
        </div>
    </div>
</x-shop::layouts.account>
