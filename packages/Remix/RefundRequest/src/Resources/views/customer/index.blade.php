<x-shop::layouts.account>
    <x-slot:title>
        Daftar Refund Request
    </x-slot>

    <div class="refund-requests-list">
        <h2 class="text-2xl font-semibold mb-4">Daftar Refund Request</h2>

        @if($refundRequests->isEmpty())
            <p>Belum ada refund request.</p>
        @else
            <div class="overflow-x-auto">
                <table class="w-full border-collapse border border-gray-200">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="border border-gray-200 px-4 py-2 text-left">ID</th>
                            <th class="border border-gray-200 px-4 py-2 text-left">Order ID</th>
                            <th class="border border-gray-200 px-4 py-2 text-left">Status</th>
                            <th class="border border-gray-200 px-4 py-2 text-left">Tanggal Pengajuan</th>
                            <th class="border border-gray-200 px-4 py-2 text-left">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($refundRequests as $request)
                            <tr>
                                <td class="border border-gray-200 px-4 py-2">#{{ $request->id }}</td>
                                <td class="border border-gray-200 px-4 py-2">#{{ $request->order_id }}</td>
                                <td class="border border-gray-200 px-4 py-2">{{ ucfirst($request->status) }}</td>
                                <td class="border border-gray-200 px-4 py-2">{{ $request->created_at->format('d M Y') }}</td>
                                <td class="border border-gray-200 px-4 py-2">
                                    <a href="{{ route('remix.customer.refund-requests.show', $request->id) }}" class="text-blue-600 hover:underline">Detail</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ $refundRequests->links() }}
            </div>
        @endif
    </div>
</x-shop::layouts.account>
