@component('mail::message')
# Your LocalPro Weekly Digest

Hi {{ $user->name }},

Here's a summary of your activity on LocalPro from {{ $start }} to {{ $end }}:

- **{{ $notifications }}** new notification(s)
- **{{ $messages }}** new message(s)
- **{{ $bookings }}** new booking(s)

Log in to LocalPro to catch up on all your requests, messages, and bookings.

[View your dashboard]({{ config('app.url') }})

Thanks,
The LocalPro Team
@endcomponent