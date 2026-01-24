<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Auth\Notifications\VerifyEmail as BaseVerifyEmail;

class VerifyEmail extends BaseVerifyEmail implements ShouldQueue
{
    use Queueable;

    protected function verificationUrl($notifiable)
    {
        return parent::verificationUrl($notifiable);
    }

    protected function buildMailMessage($url)
    {
        return parent::buildMailMessage($url)
            ->subject('Verify Your Email Address')
            ->line('Please click the button below to verify your email address.')
            ->action('Verify Email Address', $url)
            ->line('If you did not create an account, no further action is required.');
    }
}
