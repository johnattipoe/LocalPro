<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ServiceRequest;
use Illuminate\Http\Request;

class BookingManagementController extends Controller
{
    public function index(Request $request)
    {
        $requests = ServiceRequest::with(['customer','provider.user','category'])
            ->when($request->filled('status'), fn ($q) => $q->where('status',$request->query('status')))
            ->latest()->paginate(20)->withQueryString();
        return view('admin.bookings.index', compact('requests'));
    }
}
