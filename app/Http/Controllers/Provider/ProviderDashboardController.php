<?php

namespace App\Http\Controllers\Provider;

use App\Http\Controllers\Controller;
use App\Models\ServiceRequest;
use Illuminate\Http\Request;

class ProviderDashboardController extends Controller
{
    public function index(Request $request)
    {
        $profile = $request->user()->providerProfile;
        abort_unless($profile, 404);
        $requests = ServiceRequest::where('provider_profile_id', $profile->id);
        $stats = [
            'pending' => (clone $requests)->where('status', 'pending')->count(),
            'accepted' => (clone $requests)->where('status', 'accepted')->count(),
            'completed' => (clone $requests)->where('status', 'completed')->count(),
        ];
        $recentRequests = (clone $requests)->with(['customer', 'category'])->latest()->limit(6)->get();

        return view('provider.dashboard', compact('profile', 'stats', 'recentRequests'));
    }
}
