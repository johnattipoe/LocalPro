@extends('layouts.admin')

@section('title', 'Support inbox')

@section('content')
<section class="admin-content">
    <div class="eyebrow muted">CUSTOMER CARE</div>
    <h1>Support inbox</h1>
    <p class="request-intro">Review account questions, verification concerns, and booking problems. Keep replies clear, factual, and tied to the case history.</p>

    @if(session('status'))<div class="flash-note">{{ session('status') }}</div>@endif

    <div class="admin-table-wrap">
        <table class="admin-table">
            <thead><tr><th>Received</th><th>Contact</th><th>Topic / job</th><th>Message</th><th>Update case</th></tr></thead>
            <tbody>
                @forelse($tickets as $ticket)
                    <tr>
                        <td>{{ $ticket->created_at->format('M j, Y') }}<br><small>{{ ucfirst(str_replace('_', ' ', $ticket->status)) }}</small></td>
                        <td><strong>{{ $ticket->name }}</strong><br><a href="mailto:{{ $ticket->email }}">{{ $ticket->email }}</a></td>
                        <td>{{ ucfirst(str_replace('_', ' ', $ticket->topic)) }}<br>
                            @if($ticket->serviceRequest)<a href="{{ route('admin.bookings.index') }}">Request #{{ $ticket->service_request_id }}</a>@else<small>General support</small>@endif
                        </td>
                        <td class="support-message">{{ $ticket->message }}@if($ticket->admin_response)<hr><strong>Previous reply</strong><br>{{ $ticket->admin_response }}@endif</td>
                        <td>
                            <form method="POST" action="{{ route('admin.support.update', $ticket) }}" class="support-update-form">
                                @csrf @method('PATCH')
                                <label>Status<select name="status"><option value="open" @selected($ticket->status === 'open')>Open</option><option value="in_progress" @selected($ticket->status === 'in_progress')>In progress</option><option value="resolved" @selected($ticket->status === 'resolved')>Resolved</option></select></label>
                                <label>Reply by email<textarea name="admin_response" rows="3" maxlength="5000" placeholder="Write an update or resolution">{{ old('admin_response', $ticket->admin_response) }}</textarea></label>
                                <button class="button button-small" type="submit">Save and send</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5">No support cases have been submitted.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    {{ $tickets->links() }}
</section>
@endsection
