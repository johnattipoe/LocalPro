<?php

namespace App\Http\Controllers\Admin\AdminDashboardController;

use App\Http\Controllers\Controller\Controller;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\ProviderProfile;
use App\Models\Review;
use App\Models\ServiceCategory;
use App\Models\ServiceRequest;
use App\Models\SupportTicket;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $now = now();
        $monthStart = $now->copy()->subDays(29)->startOfDay();
        $previousMonthStart = $now->copy()->subDays(59)->startOfDay();
        $previousMonthEnd = $monthStart->copy()->subSecond();

        $stats = [
            'users' => User::count(),
            'verified' => ProviderProfile::where('verification_status', 'approved')->count(),
            'bookings' => Booking::count(),
            'revenue' => (float) Payment::where('status', 'paid')->sum('amount'),
            'pending_verifications' => ProviderProfile::where('verification_status', 'pending')->count(),
            'open_disputes' => SupportTicket::whereIn('status', ['open', 'in_progress'])->count(),
            'users_change' => $this->changePercent(User::query(), $monthStart, $previousMonthStart, $previousMonthEnd),
            'providers_change' => $this->changePercent(ProviderProfile::where('verification_status', 'approved'), $monthStart, $previousMonthStart, $previousMonthEnd),
            'bookings_change' => $this->changePercent(Booking::query(), $monthStart, $previousMonthStart, $previousMonthEnd),
            'revenue_change' => $this->revenueChange($monthStart, $previousMonthStart, $previousMonthEnd),
        ];

        $chartBookings = Booking::selectRaw('DATE(created_at) as day, COUNT(*) as total')
            ->where('created_at', '>=', $now->copy()->subDays(6)->startOfDay())
            ->groupBy('day')->orderBy('day')->pluck('total', 'day');
        $chart = collect(range(6, 0))->map(function ($daysAgo) use ($now, $chartBookings) {
            $date = $now->copy()->subDays($daysAgo)->startOfDay();
            return ['label' => $date->format('M j'), 'value' => (int) $chartBookings->get($date->toDateString(), 0)];
        });
        $chartMax = max(1, (int) $chart->max('value'));
        $chartPoints = $chart->values()->map(function ($point, $index) use ($chartMax) {
            $x = 30 + ($index * 105);
            $y = 150 - (105 * $point['value'] / $chartMax);
            return ['x' => $x, 'y' => round($y, 1), 'label' => $point['label'], 'value' => $point['value']];
        });

        $categoryCounts = ServiceRequest::query()->select('service_category_id')->selectRaw('COUNT(*) as total')
            ->with('category')->groupBy('service_category_id')->orderByDesc('total')->limit(7)->get();
        $categoryTotal = max(1, (int) $categoryCounts->sum('total'));
        $categoryColors = ['#07977c', '#2786e8', '#f4aa24', '#8652dc', '#ed5357', '#22a8c4', '#8394a7'];
        $categories = $categoryCounts->values()->map(function ($row, $index) use ($categoryTotal, $categoryColors) {
            return [
                'name' => $row->category->name ?? 'Uncategorized',
                'total' => (int) $row->total,
                'percent' => (int) round($row->total * 100 / $categoryTotal),
                'color' => $categoryColors[$index % count($categoryColors)],
            ];
        });
        $donutStops = [];
        $cursor = 0;
        foreach ($categories as $category) {
            $end = $cursor + ($category['total'] * 100 / $categoryTotal);
            $donutStops[] = $category['color'] . ' ' . $cursor . '% ' . $end . '%';
            $cursor = $end;
        }
        $donutBackground = $donutStops ? 'conic-gradient(' . implode(', ', $donutStops) . ')' : 'conic-gradient(#e8edf1 0% 100%)';

        $pendingProviders = ProviderProfile::with(['user', 'verifications'])
            ->where('verification_status', 'pending')->latest()->limit(5)->get();
        $recentBookings = Booking::with(['customer', 'provider.user', 'serviceRequest.category'])
            ->latest()->limit(5)->get();
        $activities = $this->recentActivity();

        return view('admin.dashboard', compact('stats', 'chart', 'chartPoints', 'chartMax', 'categories', 'categoryTotal', 'donutBackground', 'pendingProviders', 'recentBookings', 'activities'));
    }

    private function changePercent($query, Carbon $monthStart, Carbon $previousStart, Carbon $previousEnd): int
    {
        $current = (clone $query)->where('created_at', '>=', $monthStart)->count();
        $previous = (clone $query)->whereBetween('created_at', [$previousStart, $previousEnd])->count();
        if ($previous === 0) return $current > 0 ? 100 : 0;
        return (int) round((($current - $previous) / $previous) * 100);
    }

    private function revenueChange(Carbon $monthStart, Carbon $previousStart, Carbon $previousEnd): int
    {
        $current = (float) Payment::where('status', 'paid')->where('created_at', '>=', $monthStart)->sum('amount');
        $previous = (float) Payment::where('status', 'paid')->whereBetween('created_at', [$previousStart, $previousEnd])->sum('amount');
        if ($previous <= 0) return $current > 0 ? 100 : 0;
        return (int) round((($current - $previous) / $previous) * 100);
    }

    private function recentActivity(): Collection
    {
        $activity = collect();

        ProviderProfile::with('user')->where('verification_status', 'pending')->latest()->limit(3)->get()->each(function ($provider) use ($activity) {
            $activity->push(['icon' => 'shield-check', 'kind' => 'success', 'title' => 'Provider verification', 'detail' => $provider->user->name . ' (' . $provider->profession . ')', 'at' => $provider->created_at, 'url' => route('admin.providers.index', ['status' => 'pending'])]);
        });
        User::where('role', 'customer')->latest()->limit(3)->get()->each(function ($user) use ($activity) {
            $activity->push(['icon' => 'user-round', 'kind' => 'blue', 'title' => 'New customer registered', 'detail' => $user->name . ' · Customer', 'at' => $user->created_at, 'url' => route('admin.users.index')]);
        });
        ServiceRequest::with('category')->latest()->limit(3)->get()->each(function ($request) use ($activity) {
            $activity->push(['icon' => 'clipboard-list', 'kind' => 'amber', 'title' => 'New booking request', 'detail' => ($request->category->name ?? 'Local service') . ' · ' . $request->city, 'at' => $request->created_at, 'url' => route('admin.bookings.index')]);
        });
        Payment::where('status', 'paid')->latest()->limit(2)->get()->each(function ($payment) use ($activity) {
            $activity->push(['icon' => 'credit-card', 'kind' => 'purple', 'title' => 'Payment received', 'detail' => 'GH₵ ' . number_format((float) $payment->amount, 2), 'at' => $payment->paid_at ?? $payment->created_at, 'url' => route('admin.payments.index')]);
        });
        Review::with('provider.user')->latest()->limit(2)->get()->each(function ($review) use ($activity) {
            $activity->push(['icon' => 'star', 'kind' => 'amber', 'title' => 'New customer review', 'detail' => $review->rating . ' stars · ' . ($review->provider->user->name ?? 'Professional'), 'at' => $review->created_at, 'url' => route('admin.reports.index')]);
        });
        SupportTicket::whereIn('status', ['open', 'in_progress'])->latest()->limit(2)->get()->each(function ($ticket) use ($activity) {
            $activity->push(['icon' => 'circle-alert', 'kind' => 'red', 'title' => 'Open support issue', 'detail' => $ticket->topic ?: 'Customer support request', 'at' => $ticket->created_at, 'url' => route('admin.support.index')]);
        });

        return $activity->sortByDesc('at')->take(6)->values();
    }
}
