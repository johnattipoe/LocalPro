<?php

namespace App\Http\Controllers\Provider\ProviderAvailabilityController;

use App\Http\Controllers\Controller\Controller;
use Illuminate\Http\Request;

class ProviderAvailabilityController extends Controller
{
    public function edit(Request $request)
    {
        $profile = $request->user()->providerProfile;
        abort_unless($profile, 404);

        return view('provider.availability.edit', compact('profile'));
    }

    public function update(Request $request)
    {
        $profile = $request->user()->providerProfile;
        abort_unless($profile, 404);
        $data = $request->validate([
            'accepting_requests' => ['required', 'boolean'],
            'availability_days' => ['nullable', 'array'],
            'availability_days.*' => ['in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday'],
            'availability_start' => ['nullable', 'date_format:H:i'],
            'availability_end' => ['nullable', 'date_format:H:i', 'after:availability_start'],
        ]);

        if ($request->filled('availability_start') !== $request->filled('availability_end')) {
            return back()->withErrors(['availability_end' => 'Enter both a start time and an end time, or leave both blank.'])->withInput();
        }

        $profile->update([
            'accepting_requests' => (bool) $data['accepting_requests'],
            'availability' => ! empty($data['availability_days']) ? [
                'days' => array_values($data['availability_days']),
                'start' => $data['availability_start'] ?? null,
                'end' => $data['availability_end'] ?? null,
            ] : null,
        ]);

        return back()->with('status', 'Your weekly availability has been saved.');
    }
}
