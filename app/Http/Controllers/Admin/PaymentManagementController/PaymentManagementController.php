<?php

namespace App\Http\Controllers\Admin\PaymentManagementController;

use App\Http\Controllers\Controller\Controller;
use App\Models\Payment;

class PaymentManagementController extends Controller
{
    public function index()
    {
        $payments = Payment::with(['customer','booking.provider.user','booking.serviceRequest.category'])->latest()->paginate(20);
        $totals = [
            'paid' => Payment::where('status','paid')->sum('amount'),
            'pending' => Payment::where('status','pending')->sum('amount'),
            'count' => Payment::count(),
        ];
        return view('admin.payments.index',compact('payments','totals'));
    }
}
