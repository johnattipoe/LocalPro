<?php

namespace App\Http\Controllers\Provider\ProviderPayoutController;

use App\Http\Controllers\Controller\Controller;
use App\Services\PayoutService\PayoutService;
use Illuminate\Http\Request;

class ProviderPayoutController extends Controller
{
    public function __construct(private PayoutService $payouts)
    {
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'amount' => ['required', 'numeric', 'min:0.01', 'max:999999'],
        ]);

        $provider = $request->user()->providerProfile;
        abort_unless($provider, 404);
        $this->payouts->request($provider, $request->user(), (float) $data['amount']);

        return redirect()->route('provider.earnings.index')->with('status', 'Your payout request was sent to the LocalPro admin team for review.');
    }
}
