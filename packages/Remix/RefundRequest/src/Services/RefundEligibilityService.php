<?php

namespace Remix\RefundRequest\Services;

use Webkul\Sales\Models\Order;

class RefundEligibilityService
{
    /** Durasi window konfirmasi (hari). Fix sesuai keputusan bisnis saat ini. */
    public const CONFIRM_WINDOW_DAYS = 5;

    public function canRequestRefund(Order $order): bool
    {
        // Belum ditandai terkirim oleh admin -> belum bisa refund
        if (! $order->shipped_at) {
            return false;
        }

        // Sudah dikonfirmasi selesai oleh customer -> window refund tertutup
        if ($order->completed_confirmed_at) {
            return false;
        }

        // Hitung deadline otomatis (5 hari setelah dikirim)
        $confirmDeadline = \Carbon\Carbon::parse($order->shipped_at)->addDays(self::CONFIRM_WINDOW_DAYS);
        if (now()->greaterThan($confirmDeadline)) {
            return false;
        }

        // Cegah double request selama masih ada yang aktif diproses
        return ! $order->remixRefundRequests()
            ->whereIn('status', ['pending', 'processing'])
            ->exists();
    }
}
