<?php

namespace App\Http\Controllers\Customer\CustomerProfileController;

use App\Http\Controllers\Controller\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CustomerProfileController extends Controller
{
    public function settings(Request $request)
    {
        return view('customer.settings.settings', ['user' => $request->user()]);
    }

    public function edit(Request $request)
    {
        return view('customer.profile.edit', ['user'=>$request->user()]);
    }

    public function update(Request $request)
    {
        $user = $request->user();
        $data = $request->validate([
            'name'=>['required','string','max:120'],
            'email'=>['required','email','max:180',Rule::unique('users','email')->ignore($user->id)],
            'phone'=>['nullable','string','max:32'],
            'city'=>['required','in:Ho,Hohoe,Keta,Aflao'],
        ]);
        $user->update($data);
        return back()->with('status','Your profile has been updated.');
    }
}
