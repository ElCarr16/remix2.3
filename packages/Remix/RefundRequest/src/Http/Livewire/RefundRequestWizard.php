<?php

namespace Remix\RefundRequest\Http\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use Webkul\Sales\Repositories\OrderRepository;
use Remix\RefundRequest\Repositories\{RefundRequestRepository, RefundReasonRepository};
use Remix\RefundRequest\Services\RefundEligibilityService;

class RefundRequestWizard extends Component
{
    use WithFileUploads;

    public int $orderId;
    public int $step = 1; // 1 = agreement, 2 = form

    public bool $agreementAccepted = false;

    // Form fields
    public string $name = '';
    public string $phone = '';
    public string $address = '';
    public ?int $reasonId = null;
    public string $otherReasonText = '';
    public string $description = '';
    public array $media = [];

    public $reasons = [];

    protected function rules(): array
    {
        return [
            'name'            => 'required|string|max:150',
            'phone'           => 'required|string|max:20',
            'address'         => 'required|string|max:500',
            'reasonId'        => 'nullable|exists:remix_refund_reasons,id',
            'otherReasonText' => 'required_if:reasonId,null|nullable|string|max:255',
            'description'     => 'required|string|max:1000',
            'media.*'         => 'nullable|file|mimes:jpg,jpeg,png,mp4,mov|max:20480', // 20MB/file
        ];
    }

    public function mount(
        int $orderId,
        OrderRepository $orderRepository,
        RefundEligibilityService $eligibility,
        RefundReasonRepository $reasonRepository
    ): void {
        $order = $orderRepository->findOrFail($orderId);

        abort_unless($order->customer_id === auth()->guard('customer')->id(), 403);
        abort_unless($eligibility->canRequestRefund($order), 403, 'Periode refund untuk pesanan ini sudah berakhir.');

        $this->orderId = $orderId;
        $this->name    = auth()->guard('customer')->user()->name ?? '';
        $this->phone   = auth()->guard('customer')->user()->phone ?? ''; // cek nama kolom asli di tabel customers
        $this->reasons = $reasonRepository->where('is_active', true)->orderBy('sort_order')->get();
    }

    public function acceptAgreement(): void
    {
        $this->validate(['agreementAccepted' => 'accepted'], [
            'agreementAccepted.accepted' => 'Kamu harus menyetujui syarat & ketentuan dulu.',
        ]);

        $this->step = 2;
    }

    public function backToAgreement(): void
    {
        $this->step = 1;
    }

    public function submit(RefundRequestRepository $refundRequestRepository, OrderRepository $orderRepository)
    {
        $this->validate();

        $order = $orderRepository->findOrFail($this->orderId);

        $refundRequest = $refundRequestRepository->create([
            'order_id'              => $order->id,
            'invoice_id'            => $order->invoices->first()?->id,
            'customer_id'           => auth()->guard('customer')->id(),
            'status'                => 'pending',
            'name'                  => $this->name,
            'phone'                 => $this->phone,
            'address'               => $this->address,
            'reason_id'             => $this->reasonId,
            'other_reason_text'     => $this->reasonId ? null : $this->otherReasonText,
            'description'           => $this->description,
            'requested_amount'      => $order->grand_total, // full refund; sesuaikan kalau mau parsial per item
            'agreement_accepted_at' => now(),
        ]);

        foreach ($this->media as $file) {
            $path = $file->store("refund-requests/{$refundRequest->id}", 'public');

            $refundRequest->media()->create([
                'path' => $path,
                'type' => str($file->getMimeType())->startsWith('video') ? 'video' : 'image',
            ]);
        }

        // Langkah 3: redirect ke halaman status
        return redirect()->route('remix.refund-requests.show', $refundRequest->id);
    }

    public function render()
    {
        return view('remix::customer.refund-wizard');
    }
}
