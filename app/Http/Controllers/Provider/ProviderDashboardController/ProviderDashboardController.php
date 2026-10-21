<?php

namespace App\Http\Controllers\Provider\ProviderDashboardController;

use App\Http\Controllers\Controller\Controller;
use App\Models\Message;
use App\Models\Notification;
use App\Models\Payment;
use App\Models\Review;
use App\Models\ServiceRequest;
use Illuminate\Http\Request;

class ProviderDashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $profile = $user->providerProfile;
        abort_unless($profile, 404);

        $base = ServiceRequest::query()->where('provider_profile_id', $profile->id);
        $paidPayments = Payment::query()->where('status', 'paid')->whereHas('booking', fn ($query) => $query->where('provider_profile_id', $profile->id));
        $stats = [
            'pending' => (clone $base)->where('status', 'pending')->count(),
            'accepted' => (clone $base)->where('status', 'accepted')->count(),
            'completed' => (clone $base)->where('status', 'completed')->count(),
            'earnings' => (clone $paidPayments)->sum('amount'),
            'unread_messages' => Message::where('recipient_id', $user->id)->whereNull('read_at')->count(),
            'unread_notifications' => Notification::where('user_id', $user->id)->whereNull('read_at')->count(),
            'active_services' => $profile->services()->where('is_active', true)->count(),
        ];

        $recentRequests = (clone $base)->with(['customer', 'category', 'booking'])->latest()->limit(5)->get();
        $services = $profile->services()->with('category')->orderByDesc('is_active')->latest()->limit(4)->get();
        $reviews = Review::with('customer')->where('provider_profile_id', $profile->id)
            ->where('status', 'published')->latest()->limit(3)->get();
        $latestMessageIds = Message::query()->selectRaw('MAX(id)')
            ->whereNotNull('service_request_id')
            ->whereHas('serviceRequest', fn ($query) => $query->where('provider_profile_id', $profile->id))
            ->groupBy('service_request_id');
        $latestMessages = Message::with(['sender', 'serviceRequest.category', 'serviceRequest.customer'])
            ->whereIn('id', $latestMessageIds)->latest()->limit(3)->get();

        $weeklyPayments = (clone $paidPayments)->whereNotNull('paid_at')
            ->where('paid_at', '>=', now()->subDays(6)->startOfDay())
            ->get(['amount', 'paid_at']);
        $earningsTrend = collect(range(6, 0))->map(function ($daysAgo) use ($weeklyPayments) {
            $date = now()->subDays($daysAgo)->startOfDay();
            $amount = $weeklyPayments->filter(fn ($payment) => $payment->paid_at->format('Y-m-d') === $date->format('Y-m-d'))->sum(fn ($payment) => (float) $payment->amount);
            return ['label' => $date->format('D'), 'date' => $date->format('M j'), 'amount' => $amount];
        });
        $weekEarnings = $earningsTrend->sum('amount');
        $chartMaximum = max(1, (float) $earningsTrend->max('amount'));

        return view('provider.dashboard', compact(
            'profile', 'stats', 'recentRequests', 'services', 'reviews', 'latestMessages',
            'earningsTrend', 'weekEarnings', 'chartMaximum'
        ));
    }

    public function reviews(Request $request)
    {
        $profile = $request->user()->providerProfile;
        abort_unless($profile, 404);
        $reviews = Review::with(['customer', 'booking.serviceRequest.category'])
            ->where('provider_profile_id', $profile->id)->where('status', 'published')
            ->latest()->paginate(15);

        return view('provider.reviews.index', compact('profile', 'reviews'));
    }
}
