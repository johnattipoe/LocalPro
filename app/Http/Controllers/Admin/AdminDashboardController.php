<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProviderProfile;
use App\Models\ServiceRequest;
use App\Models\SupportTicket;
use App\Models\Payment;
use App\Models\ProviderPayout;
use App\Models\User;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'customers' => User::where('role', 'customer')->count(),
            'providers' => ProviderProfile::where('verification_status', 'approved')->count(),
            'pending' => ProviderProfile::where('verification_status', 'pending')->count(),
            'requests' => ServiceRequest::count(),
            'open_support' => SupportTicket::whereIn('status', ['open', 'in_progress'])->count(),
            'payments_recorded' => Payment::where('status', 'paid')->count(),
            'amount_collected' => Payment::where('status', 'paid')->sum('amount'),
            'pending_payouts' => ProviderPayout::whereIn('status', ['requested', 'approved'])->count(),
        ];
        $recentRequests = ServiceRequest::with(['customer', 'provider.user', 'category'])->latest()->limit(8)->get();

        return view('admin.dashboard', compact('stats', 'recentRequests'));
    }
}
