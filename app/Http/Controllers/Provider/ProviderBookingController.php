<?php

namespace App\Http\Controllers\Provider;

use App\Http\Controllers\Controller;
use App\Models\ServiceRequest;
use App\Services\BookingService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProviderBookingController extends Controller
{
    public function __construct(private BookingService $bookings)
    {
    }

    public function index(Request $request)
    {
        $profile = $request->user()->providerProfile;
        abort_unless($profile, 404);
        $requests = ServiceRequest::with(['customer', 'category'])
            ->where('provider_profile_id', $profile->id)->latest()->paginate(12);

        return view('provider.bookings.index', compact('profile', 'requests'));
    }

    public function show(Request $request, ServiceRequest $serviceRequest)
    {
        abort_unless($serviceRequest->provider_profile_id === $request->user()->providerProfile?->id, 404);
        $serviceRequest->load(['customer', 'category']);

        return view('provider.bookings.show', compact('serviceRequest'));
    }

    public function update(Request $request, ServiceRequest $serviceRequest)
    {
        abort_unless($serviceRequest->provider_profile_id === $request->user()->providerProfile?->id, 404);
        $data = $request->validate([
            'status' => ['required', Rule::in(['accepted', 'declined', 'completed'])],
            'quoted_price' => ['nullable','numeric','min:0','max:999999'],
            'final_price' => ['nullable','numeric','min:0','max:999999'],
            'scheduled_at' => ['nullable','date','after_or_equal:today'],
        ]);
        $bookingData = [];
        if ($data['status'] === 'accepted') {
            $bookingData['quoted_price'] = $data['quoted_price'] ?? null;
            $bookingData['scheduled_at'] = $data['scheduled_at'] ?? null;
        }
        if ($data['status'] === 'completed') {
            $bookingData['final_price'] = $data['final_price'] ?? null;
        }
        $this->bookings->transitionByProvider($serviceRequest, $request->user(), $data['status'], $bookingData);

        return redirect()->route('provider.bookings.show', $serviceRequest)
            ->with('status', 'Request marked as ' . $data['status'] . '.');
    }
}
