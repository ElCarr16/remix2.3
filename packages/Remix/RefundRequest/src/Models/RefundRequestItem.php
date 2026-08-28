<?php

namespace Remix\RefundRequest\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\Sales\Models\OrderItem;

class RefundRequestItem extends Model
{
    protected $table = 'remix_refund_request_items';

    protected $fillable = ['refund_request_id', 'order_item_id', 'qty', 'amount'];

    public function refundRequest(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(RefundRequest::class);
    }

    public function orderItem(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(OrderItem::class);
    }
}
