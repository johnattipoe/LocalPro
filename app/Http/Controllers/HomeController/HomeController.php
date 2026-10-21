<?php

namespace App\Http\Controllers\HomeController;

use App\Http\Controllers\Controller\Controller;
use App\Models\ProviderProfile;
use App\Models\ServiceCategory;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function services()
    {
        $categories = ServiceCategory::where('is_active',true)->orderBy('sort_order')->get();
        return view('services.index.index',compact('categories'));
    }

    public function index(Request $request)
    {
        $categories = ServiceCategory::query()->where('is_active', true)
            ->when($request->filled('q'), fn ($query) => $query->where('name', 'like', '%' . $request->q . '%'))
            ->orderBy('sort_order')->get();
        $providers = ProviderProfile::query()->with('user')->where('verification_status', 'approved')
            ->whereHas('user', fn ($query) => $query->where('is_active', true))
            ->when($request->filled('q'), function ($query) use ($request) {
                $term = '%' . $request->q . '%';
                $query->where(function ($matches) use ($term) {
                    $matches->where('profession', 'like', $term)
                        ->orWhereHas('services', fn ($services) => $services->where('is_active', true)->where(function ($service) use ($term) {
                            $service->where('title', 'like', $term)->orWhere('description', 'like', $term);
                        }));
                });
            })
            ->when($request->filled('city'), fn ($query) => $query->where('city', $request->city))
            ->orderByDesc('average_rating')->limit(3)->get();
        return view('pages.home.home', compact('categories', 'providers'));
    }

    public function category(ServiceCategory $category)
    {
        $providers = ProviderProfile::query()->with('user')->where('verification_status', 'approved')
            ->whereHas('user', fn ($query) => $query->where('is_active', true))
            ->where(function ($matches) use ($category) {
                $matches->where('profession', 'like', '%' . $category->name . '%')
                    ->orWhereHas('services', fn ($services) => $services->where('is_active', true)->where('service_category_id', $category->id));
            })->orderByDesc('average_rating')->paginate(12);
        return view('services.show.show', compact('category', 'providers'));
    }

    public function provider(ProviderProfile $provider)
    {
        abort_unless($provider->verification_status === 'approved', 404);
        $provider->load(['user', 'services' => fn ($query) => $query->where('is_active', true)->with('category'), 'reviews.customer']);
        abort_unless($provider->user->is_active, 404);
        return view('providers.show.show', compact('provider'));
    }
}
