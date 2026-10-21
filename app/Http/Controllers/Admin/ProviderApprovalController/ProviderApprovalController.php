<?php

namespace App\Http\Controllers\Admin\ProviderApprovalController;

use App\Http\Controllers\Controller\Controller;
use App\Models\ProviderProfile;
use App\Models\ProviderVerification;
use App\Services\ProviderVerificationService\ProviderVerificationService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProviderApprovalController extends Controller
{
    public function __construct(private ProviderVerificationService $verification)
    {
    }

    public function index(Request $request)
    {
        $status = $request->query('status', 'pending');
        abort_unless(in_array($status, ['pending', 'approved', 'rejected', 'all'], true), 404);
        $providers = ProviderProfile::with(['user','verifications'])
            ->when($status !== 'all', fn ($query) => $query->where('verification_status', $status))
            ->latest()->paginate(15)->withQueryString();

        return view('admin.providers.index.index', compact('providers', 'status'));
    }

    public function update(Request $request, ProviderProfile $provider)
    {
        $data = $request->validate(['verification_status' => ['required', Rule::in(['approved', 'rejected'])]]);
        $this->verification->review($provider, $request->user(), $data['verification_status']);

        return redirect()->route('admin.providers.index')
            ->with('status', $provider->user->name . ' was ' . $data['verification_status'] . '.');
    }

    public function document(ProviderVerification $verification)
    {
        abort_unless($verification->exists, 404);
        return \Illuminate\Support\Facades\Storage::disk(config('filesystems.default'))
            ->download($verification->document_path);
    }
}
