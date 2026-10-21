<?php

namespace App\Http\Controllers\Customer\CustomerDashboardController;

use App\Http\Controllers\Controller\Controller;
use App\Models\Message;
use App\Models\Notification;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\ProviderProfile;
use App\Models\Review;
use App\Models\ServiceCategory;
use App\Models\ServiceRequest;
use Illuminate\Http\Request;

class CustomerDashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $base = ServiceRequest::query()->where('customer_id', $user->id);
        $stats = [
            'open' => (clone $base)->whereIn('status', ['pending', 'accepted', 'in_progress'])->count(),
            'completed' => (clone $base)->where('status', 'completed')->count(),
            'total' => (clone $base)->count(),
            'waiting' => (clone $base)->where('status', 'pending')->count(),
            'scheduled' => Booking::where('customer_id', $user->id)->where('status', 'accepted')->whereNotNull('scheduled_at')->where('scheduled_at', '>=', now())->count(),
            'paid_total' => Payment::where('customer_id', $user->id)->where('status', 'paid')->sum('amount'),
            'unread_messages' => Message::where('recipient_id', $user->id)->whereNull('read_at')->count(),
            'unread_notifications' => Notification::where('user_id', $user->id)->whereNull('read_at')->count(),
        ];

        $recent = (clone $base)->with(['provider.user', 'category', 'booking.review', 'booking.payments'])->latest()->limit(5)->get();
        $upcomingBooking = Booking::with(['serviceRequest.category', 'provider.user'])
            ->where('customer_id', $user->id)->where('status', 'accepted')->whereHas('serviceRequest')->whereNotNull('scheduled_at')
            ->where('scheduled_at', '>=', now())->orderBy('scheduled_at')->first();
        $reviewDue = (clone $base)->with(['provider.user', 'category', 'booking'])
            ->where('status', 'completed')->whereHas('booking', fn ($query) => $query->whereDoesntHave('review'))
            ->latest()->limit(3)->get();
        $paymentDue = (clone $base)->with(['provider.user', 'category', 'booking'])
            ->where('status', 'completed')->whereHas('booking', fn ($query) => $query->whereNotNull('final_price')->where('final_price', '>', 0)
                ->whereDoesntHave('payments', fn ($payment) => $payment->where('status', 'paid')))
            ->latest()->limit(3)->get();
        $actionItems = collect();
        $paymentDue->each(function ($item) use ($actionItems) {
            $actionItems->push(['type' => 'payment', 'title' => 'Payment due for ' . ($item->category->name ?? 'completed work'), 'detail' => 'GH₵ ' . number_format((float) $item->booking->final_price, 2), 'url' => route('customer.requests.show', $item), 'label' => 'Review payment']);
        });
        $reviewDue->each(function ($item) use ($actionItems) {
            if (! $actionItems->contains(fn ($action) => $action['url'] === route('customer.requests.show', $item))) {
                $actionItems->push(['type' => 'review', 'title' => 'Share your feedback', 'detail' => ($item->provider->user->name ?? 'Professional') . ' · ' . ($item->category->name ?? 'Service'), 'url' => route('customer.reviews.create', $item), 'label' => 'Leave a review']);
            }
        });
        $actionItems = $actionItems->take(4)->values();
        $categories = ServiceCategory::query()->where('is_active', true)->orderBy('sort_order')->orderBy('name')->get();
        $providerCounts = ProviderProfile::query()->where('verification_status', 'approved')
            ->whereHas('user', fn ($query) => $query->where('is_active', true))
            ->selectRaw('LOWER(profession) as profession, COUNT(*) as total')->groupByRaw('LOWER(profession)')
            ->pluck('total', 'profession');
        $recommendations = ProviderProfile::query()
            ->with('user')
            ->where('verification_status', 'approved')
            ->whereHas('user', fn ($query) => $query->where('is_active', true))
            ->orderByDesc('average_rating')->orderByDesc('review_count')->limit(4)->get();
        $activities = collect();
        (clone $base)->with(['category', 'provider.user'])->latest()->limit(5)->get()->each(function ($item) use ($activities) {
            $activities->push(['at' => $item->created_at, 'type' => 'request', 'title' => 'Service request submitted', 'detail' => ($item->category->name ?? 'Service') . ' · ' . ucfirst($item->status), 'url' => route('customer.requests.show', $item)]);
        });
        Payment::with('booking.serviceRequest.category')->where('customer_id', $user->id)->latest()->limit(4)->get()->each(function ($payment) use ($activities) {
            $activities->push(['at' => $payment->created_at, 'type' => 'payment', 'title' => 'Payment ' . strtolower($payment->status), 'detail' => 'GH₵ ' . number_format((float) $payment->amount, 2), 'url' => route('customer.payments.index')]);
        });
        Review::with('provider.user')->where('customer_id', $user->id)->latest()->limit(3)->get()->each(function ($review) use ($activities) {
            $activities->push(['at' => $review->created_at, 'type' => 'review', 'title' => 'You left a review', 'detail' => ($review->provider->user->name ?? 'Professional') . ' · ' . $review->rating . ' stars', 'url' => route('customer.reviews.index')]);
        });
        Message::with(['sender', 'serviceRequest.category'])->where('recipient_id', $user->id)->latest()->limit(4)->get()->each(function ($message) use ($activities) {
            $requestRecord = $message->serviceRequest;
            if ($requestRecord && $requestRecord->customer_id) {
                $activities->push(['at' => $message->created_at, 'type' => 'message', 'title' => 'Message from ' . ($message->sender->name ?? 'your professional'), 'detail' => $requestRecord->category->name ?? 'Service request conversation', 'url' => route('messages.show', $requestRecord)]);
            }
        });
        $activities = $activities->sortByDesc('at')->take(5)->values();

        return view('customer.dashboard', compact('stats', 'recent', 'categories', 'providerCounts', 'recommendations', 'activities', 'upcomingBooking', 'actionItems'));
    }
}
