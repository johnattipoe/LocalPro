<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index()
    {
        $reviews = Review::with(['customer','provider.user','booking.serviceRequest'])->latest()->paginate(20);
        return view('admin.reports.index',compact('reviews'));
    }

    public function toggleReview(Review $review)
    {
        $review->update(['status'=>$review->status==='published'?'hidden':'published']);
        $provider = $review->provider;
        $provider->review_count = $provider->reviews()->count();
        $provider->average_rating = $provider->reviews()->avg('rating') ?? 0;
        $provider->save();
        return back()->with('status','Review moderation updated.');
    }
}
