<?php

namespace App\Http\Controllers\Admin\SupportTicketController;

use App\Http\Controllers\Controller\Controller;
use App\Models\SupportTicket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class SupportTicketController extends Controller
{
    public function index()
    {
        $tickets = SupportTicket::with(['user', 'serviceRequest'])
            ->latest()
            ->paginate(20);

        return view('admin.support.index', compact('tickets'));
    }

    public function update(Request $request, SupportTicket $ticket)
    {
        $data = $request->validate([
            'status' => ['required', 'in:open,in_progress,resolved'],
            'admin_response' => ['nullable', 'string', 'max:5000'],
        ]);

        $wasAnswered = filled($ticket->admin_response);
        $ticket->update(array_merge($data, [
            'resolved_at' => $data['status'] === 'resolved' ? now() : null,
        ]));

        if (filled($data['admin_response']) && (! $wasAnswered || $ticket->wasChanged('admin_response'))) {
            $supportEmail = config('mail.from.address');
            try {
                Mail::raw($data['admin_response'], function ($message) use ($ticket, $supportEmail) {
                    $message->to($ticket->email)
                        ->subject('LocalPro Support: ' . $ticket->topic);
                    if ($supportEmail) {
                        $message->replyTo($supportEmail, config('mail.from.name'));
                    }
                });
            } catch (\Throwable $exception) {
                report($exception);
                return back()->with('status', 'Ticket updated. Email delivery is not configured, so the response was saved but not emailed.');
            }
        }

        return back()->with('status', 'Support ticket updated.');
    }
}
