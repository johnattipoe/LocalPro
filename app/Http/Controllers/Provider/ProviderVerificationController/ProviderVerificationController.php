<?php

namespace App\Http\Controllers\Provider\ProviderVerificationController;

use App\Http\Controllers\Controller\Controller;
use App\Services\ProviderVerificationService\ProviderVerificationService;
use Illuminate\Http\Request;

class ProviderVerificationController extends Controller
{
    public function __construct(private ProviderVerificationService $verification)
    {
    }

    public function index(Request $request)
    {
        $profile = $request->user()->providerProfile;
        abort_unless($profile, 404);
        $documents = $profile->verifications()->latest()->get();
        return view('provider.verification.index', compact('profile','documents'));
    }

    public function store(Request $request)
    {
        $profile = $request->user()->providerProfile;
        abort_unless($profile, 404);
        $data = $request->validate([
            'document_type'=>['required','in:ghana_card,passport,drivers_license,business_document'],
            'document'=>['required','file','mimes:jpg,jpeg,png,pdf','max:5120'],
        ]);
        $this->verification->submit($profile, $data['document_type'], $request->file('document'));
        return back()->with('status','Verification document securely submitted for review.');
    }
}
