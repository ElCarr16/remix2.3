<?php

namespace Remix\RefundRequest\Services;

use Webkul\Sales\Models\Order;

class RefundEligibilityService
{
    /** Durasi window konfirmasi (hari). Fix sesuai keputusan bisnis saat ini. */
    public const CONFIRM_WINDOW_DAYS = 5;

    public function canRequestRefund(Order $order): bool
    {
        $delivery = $order->remixDelivery;

        // Belum ditandai terkirim oleh admin -> belum bisa refund lewat alur ini
        if (! $delivery || ! $delivery->delivered_at) {
            return false;
        }

        // Sudah dikonfirmasi (manual oleh customer ATAU auto oleh system) -> window lewat
        if ($delivery->confirmed_at) {
            return false;
        }

        // Safety net kalau scheduler auto-confirm telat jalan
        if ($delivery->confirm_deadline_at && now()->greaterThan($delivery->confirm_deadline_at)) {
            return false;
        }

        // Cegah double request selama masih ada yang aktif diproses
        return ! $order->remixRefundRequests()
            ->whereIn('status', ['pending', 'processing'])
            ->exists();
    }
}
