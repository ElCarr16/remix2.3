<?php

namespace Remix\RefundRequest\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\Sales\Models\Order;

class OrderDelivery extends Model
{
    protected $table = 'remix_order_deliveries';

    protected $fillable = ['order_id', 'delivered_at', 'confirm_deadline_at', 'confirmed_at', 'confirmed_by'];

    protected $casts = [
        'delivered_at'        => 'datetime',
        'confirm_deadline_at' => 'datetime',
        'confirmed_at'        => 'datetime',
    ];

    public function order(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
