<?php

namespace Remix\RefundRequest\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class RefundRequestMedia extends Model
{
    protected $table = 'remix_refund_request_media';

    protected $fillable = ['refund_request_id', 'path', 'type'];

    public function refundRequest(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(RefundRequest::class);
    }

    public function getUrlAttribute(): string
    {
        return Storage::disk('public')->url($this->path);
    }
}
