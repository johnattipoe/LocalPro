<?php

namespace App\Console\Commands;

use App\Models\Booking;
use App\Services\NotificationService\NotificationService;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SendBookingReminders extends Command
{
    protected $signature = 'bookings:send-reminders {--hours=24 : Hours ahead to check for upcoming bookings}';

    protected $description = 'Send automated reminders for upcoming bookings';

    public function __construct(private NotificationService $notifications)
    {
        parent::__construct();
    }

    public function handle(): int
    {
        $hours = (int) $this->option('hours');
        $from = Carbon::now()->addHours($hours);
        $to = $from->copy()->addHour();

        $bookings = Booking::with(['customer', 'provider.user', 'serviceRequest.category'])
            ->where('scheduled_at', '>=', $from)
            ->where('scheduled_at', '<=', $to)
            ->where('status', 'accepted')
            ->whereNull('reminder_sent_at')
            ->get();

        $count = 0;
        foreach ($bookings as $booking) {
            $this->notifications->send(
                $booking->customer,
                'Upcoming booking reminder',
                'You have a booking for '.($booking->serviceRequest?->category?->name ?? 'a service').' on '.$booking->scheduled_at->format('M j, Y · g:i A').'.'
            );

            $booking->update(['reminder_sent_at' => Carbon::now()]);
            $count++;
        }

        $this->info("Sent {$count} booking reminder(s).");

        return self::SUCCESS;
    }
}
