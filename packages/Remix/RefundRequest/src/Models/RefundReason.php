<?php

namespace Remix\RefundRequest\Models;

use Illuminate\Database\Eloquent\Model;

class RefundReason extends Model
{
    protected $table = 'remix_refund_reasons';

    protected $fillable = ['label', 'is_active', 'sort_order'];

    protected $casts = ['is_active' => 'boolean'];
}
