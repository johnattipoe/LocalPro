<?php

namespace App\Http\Controllers\Provider;

use App\Http\Controllers\Controller;
use App\Models\ServiceCategory;
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
            'starting_price' => ['nullable', 'numeric', 'min:0', 'max:999999'],
            'availability_days' => ['nullable','array'],
            'availability_days.*' => ['in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday'],
            'availability_start' => ['nullable','date_format:H:i'],
            'availability_end' => ['nullable','date_format:H:i','after:availability_start'],
        ]);
        $data['availability'] = !empty($data['availability_days']) ? [
            'days'=>array_values($data['availability_days']),
            'start'=>$data['availability_start'] ?? null,
            'end'=>$data['availability_end'] ?? null,
        ] : null;
        unset($data['availability_days'],$data['availability_start'],$data['availability_end']);
        if ($profile->profession !== $data['profession']) {
            $data['verification_status'] = 'pending';
        }
        $profile->update($data);
        $request->user()->update(['city' => $data['city']]);
        return back()->with('status', 'Your professional profile has been updated.');
    }
}
