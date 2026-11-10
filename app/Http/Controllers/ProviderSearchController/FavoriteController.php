<?php

namespace App\Http\Controllers\ProviderSearchController;

use App\Http\Controllers\Controller\Controller;
use App\Models\FavoriteProvider;
use App\Models\ProviderProfile;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    public function toggle(Request $request, ProviderProfile $provider)
    {
        abort_unless($provider->verification_status === 'approved' && $provider->user->is_active, 404);

        $favorite = FavoriteProvider::where('user_id', $request->user()->id)
            ->where('provider_profile_id', $provider->id)
            ->first();

        if ($favorite) {
            $favorite->delete();
            $isFavorited = false;
        } else {
            FavoriteProvider::create([
                'user_id' => $request->user()->id,
                'provider_profile_id' => $provider->id,
            ]);
            $isFavorited = true;
        }

        if ($request->expectsJson()) {
            return response()->json(['favorited' => $isFavorited]);
        }

        return back()->with('success', $isFavorited ? 'Provider saved to favorites.' : 'Provider removed from favorites.');
    }

    public function index(Request $request)
    {
        $providers = $request->user()->favoriteProviders()
            ->where('verification_status', 'approved')
            ->whereHas('user', fn ($q) => $q->where('is_active', true))
            ->with(['user', 'services' => fn ($q) => $q->where('is_active', true)->with('category')])
            ->orderByDesc('average_rating')
            ->paginate(12)
            ->withQueryString();

        return view('providers.favorites.index', compact('providers'));
    }
}
