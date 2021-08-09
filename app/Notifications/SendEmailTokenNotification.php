<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Lang;

class SendEmailTokenNotification extends Notification
{
    use Queueable;

    public $token;

    /**
     * Create a new notification instance.
     *@param $token
     * @return void
     */
    public function __construct($token)
    {
        $this->token = $token;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage())
            ->subject(Lang::get('Verify Email Token'))
            ->greeting(Lang::get('Welcome to V-school!'))
            ->line(Lang::get('Your e-mail verification token is **:token**. It expires in :count minutes.', ['count' => config('auth.verification.email.expire'), 'token' => $this->token]))
            ->line(Lang::get('If you did not request a verification token, no further action is required. Thank you.'));
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
