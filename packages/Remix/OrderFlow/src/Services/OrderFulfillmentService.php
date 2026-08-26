<?php

namespace Remix\OrderFlow\Services;

use Remix\OrderFlow\Enums\FulfillmentStatus;
use Remix\OrderFlow\Models\OrderFulfillmentLog;
use Remix\OrderFlow\Mail\FulfillmentStatusChanged;
use Webkul\Sales\Models\Order;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Exception;

class OrderFulfillmentService
{
    public function transition(
        Order $order,
        FulfillmentStatus $to,
        string $changedByType = 'admin',
        ?int $adminId = null,
        ?string $note = null
    ): Order {
        $current = FulfillmentStatus::from($order->fulfillment_status);

        if (! in_array($to, $current->nextAllowed(), true)) {
            throw new Exception(
                "Invalid transition: {$current->label()} -> {$to->label()}"
            );
        }

        DB::transaction(function () use ($order, $current, $to, $changedByType, $adminId, $note) {
            $order->fulfillment_status = $to->value;

            match ($to) {
                FulfillmentStatus::PENDING_PROCESS => $order->approved_at = now(),
                FulfillmentStatus::SHIPPED => $order->shipped_at = now(),
                FulfillmentStatus::WAITING_COMPLETION_CONFIRMATION => $order->completion_requested_at = now(),
                FulfillmentStatus::COMPLETED => $order->completed_confirmed_at = now(),
                FulfillmentStatus::REJECTED => $order->admin_rejection_reason = $note,
                default => null,
            };

            $order->save();

            OrderFulfillmentLog::create([
                'order_id' => $order->id,
                'from_status' => $current->value,
                'to_status' => $to->value,
                'changed_by_type' => $changedByType,
                'changed_by_admin_id' => $adminId,
                'note' => $note,
            ]);
        });

        // Send email notification for specific statuses
        if (in_array($to, [
            FulfillmentStatus::PENDING_PROCESS,
            FulfillmentStatus::SHIPPED,
            FulfillmentStatus::REJECTED,
            FulfillmentStatus::COMPLETED
        ])) {
            try {
                Mail::to($order->customer_email)->queue(new FulfillmentStatusChanged($order, $to, $note));
            } catch (\Exception $e) {
                \Log::error('Failed to send fulfillment status email: ' . $e->getMessage());
            }
        }

        return $order->fresh();
    }

    public function reject(Order $order, int $adminId, string $reason): Order
    {
        $order = $this->transition($order, FulfillmentStatus::REJECTED, 'admin', $adminId, $reason);

        // Try standard cancel first
        $canceled = app(\Webkul\Sales\Repositories\OrderRepository::class)->cancel($order);
        
        // Force the native status to canceled if the standard method fails (e.g. because it was already invoiced by Midtrans)
        if ($order->status !== 'canceled' && $order->status !== 'closed') {
            $order->status = 'canceled';
            $order->save();
        }

        return $order;
    }

    public function markShipped(
        Order $order,
        string $courierName,
        string $trackingNumber,
        ?string $courierCode = null
    ): Order {
        $order->courier_name = $courierName;
        $order->courier_code = $courierCode;
        $order->courier_tracking_number = $trackingNumber;

        return $this->transition(
            $order,
            FulfillmentStatus::SHIPPED,
            'admin',
            null,
            "Courier: {$courierName}, Tracking: {$trackingNumber}"
        );
    }
}
