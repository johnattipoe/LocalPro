<?php

namespace App\Http\Controllers;

use App\Models\ProviderProfile;
use App\Models\ServiceCategory;
use Illuminate\Http\Request;

class ProviderSearchController extends Controller
{
    public function index(Request $request)
    {
        $filters = $request->validate([
            'q'=>['nullable','string','max:120'],
            'city'=>['nullable','in:Ho,Hohoe,Keta,Aflao'],
            'category'=>['nullable','string','max:120'],
            'min_rating'=>['nullable','numeric','between:0,5'],
            'max_price'=>['nullable','numeric','min:0','max:999999'],
        ]);
        $providers = ProviderProfile::with(['user','services'=>fn($q)=>$q->where('is_active',true)])
            ->where('verification_status','approved')
            ->whereHas('user',fn($q)=>$q->where('is_active',true))
            ->when(!empty($filters['q']),fn($q)=>$q->where('profession','like','%'.$filters['q'].'%'))
            ->when(!empty($filters['category']),fn($q)=>$q->where('profession','like','%'.$filters['category'].'%'))
            ->when(!empty($filters['city']),fn($q)=>$q->where('city',$filters['city']))
            ->when(isset($filters['min_rating']),fn($q)=>$q->where('average_rating','>=',$filters['min_rating']))
            ->when(isset($filters['max_price']),fn($q)=>$q->whereNotNull('starting_price')->where('starting_price','<=',$filters['max_price']))
            ->orderByDesc('average_rating')->paginate(12)->withQueryString();
        $categories = ServiceCategory::where('is_active',true)->orderBy('sort_order')->get();
        return view('providers.index',compact('providers','categories','filters'));
    }
}
