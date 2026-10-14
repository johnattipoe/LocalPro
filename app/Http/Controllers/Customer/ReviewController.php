<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\ServiceRequest;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReviewController extends Controller
{
    public function create(Request $request, ServiceRequest $serviceRequest)
    {
        abort_unless($serviceRequest->customer_id === $request->user()->id, 404);
        abort_unless($serviceRequest->status === 'completed' && $serviceRequest->booking, 404);
        abort_if($serviceRequest->booking->review()->exists(), 404);
        return view('customer.reviews.create', compact('serviceRequest'));
    }

    public function store(Request $request, ServiceRequest $serviceRequest)
    {
        abort_unless($serviceRequest->customer_id === $request->user()->id, 404);
        abort_unless($serviceRequest->status === 'completed' && $serviceRequest->booking, 404);
        abort_if($serviceRequest->booking->review()->exists(), 404);
        $data = $request->validate(['rating' => ['required', 'integer', 'between:1,5'], 'comment' => ['nullable', 'string', 'max:2000']]);

        DB::transaction(function () use ($request, $serviceRequest, $data) {
            Review::create([
                'booking_id' => $serviceRequest->booking->id,
                'customer_id' => $request->user()->id,
                'provider_profile_id' => $serviceRequest->provider_profile_id,
                'rating' => $data['rating'],
                'comment' => $data['comment'] ?? null,
                'status' => 'published',
            ]);
            $provider = $serviceRequest->provider;
            $provider->review_count = $provider->reviews()->count();
            $provider->average_rating = $provider->reviews()->avg('rating') ?? 0;
            $provider->save();
        });

        return redirect()->route('customer.requests.show', $serviceRequest)->with('status', 'Thanks for sharing your review.');
    }
}
