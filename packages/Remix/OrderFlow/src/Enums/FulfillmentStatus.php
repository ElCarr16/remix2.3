<?php

namespace Remix\OrderFlow\Enums;

enum FulfillmentStatus: string
{
    case WAITING_PAYMENT = 'waiting_payment';
    case WAITING_APPROVAL = 'waiting_approval';
    case REJECTED = 'rejected';
    case PENDING_PROCESS = 'pending_process';
    case PROCESSING = 'processing';
    case WAITING_COURIER_PICKUP = 'waiting_courier_pickup';
    case SHIPPED = 'shipped';
    case WAITING_COMPLETION_CONFIRMATION = 'waiting_completion_confirmation';
    case COMPLETED = 'completed';

    public function label(): string
    {
        return match ($this) {
            self::WAITING_PAYMENT => 'Menunggu Pembayaran',
            self::WAITING_APPROVAL => 'Menunggu Persetujuan Admin',
            self::REJECTED => 'Pesanan Dibatalkan Admin',
            self::PENDING_PROCESS => 'Menunggu Diproses',
            self::PROCESSING => 'Sedang Diproses Admin',
            self::WAITING_COURIER_PICKUP => 'Menunggu Pickup Kurir',
            self::SHIPPED => 'Pesanan Telah Dikirim',
            self::WAITING_COMPLETION_CONFIRMATION => 'Menunggu Konfirmasi Selesai',
            self::COMPLETED => 'Pesanan Selesai',
        };
    }

    public function nextAllowed(): array
    {
        return match ($this) {
            self::WAITING_PAYMENT => [self::WAITING_APPROVAL],
            self::WAITING_APPROVAL => [self::PENDING_PROCESS, self::REJECTED],
            self::PENDING_PROCESS => [self::PROCESSING],
            self::PROCESSING => [self::WAITING_COURIER_PICKUP],
            self::WAITING_COURIER_PICKUP => [self::SHIPPED],
            self::SHIPPED => [self::WAITING_COMPLETION_CONFIRMATION],
            self::WAITING_COMPLETION_CONFIRMATION => [self::COMPLETED],
            default => [],
        };
    }

    public function stepIndex(): int
    {
        return array_search($this, self::cases(), true);
    }
}
