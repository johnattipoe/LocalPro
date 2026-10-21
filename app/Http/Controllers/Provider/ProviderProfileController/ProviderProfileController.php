<?php

namespace App\Http\Controllers\Provider\ProviderProfileController;

use App\Http\Controllers\Controller\Controller;
use App\Models\ServiceCategory;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class ProviderProfileController extends Controller
{
    public function edit(Request $request)
    {
        $profile = $request->user()->providerProfile;
        abort_unless($profile, 404);
        return view('provider.profile.edit', ['profile' => $profile, 'categories' => ServiceCategory::where('is_active', true)->orderBy('name')->get()]);
    }

    public function update(Request $request)
    {
        $profile = $request->user()->providerProfile;
        abort_unless($profile, 404);
        $data = $request->validate([
            'profession' => ['required', 'string', 'max:120'],
            'bio' => ['nullable', 'string', 'max:2000'],
            'city' => ['required', 'in:Ho,Hohoe,Keta,Aflao'],
            'service_area' => ['nullable', 'string', 'max:255'],
            'location_address' => ['nullable', 'string', 'max:255'],
            'location_latitude' => ['nullable', 'numeric', 'between:-90,90', 'required_with:location_longitude'],
            'location_longitude' => ['nullable', 'numeric', 'between:-180,180', 'required_with:location_latitude'],
            'service_mode' => ['required', 'in:provider_location,customer_location,both'],
            'starting_price' => ['nullable', 'numeric', 'min:0', 'max:999999'],
            'availability_days' => ['nullable','array'],
            'availability_days.*' => ['in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday'],
            'availability_start' => ['nullable','date_format:H:i'],
            'availability_end' => ['nullable','date_format:H:i','after:availability_start'],
            'profile_photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);
        $photo = $data['profile_photo'] ?? null;
        unset($data['profile_photo']);
        if (array_key_exists('availability_days', $data) || array_key_exists('availability_start', $data) || array_key_exists('availability_end', $data)) {
            $data['availability'] = ! empty($data['availability_days']) ? [
                'days' => array_values($data['availability_days']),
                'start' => $data['availability_start'] ?? null,
                'end' => $data['availability_end'] ?? null,
            ] : null;
        }
        unset($data['availability_days'], $data['availability_start'], $data['availability_end']);
        if ($profile->profession !== $data['profession']) {
            $data['verification_status'] = 'pending';
        }
        if ($photo) {
            $disk = in_array(config('filesystems.default'), ['r2', 's3'], true)
                ? config('filesystems.default')
                : 'public';
            $path = $photo->store('provider-photos/'.$request->user()->id, $disk);

            if ($path) {
                if ($profile->profile_photo_path) {
                    Storage::disk($profile->profile_photo_disk ?: 'public')->delete($profile->profile_photo_path);
                }
                $data['profile_photo_path'] = $path;
                $data['profile_photo_disk'] = $disk;
            }
        }
        $profile->update($data);
        $request->user()->update(['city' => $data['city']]);
        return back()->with('status', 'Your professional profile has been updated.');
    }
}
