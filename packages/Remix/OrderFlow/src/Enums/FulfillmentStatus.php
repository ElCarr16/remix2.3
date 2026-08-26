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
            self::WAITING_PAYMENT => 'Waiting for Payment',
            self::WAITING_APPROVAL => 'Waiting for Admin Approval',
            self::REJECTED => 'Order Rejected by Admin',
            self::PENDING_PROCESS => 'Waiting to be Processed',
            self::PROCESSING => 'Being Processed by Admin',
            self::WAITING_COURIER_PICKUP => 'Waiting for Courier Pickup',
            self::SHIPPED => 'Order Shipped',
            self::WAITING_COMPLETION_CONFIRMATION => 'Waiting for Completion Confirmation',
            self::COMPLETED => 'Order Completed',
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
            self::SHIPPED => [self::WAITING_COMPLETION_CONFIRMATION, self::COMPLETED],
            self::WAITING_COMPLETION_CONFIRMATION => [self::COMPLETED],
            default => [],
        };
    }

    public function stepIndex(): int
    {
        return array_search($this, self::cases(), true);
    }
}
