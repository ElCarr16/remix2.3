<div>
    @if($step === 1)
        {{-- LANGKAH 1: AGREEMENT --}}
        <div class="refund-agreement">
            <h2>Syarat & Ketentuan Refund</h2>
            <div class="prose text-sm">
                <ol>
                    <li>Refund hanya bisa diajukan sebelum status pesanan "Diterima".</li>
                    <li>Bukti foto/video wajib menunjukkan kondisi produk yang diterima.</li>
                    <li>Proses review admin maksimal 2x24 jam kerja.</li>
                    <li>Dana dikembalikan ke metode pembayaran asal.</li>
                </ol>
            </div>

            <label class="flex items-center gap-2">
                <input type="checkbox" wire:model="agreementAccepted">
                Saya sudah membaca dan menyetujui syarat & ketentuan di atas
            </label>
            @error('agreementAccepted') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror

            <button wire:click="acceptAgreement" class="btn-primary mt-4">Lanjut ke Form</button>
        </div>
    @elseif($step === 2)
        {{-- LANGKAH 2: FORM --}}
        <form wire:submit.prevent="submit" class="space-y-4">
            <div>
                <label>Nama Lengkap</label>
                <input type="text" wire:model="name" class="input">
                @error('name') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
            </div>

            <div>
                <label>Nomor Telepon</label>
                <input type="text" wire:model="phone" class="input">
                @error('phone') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
            </div>

            <div>
                <label>Alamat</label>
                <textarea wire:model="address" class="input"></textarea>
                @error('address') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
            </div>

            <div>
                <label>ID Order</label>
                <input type="text" value="#{{ $orderId }}" disabled class="input bg-gray-100">
            </div>

            <div>
                <label>Alasan Refund</label>
                <select wire:model.live="reasonId" class="input">
                    <option value="">-- Alasan lain (isi manual) --</option>
                    @foreach($reasons as $reason)
                        <option value="{{ $reason->id }}">{{ $reason->label }}</option>
                    @endforeach
                </select>
            </div>

            @if(! $reasonId)
                <div>
                    <label>Sebutkan Alasan Lain</label>
                    <input type="text" wire:model="otherReasonText" class="input">
                    @error('otherReasonText') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
                </div>
            @endif

            <div>
                <label>Deskripsi</label>
                <textarea wire:model="description" class="input" rows="4"
                    placeholder="Jelaskan detail masalahnya..."></textarea>
                @error('description') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
            </div>

            <div>
                <label>Bukti Foto/Video (bisa lebih dari satu)</label>
                <input type="file" wire:model="media" multiple accept="image/*,video/*">
                @error('media.*') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror

                <div wire:loading wire:target="media" class="text-sm text-gray-500">Mengunggah file...</div>
            </div>

            <div class="flex gap-2">
                <button type="button" wire:click="backToAgreement" class="btn-secondary">Kembali</button>
                <button type="submit" class="btn-primary" wire:loading.attr="disabled">
                    Kirim Permintaan Refund
                </button>
            </div>
        </form>
    @endif
</div>
