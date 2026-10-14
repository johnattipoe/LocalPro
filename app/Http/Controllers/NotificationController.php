<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        abort_unless($request->user()->is_active,403);
        $notifications = Notification::where('user_id',$request->user()->id)->latest()->paginate(20);
        Notification::where('user_id',$request->user()->id)->whereNull('read_at')->update(['read_at'=>now()]);
        return view('notifications.index',compact('notifications'));
    }
}
