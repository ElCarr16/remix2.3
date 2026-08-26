<?php

namespace Remix\OrderFlow\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\Sales\Models\Order;
use Webkul\Admin\Models\Admin;

class OrderFulfillmentLog extends Model
{
    protected $fillable = [
        'order_id', 'from_status', 'to_status',
        'changed_by_admin_id', 'changed_by_type', 'note',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class, 'changed_by_admin_id');
    }
}
