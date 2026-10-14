<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProviderPayout;
use App\Services\PayoutService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PayoutManagementController extends Controller
{
    public function __construct(private PayoutService $payouts)
    {
    }

    public function index()
    {
        $payouts = ProviderPayout::with(['provider.user', 'requester'])
            ->latest()
            ->paginate(20);

        return view('admin.payouts.index', compact('payouts'));
    }

    public function update(Request $request, ProviderPayout $payout)
    {
        $data = $request->validate([
            'status' => ['required', Rule::in(['approved', 'rejected', 'paid'])],
            'transfer_reference' => ['nullable', 'string', 'max:120'],
            'admin_note' => ['nullable', 'string', 'max:2000'],
        ]);

        if ($data['status'] === 'rejected' && empty($data['admin_note'])) {
            return back()->withErrors(['admin_note' => 'Explain why the payout request was rejected.']);
        }

        if ($data['status'] === 'paid' && empty($data['transfer_reference'])) {
            return back()->withErrors(['transfer_reference' => 'Add the external transfer reference before marking a payout as paid.']);
        }

        $this->payouts->review(
            $payout,
            $request->user(),
            $data['status'],
            $data['transfer_reference'] ?? null,
            $data['admin_note'] ?? null
        );

        return back()->with('status', 'Payout record updated. Complete any approved transfer outside LocalPro, then record its reference as paid.');
    }
}
