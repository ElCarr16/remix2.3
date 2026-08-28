<?php

namespace Remix\RefundRequest\Repositories;

use Webkul\Core\Eloquent\Repository;
use Remix\RefundRequest\Models\RefundReason;

class RefundReasonRepository extends Repository
{
    public function model(): string
    {
        return RefundReason::class;
    }
}
