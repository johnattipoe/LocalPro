<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\ServiceRequest;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        return redirect()->route('customer.requests.index');
    }

    public function show(Request $request, ServiceRequest $booking)
    {
        abort_unless($booking->customer_id === $request->user()->id,404);
        return redirect()->route('customer.requests.show',$booking);
    }
}
