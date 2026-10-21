<?php

namespace App\Http\Controllers\Provider\ProviderBookingController;

use App\Http\Controllers\Controller\Controller;
use App\Models\ServiceRequest;
use App\Services\BookingService\BookingService;
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
        $filters = $request->validate([
            'q' => ['nullable', 'string', 'max:120'],
            'status' => ['nullable', 'in:pending,accepted,completed,cancelled,declined'],
        ]);
        $requests = ServiceRequest::with(['customer', 'category', 'booking'])
            ->where('provider_profile_id', $profile->id)
            ->when($filters['status'] ?? null, fn ($query, $status) => $query->where('status', $status))
            ->when($filters['q'] ?? null, function ($query, $term) {
                $like = '%' . $term . '%';
                $query->where(function ($matches) use ($like) {
                    $matches->where('description', 'like', $like)
                        ->orWhere('city', 'like', $like)
                        ->orWhereHas('customer', fn ($customer) => $customer->where('name', 'like', $like)->orWhere('phone', 'like', $like))
                        ->orWhereHas('category', fn ($category) => $category->where('name', 'like', $like));
                });
            })
            ->latest()->paginate(12)->withQueryString();

        return view('provider.bookings.index', compact('profile', 'requests', 'filters'));
    }

    public function show(Request $request, ServiceRequest $serviceRequest)
    {
        abort_unless((int) $serviceRequest->provider_profile_id === (int) $request->user()->providerProfile?->id, 404);
        $serviceRequest->load(['customer', 'category', 'booking']);

        return view('provider.bookings.show', compact('serviceRequest'));
    }

    public function update(Request $request, ServiceRequest $serviceRequest)
    {
        abort_unless((int) $serviceRequest->provider_profile_id === (int) $request->user()->providerProfile?->id, 404);
        $data = $request->validate([
            'status' => ['required', Rule::in(['accepted', 'declined', 'completed'])],
            'quoted_price' => ['nullable','numeric','min:0','max:999999'],
            'final_price' => ['nullable','numeric','min:0','max:999999'],
            'scheduled_at' => ['nullable','date','after_or_equal:today'],
        ]);
        if ($data['status'] === 'accepted' && $request->user()->providerProfile?->verification_status !== 'approved') {
            return back()->withErrors(['status' => 'Your provider profile must be verified before accepting jobs.'])->withInput();
        }

        if ($data['status'] === 'accepted' && $serviceRequest->status === 'accepted') {
            $bookingData = [];
            if (isset($data['quoted_price'])) {
                $bookingData['quoted_price'] = $data['quoted_price'];
            }
            if (isset($data['scheduled_at'])) {
                $bookingData['scheduled_at'] = $data['scheduled_at'];
            }
            if ($bookingData === []) {
                return back()->withErrors(['quoted_price' => 'Add a quote or proposed date before saving.'])->withInput();
            }

            $this->bookings->updateAcceptedDetails($serviceRequest, $request->user(), $bookingData);

            return redirect()->route('provider.bookings.show', $serviceRequest)
                ->with('status', 'Quote or schedule updated.');
        }

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
