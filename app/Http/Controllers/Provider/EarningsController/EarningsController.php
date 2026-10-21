<?php

namespace App\Http\Controllers\Provider\EarningsController;

use App\Http\Controllers\Controller\Controller;
use App\Models\Payment;
use App\Models\ProviderPayout;
use App\Services\PayoutService\PayoutService;
use Illuminate\Http\Request;

class EarningsController extends Controller
{
    public function __construct(private PayoutService $payouts)
    {
    }

    public function index(Request $request)
    {
        $profile = $request->user()->providerProfile;
        abort_unless($profile,404);
        $payments = Payment::with(['customer','booking.serviceRequest'])
            ->whereHas('booking',fn($q)=>$q->where('provider_profile_id',$profile->id))
            ->latest()->paginate(20);
        $paidTotal = Payment::where('status','paid')->whereHas('booking',fn($q)=>$q->where('provider_profile_id',$profile->id))->sum('amount');
        $payoutRequests = ProviderPayout::where('provider_profile_id', $profile->id)->latest()->paginate(10, ['*'], 'payout_page');
        $availablePayoutAmount = $this->payouts->availableAmount($profile);
        return view('provider.earnings.index',compact('payments','paidTotal','payoutRequests','availablePayoutAmount','profile'));
    }
}
