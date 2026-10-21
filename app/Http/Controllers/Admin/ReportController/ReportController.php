<?php

namespace App\Http\Controllers\Admin\ReportController;

use App\Http\Controllers\Controller\Controller;
use App\Models\Review;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\ServiceRequest;
use App\Models\User;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index()
    {
        $reviews = Review::with(['customer','provider.user','booking.serviceRequest'])->latest()->paginate(20);
        return view('admin.reports.index',compact('reviews'));
    }

    public function analytics()
    {
        $start = now()->subDays(29)->startOfDay();
        $stats = [
            'bookings' => Booking::where('created_at', '>=', $start)->count(),
            'customers' => User::where('role', 'customer')->where('created_at', '>=', $start)->count(),
            'requests' => ServiceRequest::where('created_at', '>=', $start)->count(),
            'revenue' => (float) Payment::where('status', 'paid')->where('created_at', '>=', $start)->sum('amount'),
        ];

        $days = Booking::selectRaw('DATE(created_at) as day, COUNT(*) as total')
            ->where('created_at', '>=', $start)
            ->groupBy('day')
            ->orderBy('day')
            ->pluck('total', 'day');
        $bookingTrend = collect(range(29, 0))->map(function ($offset) use ($days) {
            $date = now()->subDays($offset)->startOfDay();
            return ['label' => $date->format('M j'), 'count' => (int) $days->get($date->toDateString(), 0)];
        });

        $bookingStatuses = Booking::select('status')->selectRaw('COUNT(*) as total')
            ->groupBy('status')->orderByDesc('total')->get();
        $topCategories = ServiceRequest::query()->select('service_category_id')->selectRaw('COUNT(*) as total')
            ->with('category')->groupBy('service_category_id')->orderByDesc('total')->limit(10)->get();
        $categoryTotal = ServiceRequest::count();

        return view('admin.analytics.index', compact('stats', 'bookingTrend', 'bookingStatuses', 'topCategories', 'categoryTotal'));
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
