<?php

namespace App\Console\Commands;

use App\Models\Booking;
use App\Models\Message;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendEmailDigest extends Command
{
    protected $signature = 'digest:send {--dry-run : Do not send emails, only log}';

    protected $description = 'Send weekly email digest of user activity';

    public function handle(): int
    {
        $dryRun = (bool) $this->option('dry-run');
        $start = now()->subWeek();

        $users = User::where('is_active', true)->get();

        $sent = 0;
        foreach ($users as $user) {
            $notifications = Notification::where('user_id', $user->id)
                ->where('created_at', '>=', $start)
                ->count();
            $messages = Message::where('recipient_id', $user->id)
                ->where('created_at', '>=', $start)
                ->count();
            $bookings = Booking::where('customer_id', $user->id)
                ->where('created_at', '>=', $start)
                ->count();

            if ($notifications === 0 && $messages === 0 && $bookings === 0) {
                continue;
            }

            $data = [
                'user' => $user,
                'notifications' => $notifications,
                'messages' => $messages,
                'bookings' => $bookings,
                'start' => $start->format('M j'),
                'end' => now()->format('M j, Y'),
            ];

            if ($dryRun) {
                $this->info("Would send digest to {$user->email}: {$notifications} notifications, {$messages} messages, {$bookings} bookings.");
            } else {
                Mail::send('emails.digest', $data, function ($message) use ($user) {
                    $message->to($user->email, $user->name)
                        ->subject('Your LocalPro weekly activity digest');
                });
            }

            $sent++;
        }

        $this->info("Sent {$sent} digest email(s).");

        return self::SUCCESS;
    }
}
