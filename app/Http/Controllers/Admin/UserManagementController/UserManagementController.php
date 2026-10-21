<?php

namespace App\Http\Controllers\Admin\UserManagementController;

use App\Http\Controllers\Controller\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserManagementController extends Controller
{
    public function index(Request $request)
    {
        $users = User::with('providerProfile')->when($request->filled('q'), function ($query) use ($request) {
            $term = '%' . $request->query('q') . '%';
            $query->where(fn ($q) => $q->where('name','like',$term)->orWhere('email','like',$term));
        })->orderBy('name')->paginate(20)->withQueryString();
        return view('admin.users.index', compact('users'));
    }

    public function toggle(Request $request, User $user)
    {
        abort_if($request->user()->is($user) || $user->role === 'admin', 403);
        $user->update(['is_active' => ! $user->is_active]);
        return back()->with('status', $user->name . ' account ' . ($user->is_active ? 'restored.' : 'suspended.'));
    }
}
