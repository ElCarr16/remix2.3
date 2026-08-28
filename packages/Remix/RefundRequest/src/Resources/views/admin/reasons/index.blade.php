<x-admin::layouts>
    <x-slot:title>
        Refund Reasons
    </x-slot>

    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h1>Refund Reasons</h1>
            </div>
        </div>

        <div class="page-content">
            <form action="{{ route('remix.admin.refund-reasons.store') }}" method="POST" style="margin-bottom: 20px; padding: 15px; border: 1px solid #ccc;">
                @csrf
                <h3>Tambah Alasan Baru</h3>
                <div class="control-group">
                    <label for="label" class="required">Label</label>
                    <input type="text" name="label" class="control" required>
                </div>
                <div class="control-group">
                    <label for="sort_order">Sort Order</label>
                    <input type="number" name="sort_order" class="control" value="0">
                </div>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </form>

            @if($reasons->isEmpty())
                <p>Belum ada alasan refund.</p>
            @else
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Label</th>
                            <th>Status Aktif</th>
                            <th>Sort Order</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($reasons as $reason)
                            <tr>
                                <td>{{ $reason->id }}</td>
                                <td>{{ $reason->label }}</td>
                                <td>{{ $reason->is_active ? 'Ya' : 'Tidak' }}</td>
                                <td>{{ $reason->sort_order }}</td>
                                <td>
                                    <form action="{{ route('remix.admin.refund-reasons.destroy', $reason->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Hapus alasan ini?')">Hapus</button>
                                    </form>
                                    <!-- Form update bisa ditambahkan modal/inline di sini -->
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
</x-admin::layouts>
