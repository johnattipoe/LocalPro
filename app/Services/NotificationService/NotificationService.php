<?php

namespace App\Services\NotificationService;

use App\Models\Notification;
use App\Models\User;

class NotificationService
{
    public function __construct(private SmsService $sms, private PushService $push) {}

    public function send(User $recipient, string $title, string $body, array $channels = ['in_app']): Notification
    {
        $notification = Notification::create([
            'user_id' => $recipient->id,
            'title' => $title,
            'body' => $body,
        ]);

        if (in_array('sms', $channels, true) && $recipient->sms_notifications_enabled && $recipient->phone) {
            $this->sms->send($recipient->phone, "{$title}: {$body}");
        }

        if (in_array('push', $channels, true)) {
            $this->push->send($title, $body, $recipient->id);
        }

        return $notification;
    }
}
