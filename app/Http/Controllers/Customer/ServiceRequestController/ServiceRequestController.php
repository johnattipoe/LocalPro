<?php

namespace App\Http\Controllers\Customer\ServiceRequestController;

use App\Http\Controllers\Controller\Controller;
use App\Http\Requests\StoreServiceRequest\StoreServiceRequest as StoreServiceRequestForm;
use App\Models\ProviderProfile;
use App\Models\ServiceCategory;
use App\Models\ServiceRequest;
use App\Models\Booking;
use App\Services\NotificationService\NotificationService;
use App\Services\BookingService\BookingService;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ServiceRequestController extends Controller
{
    public function __construct(private NotificationService $notifications, private BookingService $bookings)
    {
    }

    public function create(ProviderProfile $provider)
    {
        abort_unless($provider->verification_status === 'approved' && $provider->user()->where('is_active',true)->exists(), 404);
        $provider->load('user');
        $category = ServiceCategory::query()
            ->where('is_active', true)
            ->where('name', $provider->profession)
            ->first();

        return view('customer.requests.create', compact('provider', 'category'));
    }

    public function store(StoreServiceRequestForm $request)
    {
        $data = $request->validated();
        $provider = ProviderProfile::query()
            ->whereKey($data['provider_profile_id'])
            ->where('verification_status', 'approved')
            ->whereHas('user', fn ($query) => $query->where('is_active', true))
            ->firstOrFail();

        if (! empty($data['service_category_id'])) {
            $category = ServiceCategory::query()->whereKey($data['service_category_id'])
                ->where('is_active', true)->first();
            if (! $category || $category->name !== $provider->profession) {
                throw ValidationException::withMessages([
                    'service_category_id' => 'Choose the service listed on this professional profile.',
                ]);
            }
        }

        $serviceRequest = DB::transaction(function () use ($request, $data, $provider) {
            $serviceRequest = ServiceRequest::create([
                'customer_id' => $request->user()->id,
                'provider_profile_id' => $provider->id,
                'service_category_id' => $data['service_category_id'] ?? null,
                'description' => $data['description'],
                'city' => $data['city'],
                'address' => $data['address'] ?? null,
                'preferred_at' => $data['preferred_at'] ?? null,
                'status' => 'pending',
            ]);

            Booking::create([
                'customer_id' => $request->user()->id,
                'provider_profile_id' => $provider->id,
                'service_request_id' => $serviceRequest->id,
                'scheduled_at' => $serviceRequest->preferred_at,
                'status' => 'pending',
            ]);

            $this->notifications->send(
                $provider->user,
                'New service request',
                $request->user()->name . ' sent you a request for ' . $provider->profession . '.'
            );

            return $serviceRequest;
        });

        return redirect()->route('customer.requests.show', $serviceRequest)
            ->with('status', 'Your request was sent to ' . $provider->user->name . '.');
    }

    public function index()
    {
        $requests = ServiceRequest::query()
            ->with(['provider.user', 'category'])
            ->where('customer_id', auth()->id())
            ->latest()
            ->paginate(10);

        return view('customer.requests.index', compact('requests'));
    }

    public function history()
    {
        $requests = ServiceRequest::query()->with(['provider.user', 'category', 'booking.review'])
            ->where('customer_id', auth()->id())->whereIn('status', ['completed', 'cancelled', 'declined'])
            ->latest()->paginate(10);

        return view('customer.history.history', compact('requests'));
    }

    public function show(ServiceRequest $serviceRequest)
    {
        abort_unless((int) $serviceRequest->customer_id === (int) auth()->id(), 404);
        $serviceRequest->load(['provider.user', 'category']);

        return view('customer.requests.show', compact('serviceRequest'));
    }

    public function cancel(\Illuminate\Http\Request $request, ServiceRequest $serviceRequest)
    {
        abort_unless((int) $serviceRequest->customer_id === (int) $request->user()->id, 404);
        $this->bookings->cancelByCustomer($serviceRequest, $request->user());
        return redirect()->route('customer.requests.show',$serviceRequest)->with('status','Your request was cancelled.');
    }
}
