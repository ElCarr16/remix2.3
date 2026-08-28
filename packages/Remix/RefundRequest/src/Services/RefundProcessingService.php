<?php

namespace Remix\RefundRequest\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Remix\RefundRequest\Models\RefundRequest;
use Webkul\Sales\Repositories\RefundRepository;

class RefundProcessingService
{
    public function __construct(
        protected RefundRepository $refundRepository
    ) {}

    /**
     * Admin approve refund request.
     */
    public function approve(RefundRequest $refundRequest, float $approvedAmount, ?string $adminNote = null): RefundRequest
    {
        return DB::transaction(function () use ($refundRequest, $approvedAmount, $adminNote) {
            // 1. Buat refund resmi Bagisto (masuk laporan sales & stock balik otomatis)
            $bagistoRefund = $this->createBagistoRefund($refundRequest);

            // 2. Panggil Midtrans refund API ke transaksi asal
            $midtransResponse = $this->refundToMidtrans($refundRequest->order, $approvedAmount, $adminNote);

            // 3. Update record request custom
            $refundRequest->update([
                'status'             => 'processing',
                'approved_amount'    => $approvedAmount,
                'admin_note'         => $adminNote,
                'refund_id'          => $bagistoRefund->id,
                'midtrans_refund_id' => $midtransResponse['refund_key'] ?? null,
                'midtrans_status'    => $midtransResponse['status'] ?? 'pending',
                'approved_at'        => now(),
            ]);

            return $refundRequest->fresh();
        });
    }

    public function reject(RefundRequest $refundRequest, string $adminNote): RefundRequest
    {
        $refundRequest->update([
            'status'      => 'rejected',
            'admin_note'  => $adminNote,
            'approved_at' => now(),
        ]);

        return $refundRequest;
    }

    protected function createBagistoRefund(RefundRequest $refundRequest)
    {
        $items = [];

        foreach ($refundRequest->items as $requestItem) {
            $items[$requestItem->order_item_id] = $requestItem->qty;
        }

        return $this->refundRepository->create([
            'order_id'   => $refundRequest->order_id,
            'refund'     => [
                'items'             => $items,
                'shipping'          => $refundRequest->order->base_shipping_invoiced,
                'adjustment_refund' => 0,
                'adjustment_fee'    => 0,
            ],
        ]);
    }

    /**
     * Docs: https://docs.midtrans.com/reference/refund-transaction
     */
    protected function refundToMidtrans($order, float $amount, ?string $reason): array
    {
        $midtransOrderId = $order->increment_id; // sesuaikan dgn order_id yang dikirim saat charge

        $serverKey = config('services.midtrans.server_key');
        $baseUrl   = config('services.midtrans.api_url');

        $response = Http::withBasicAuth($serverKey, '')
            ->acceptJson()
            ->post("{$baseUrl}/v2/{$midtransOrderId}/refund", [
                'refund_key' => 'refund-' . $order->id . '-' . now()->timestamp,
                'amount'     => (int) $amount,
                'reason'     => $reason ?? 'Customer refund request',
            ]);

        if ($response->failed()) {
            Log::error('Midtrans refund failed', [
                'order_id' => $order->id,
                'response' => $response->body(),
            ]);

            throw new \RuntimeException('Gagal memproses refund ke Midtrans: ' . $response->body());
        }

        return $response->json();
    }
}
