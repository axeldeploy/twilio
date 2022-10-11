<?php

namespace Axeldeploy\Twilio;

use Illuminate\Notifications\Notification;

class SmsChannel
{
    public function send($notifiable, Notification $notification)
    {
        $message = $notification->toSms($notifiable);
        $message->send();
    }
}