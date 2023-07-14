<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class VerifyEmailCustom extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $verificationUrl = $this->verificationUrl($notifiable);
        return (new MailMessage())
                    ->from('no-reply@moviequotes.ge', 'Movie Quotes')
                    ->subject('Please verify your email address')
                    ->view('verify-email', ['url' => $verificationUrl, 'user'=> $notifiable->first_name]);
    }



    protected function verificationUrl($notifiable): String
    {
        return env('FRONT_URL').'/email-verified/?token='.$notifiable->verification_token;
    }
}
