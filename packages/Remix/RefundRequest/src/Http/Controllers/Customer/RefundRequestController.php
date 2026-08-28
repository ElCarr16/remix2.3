<?php

namespace Remix\RefundRequest\Http\Controllers\Customer;

use Illuminate\Routing\Controller;
use Remix\RefundRequest\Repositories\RefundRequestRepository;

class RefundRequestController extends Controller
{
    public function __construct(
        protected RefundRequestRepository $refundRequestRepository
    ) {}

    public function index()
    {
        $customerId = auth()->guard('customer')->id();

        $refundRequests = $this->refundRequestRepository
            ->where('customer_id', $customerId)
            ->latest()
            ->paginate(10);

        return view('remix::customer.index', compact('refundRequests'));
    }

    public function show(int $id)
    {
        $refundRequest = $this->refundRequestRepository->findOrFail($id);

        abort_unless($refundRequest->customer_id === auth()->guard('customer')->id(), 403);

        return view('remix::customer.show', compact('refundRequest'));
    }
}
