<?php

namespace Remix\RefundRequest\Repositories;

use Webkul\Core\Eloquent\Repository;
use Remix\RefundRequest\Models\RefundRequest;

class RefundRequestRepository extends Repository
{
    public function model(): string
    {
        return RefundRequest::class;
    }
}
