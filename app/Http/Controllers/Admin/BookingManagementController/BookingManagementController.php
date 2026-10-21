<?php

namespace App\Http\Controllers\Admin\BookingManagementController;

use App\Http\Controllers\Controller\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;

class BookingManagementController extends Controller
{
    public function index(Request $request)
    {
        $filters = $request->validate([
            'status' => ['nullable', 'in:pending,accepted,declined,completed,cancelled'],
        ]);

        $bookings = Booking::with(['customer', 'provider.user', 'serviceRequest.category'])
            ->when($filters['status'] ?? null, fn ($query, $status) => $query->where('status', $status))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.bookings.index', compact('bookings'));
    }
}
