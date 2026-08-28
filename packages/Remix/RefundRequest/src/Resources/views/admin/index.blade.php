<x-admin::layouts>
    <x-slot:title>
        Refund Requests
    </x-slot>

    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h1>Refund Requests</h1>
            </div>
        </div>

        <div class="page-content">
            @if($refundRequests->isEmpty())
                <p>Belum ada refund request.</p>
            @else
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Order ID</th>
                            <th>Customer</th>
                            <th>Status</th>
                            <th>Tanggal Pengajuan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($refundRequests as $request)
                            <tr>
                                <td>#{{ $request->id }}</td>
                                <td>#{{ $request->order_id }}</td>
                                <td>{{ $request->name }}</td>
                                <td>{{ ucfirst($request->status) }}</td>
                                <td>{{ $request->created_at->format('d M Y H:i') }}</td>
                                <td>
                                    <a href="{{ route('remix.admin.refund-requests.show', $request->id) }}">Lihat</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                {{ $refundRequests->links() }}
            @endif
        </div>
    </div>
</x-admin::layouts>
