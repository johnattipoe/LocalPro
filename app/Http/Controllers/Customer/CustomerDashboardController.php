<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\ServiceRequest;
use Illuminate\Http\Request;

class CustomerDashboardController extends Controller
{
    public function index(Request $request)
    {
        $requests = ServiceRequest::with(['provider.user','category'])->where('customer_id',$request->user()->id)->latest();
        $stats = ['open'=>(clone $requests)->whereIn('status',['pending','accepted'])->count(),'completed'=>(clone $requests)->where('status','completed')->count()];
        $recent = (clone $requests)->limit(5)->get();
        return view('customer.dashboard', compact('stats','recent'));
    }
}
