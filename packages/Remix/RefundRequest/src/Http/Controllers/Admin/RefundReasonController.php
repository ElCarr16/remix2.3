<?php

namespace Remix\RefundRequest\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Webkul\Admin\Http\Controllers\Controller;
use Remix\RefundRequest\Repositories\RefundReasonRepository;

class RefundReasonController extends Controller
{
    public function __construct(protected RefundReasonRepository $refundReasonRepository) {}

    public function index()
    {
        $reasons = $this->refundReasonRepository->orderBy('sort_order')->get();

        return view('remix::admin.reasons.index', compact('reasons'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'label'      => 'required|string|max:255',
            'sort_order' => 'nullable|integer',
        ]);

        $this->refundReasonRepository->create($data);

        return redirect()->back()->with('success', 'Alasan refund ditambahkan.');
    }

    public function update(Request $request, int $id)
    {
        $data = $request->validate([
            'label'      => 'required|string|max:255',
            'is_active'  => 'boolean',
            'sort_order' => 'nullable|integer',
        ]);

        $this->refundReasonRepository->update($data, $id);

        return redirect()->back()->with('success', 'Alasan refund diperbarui.');
    }

    public function destroy(int $id)
    {
        $this->refundReasonRepository->delete($id);

        return redirect()->back()->with('success', 'Alasan refund dihapus.');
    }
}
