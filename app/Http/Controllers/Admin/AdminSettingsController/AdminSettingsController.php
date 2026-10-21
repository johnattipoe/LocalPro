<?php

namespace App\Http\Controllers\Admin\AdminSettingsController;

use App\Http\Controllers\Controller\Controller;

class AdminSettingsController extends Controller
{
    public function index()
    {
        return view('admin.settings.index', [
            'appName' => config('app.name', 'LocalPro'),
            'paymentConfigured' => filled(config('services.paystack.secret_key')) && filled(config('services.paystack.public_key')),
            'googleConfigured' => filled(config('services.google.client_id')) && filled(config('services.google.client_secret')),
        ]);
    }
}
