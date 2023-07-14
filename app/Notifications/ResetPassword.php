<?php

namespace App\Notifications;

use Illuminate\Support\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Config;

class ResetPassword extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public $token)
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
        $expires = Carbon::now('PST')->addMinutes(2);
        $frontUrl = Config::get('app.front_url');

        return (new MailMessage())
        ->from('no-reply@moviequotes.ge', 'Movie Quotes')
                    ->subject('Password reset link')
        ->view('confirmation-password', [
            'url' => $frontUrl.'/new-password/?token='.$this->token.'&email='.request()->email.'&expires='.$expires,
            'user'=> $notifiable->first_name
        ]);
    }


}
