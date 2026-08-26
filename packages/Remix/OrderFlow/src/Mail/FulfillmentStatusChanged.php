<?php

namespace Remix\OrderFlow\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Remix\OrderFlow\Enums\FulfillmentStatus;
use Webkul\Sales\Models\Order;

class FulfillmentStatusChanged extends Mailable
{
    use Queueable, SerializesModels;

    public $order;
    public $status;
    public $note;

    public function __construct(Order $order, FulfillmentStatus $status, ?string $note = null)
    {
        $this->order = $order;
        $this->status = $status;
        $this->note = $note;
    }

    public function build()
    {
        return $this->subject('Update Status Pesanan #' . $this->order->increment_id . ' - ' . $this->status->label())
                    ->view('order-flow::emails.customer.status-changed');
    }
}
