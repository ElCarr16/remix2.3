<?php

namespace Remix\RefundRequest\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Webkul\Admin\Http\Controllers\Controller;
use Remix\RefundRequest\Repositories\RefundRequestRepository;
use Remix\RefundRequest\Services\RefundProcessingService;

class RefundRequestController extends Controller
{
    public function __construct(
        protected RefundRequestRepository $refundRequestRepository,
        protected RefundProcessingService $refundProcessingService
    ) {}

    public function index()
    {
        $refundRequests = $this->refundRequestRepository->latest()->paginate(15);

        return view('remix::admin.index', compact('refundRequests'));
    }

    public function show(int $id)
    {
        $refundRequest = $this->refundRequestRepository
            ->with(['order', 'items.orderItem', 'customer', 'media', 'reasonOption'])
            ->findOrFail($id);

        return view('remix::admin.show', compact('refundRequest'));
    }

    public function approve(Request $request, int $id)
    {
        $validated = $request->validate([
            'approved_amount' => 'required|numeric|min:0.01',
            'admin_note'      => 'nullable|string|max:1000',
        ]);

        $refundRequest = $this->refundRequestRepository->findOrFail($id);

        abort_if($refundRequest->status !== 'pending', 422, 'Request ini sudah diproses.');

        try {
            $this->refundProcessingService->approve(
                $refundRequest,
                (float) $validated['approved_amount'],
                $validated['admin_note'] ?? null
            );

            session()->flash('success', 'Refund disetujui dan sedang diproses ke Midtrans.');
        } catch (\Throwable $e) {
            session()->flash('error', 'Gagal memproses refund: ' . $e->getMessage());
        }

        return redirect()->back();
    }

    public function reject(Request $request, int $id)
    {
        $validated = $request->validate([
            'admin_note' => 'required|string|max:1000',
        ]);

        $refundRequest = $this->refundRequestRepository->findOrFail($id);

        abort_if($refundRequest->status !== 'pending', 422, 'Request ini sudah diproses.');

        $this->refundProcessingService->reject($refundRequest, $validated['admin_note']);

        session()->flash('success', 'Refund request ditolak.');

        return redirect()->back();
    }
}
