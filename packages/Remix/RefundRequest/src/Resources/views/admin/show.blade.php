<x-admin::layouts>
    <x-slot:title>
        Detail Refund Request #{{ $refundRequest->id }}
    </x-slot>

    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h1>Detail Refund Request #{{ $refundRequest->id }}</h1>
            </div>
            <div class="page-action">
                <a href="{{ route('remix.admin.refund-requests.index') }}" class="btn btn-lg btn-primary">Kembali</a>
            </div>
        </div>

        <div class="page-content">
            <div class="section">
                <h3>Informasi Umum</h3>
                <p><strong>Order ID:</strong> #{{ $refundRequest->order_id }}</p>
                <p><strong>Status:</strong> {{ ucfirst($refundRequest->status) }}</p>
                <p><strong>Alasan:</strong> {{ $refundRequest->reasonOption ? $refundRequest->reasonOption->label : $refundRequest->other_reason_text }}</p>
                <p><strong>Deskripsi:</strong> {{ $refundRequest->description }}</p>
                <p><strong>Requested Amount:</strong> {{ core()->formatBasePrice($refundRequest->requested_amount) }}</p>
            </div>

            <div class="section">
                <h3>Bukti</h3>
                <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                    @foreach($refundRequest->media as $file)
                        @if($file->type === 'image')
                            <a href="{{ $file->url }}" target="_blank">
                                <img src="{{ $file->url }}" style="width: 150px; height: 150px; object-fit: cover;">
                            </a>
                        @else
                            <video src="{{ $file->url }}" style="width: 150px; height: 150px;" controls></video>
                        @endif
                    @endforeach
                </div>
            </div>

            @if($refundRequest->status === 'pending')
                <div class="section" style="margin-top: 20px;">
                    <h3>Proses Refund</h3>
                    
                    <form action="{{ route('remix.admin.refund-requests.approve', $refundRequest->id) }}" method="POST" style="margin-bottom: 20px;">
                        @csrf
                        <div class="control-group">
                            <label for="approved_amount" class="required">Approved Amount</label>
                            <input type="number" step="0.01" name="approved_amount" class="control" value="{{ $refundRequest->requested_amount }}" required>
                        </div>
                        <div class="control-group">
                            <label for="admin_note">Admin Note (optional)</label>
                            <textarea name="admin_note" class="control"></textarea>
                        </div>
                        <button type="submit" class="btn btn-lg btn-primary" onclick="return confirm('Yakin approve refund ini?')">Approve & Proses Midtrans</button>
                    </form>

                    <form action="{{ route('remix.admin.refund-requests.reject', $refundRequest->id) }}" method="POST">
                        @csrf
                        <div class="control-group">
                            <label for="admin_note" class="required">Alasan Penolakan</label>
                            <textarea name="admin_note" class="control" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-lg btn-danger" onclick="return confirm('Yakin reject refund ini?')">Reject Refund</button>
                    </form>
                </div>
            @endif
        </div>
    </div>
</x-admin::layouts>
