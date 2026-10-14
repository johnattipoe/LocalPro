<?php

namespace App\Http\Controllers\Provider;

use App\Http\Controllers\Controller;
use App\Models\ProviderService;
use App\Models\ServiceCategory;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProviderServiceController extends Controller
{
    public function index(Request $request)
    {
        $profile = $request->user()->providerProfile;
        abort_unless($profile, 404);
        $services = $profile->services()->with('category')->latest()->get();
        return view('provider.services.index', compact('services', 'profile'));
    }

    public function create(Request $request)
    {
        return view('provider.services.create', ['categories' => ServiceCategory::where('is_active', true)->orderBy('name')->get()]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $request->user()->providerProfile->services()->create($data);
        return redirect()->route('provider.services.index')->with('status', 'Service added to your profile.');
    }

    public function edit(Request $request, ProviderService $service)
    {
        abort_unless($service->provider_profile_id === $request->user()->providerProfile?->id, 404);
        $categories = ServiceCategory::where('is_active', true)->orderBy('name')->get();
        return view('provider.services.edit', compact('service', 'categories'));
    }

    public function update(Request $request, ProviderService $service)
    {
        abort_unless($service->provider_profile_id === $request->user()->providerProfile?->id, 404);
        $service->update($this->validated($request));
        return redirect()->route('provider.services.index')->with('status', 'Service updated.');
    }

    public function destroy(Request $request, ProviderService $service)
    {
        abort_unless($service->provider_profile_id === $request->user()->providerProfile?->id, 404);
        $service->delete();
        return redirect()->route('provider.services.index')->with('status', 'Service removed.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'service_category_id' => ['nullable', 'exists:service_categories,id'],
            'title' => ['required', 'string', 'max:150'],
            'description' => ['nullable', 'string', 'max:2000'],
            'pricing_type' => ['required', Rule::in(['fixed', 'hourly', 'quote'])],
            'price' => ['nullable', 'numeric', 'min:0', 'max:999999'],
            'is_active' => ['nullable', 'boolean'],
        ]) + ['is_active' => $request->boolean('is_active')];
    }
}
