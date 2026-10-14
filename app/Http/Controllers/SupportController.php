<?php

namespace App\Http\Controllers;

use App\Models\ServiceRequest;
use App\Models\SupportTicket;
use App\Models\User;
use App\Services\NotificationService;
use Illuminate\Http\Request;

class SupportController extends Controller
{
    public function __construct(private NotificationService $notifications)
    {
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:255'],
            'topic' => ['required', 'in:account,provider_verification,booking,payment,safety,other'],
            'service_request_id' => ['nullable', 'integer', 'exists:service_requests,id'],
            'message' => ['required', 'string', 'min:20', 'max:5000'],
        ]);

        $serviceRequest = null;
        if (! empty($data['service_request_id'])) {
            $serviceRequest = ServiceRequest::findOrFail($data['service_request_id']);
            abort_unless(
                $request->user() && in_array($request->user()->id, [
                    $serviceRequest->customer_id,
                    optional($serviceRequest->provider)->user_id,
                ], true),
                404
            );
        }

        $ticket = SupportTicket::create([
            'user_id' => $request->user()?->id,
            'service_request_id' => $serviceRequest?->id,
            'name' => $data['name'],
            'email' => $data['email'],
            'topic' => trim($data['topic']),
            'message' => $data['message'],
        ]);

        User::where('role', 'admin')->where('is_active', true)->get()->each(function (User $admin) use ($ticket) {
            $this->notifications->send(
                $admin,
                'New support case #' . $ticket->id,
                $ticket->topic . ' case submitted by ' . $ticket->name . '.'
            );
        });

        return redirect()->route('contact')->with('status', 'Your message has been sent to LocalPro Support. Keep an eye on your email for a reply.');
    }
}
