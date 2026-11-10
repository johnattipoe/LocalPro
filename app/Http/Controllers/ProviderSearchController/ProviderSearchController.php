<?php

namespace App\Http\Controllers\ProviderSearchController;

use App\Http\Controllers\Controller\Controller;
use App\Models\ProviderProfile;
use App\Models\ServiceCategory;
use Illuminate\Http\Request;

class ProviderSearchController extends Controller
{
    public function index(Request $request)
    {
        $filters = $request->validate([
            'q' => ['nullable', 'string', 'max:120'],
            'city' => ['nullable', 'in:Ho,Hohoe,Keta,Aflao'],
            'category' => ['nullable', 'string', 'max:120'],
            'min_rating' => ['nullable', 'numeric', 'between:0,5'],
            'max_price' => ['nullable', 'numeric', 'min:0', 'max:999999'],
            'min_price' => ['nullable', 'numeric', 'min:0', 'max:999999'],
            'availability' => ['nullable', 'in:accepting,not_accepting'],
            'response_time' => ['nullable', 'in:1-24,24-48,48-72,72+'],
            'years_in_business' => ['nullable', 'in:1-2,3-5,6-10,10+'],
            'radius' => ['nullable', 'integer', 'min:1', 'max:100'],
            'lat' => ['nullable', 'numeric', 'between:-90,90'],
            'lng' => ['nullable', 'numeric', 'between:-180,180'],
            'view' => ['nullable', 'in:list,map'],
        ]);

        $providersQuery = ProviderProfile::with(['user', 'services' => fn ($q) => $q->where('is_active', true)->with('category')])
            ->where('verification_status', 'approved')
            ->whereHas('user', fn ($q) => $q->where('is_active', true))
            ->when(! empty($filters['q']), function ($query) use ($filters) {
                $term = '%'.$filters['q'].'%';
                $query->where(function ($matches) use ($term) {
                    $matches->where('profession', 'like', $term)
                        ->orWhereHas('services', fn ($services) => $services->where('is_active', true)->where(function ($service) use ($term) {
                            $service->where('title', 'like', $term)->orWhere('description', 'like', $term);
                        }));
                });
            })
            ->when(! empty($filters['category']), function ($query) use ($filters) {
                $term = '%'.$filters['category'].'%';
                $query->where(function ($matches) use ($term) {
                    $matches->where('profession', 'like', $term)
                        ->orWhereHas('services', fn ($services) => $services->where('is_active', true)->whereHas('category', fn ($category) => $category->where('name', 'like', $term)));
                });
            })
            ->when(! empty($filters['city']), fn ($q) => $q->where('city', $filters['city']))
            ->when(isset($filters['min_rating']), fn ($q) => $q->where('average_rating', '>=', $filters['min_rating']))
            ->when(isset($filters['min_price']), fn ($q) => $q->whereNotNull('starting_price')->where('starting_price', '>=', $filters['min_price']))
            ->when(isset($filters['max_price']), fn ($q) => $q->whereNotNull('starting_price')->where('starting_price', '<=', $filters['max_price']))
            ->when(($filters['availability'] ?? null) === 'accepting', fn ($q) => $q->where('accepting_requests', true))
            ->when(($filters['availability'] ?? null) === 'not_accepting', fn ($q) => $q->where('accepting_requests', false))
            ->when(($filters['response_time'] ?? null) === '1-24', fn ($q) => $q->whereNotNull('response_time_hours')->where('response_time_hours', '>=', 1)->where('response_time_hours', '<=', 24))
            ->when(($filters['response_time'] ?? null) === '24-48', fn ($q) => $q->whereNotNull('response_time_hours')->where('response_time_hours', '>', 24)->where('response_time_hours', '<=', 48))
            ->when(($filters['response_time'] ?? null) === '48-72', fn ($q) => $q->whereNotNull('response_time_hours')->where('response_time_hours', '>', 48)->where('response_time_hours', '<=', 72))
            ->when(($filters['response_time'] ?? null) === '72+', fn ($q) => $q->whereNotNull('response_time_hours')->where('response_time_hours', '>', 72))
            ->when(($filters['years_in_business'] ?? null) === '1-2', function ($query) {
                $query->whereBetween('created_at', [now()->subYears(2), now()->subYear()]);
            })
            ->when(($filters['years_in_business'] ?? null) === '3-5', function ($query) {
                $query->whereBetween('created_at', [now()->subYears(5), now()->subYears(2)]);
            })
            ->when(($filters['years_in_business'] ?? null) === '6-10', function ($query) {
                $query->whereBetween('created_at', [now()->subYears(10), now()->subYears(5)]);
            })
            ->when(($filters['years_in_business'] ?? null) === '10+', function ($query) {
                $query->where('created_at', '<', now()->subYears(10));
            })
            ->when(! empty($filters['radius']) && isset($filters['lat']) && isset($filters['lng']), function ($query) use ($filters) {
                $lat = (float) $filters['lat'];
                $lng = (float) $filters['lng'];
                $radius = (float) $filters['radius'];
                $query->where(function ($q) use ($lat, $lng, $radius) {
                    $q->whereNotNull('location_latitude')
                        ->whereNotNull('location_longitude')
                        ->whereRaw(
                            '(6371 * acos(cos(radians(?)) * cos(radians(location_latitude)) * cos(radians(location_longitude) - radians(?)) + sin(radians(?)) * sin(radians(location_latitude)))) <= ?',
                            [$lat, $lng, $lat, $radius]
                        );
                });
            })
            ->when(! empty($filters['view']) && $filters['view'] === 'map', function ($query) {
                $query->orderBy('average_rating', 'desc');
            }, function ($query) {
                $query->orderByDesc('average_rating');
            });

        $providers = $providersQuery->paginate(12)->withQueryString();

        $categories = ServiceCategory::where('is_active', true)->orderBy('sort_order')->get();

        if (! empty($filters['view']) && $filters['view'] === 'map') {
            $mapProviders = $providers->get()->filter(fn ($p) => $p->location_latitude && $p->location_longitude)->values();

            return view('providers.index.map', compact('providers','categories','filters','mapProviders'));
        }

        return view('providers.index.index',compact('providers','categories','filters'));
    }
}
