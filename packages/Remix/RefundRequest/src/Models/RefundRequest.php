<?php

namespace Remix\RefundRequest\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\Sales\Models\Order;
use Webkul\Sales\Models\Invoice;
use Webkul\Sales\Models\Refund;
use Webkul\Customer\Models\Customer;

class RefundRequest extends Model
{
    protected $table = 'remix_refund_requests';

    protected $fillable = [
        'order_id', 'invoice_id', 'customer_id', 'status',
        'name', 'phone', 'address', 'reason_id', 'other_reason_text', 'description',
        'admin_note', 'requested_amount', 'approved_amount', 'refund_id',
        'midtrans_refund_id', 'midtrans_status', 'agreement_accepted_at',
        'approved_at', 'processed_at',
    ];

    protected $casts = [
        'requested_amount'      => 'decimal:4',
        'approved_amount'       => 'decimal:4',
        'agreement_accepted_at' => 'datetime',
        'approved_at'           => 'datetime',
        'processed_at'          => 'datetime',
    ];

    public function order(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function invoice(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

    public function customer(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function refund(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Refund::class);
    }

    public function items(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(RefundRequestItem::class);
    }

    public function media(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(RefundRequestMedia::class);
    }

    public function reasonOption(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(RefundReason::class, 'reason_id');
    }
}
