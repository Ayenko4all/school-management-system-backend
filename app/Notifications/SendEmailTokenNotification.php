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
    public $password;

    /**
     * Create a new notification instance.
     *@param $token
     * @param $password
     * @return void
     */
    public function __construct($token, $password)
    {
        $this->token = $token;
        $this->password = $password;
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
            ->line(Lang::get('Your e-mail verification token is **:token**. It expires in :count minutes.',
                ['count' => config('auth.verification.email.expire'), 'token' => $this->token]))
           ->line(Lang::get("Your temporary password is, {$this->password}"))
            ->line(Lang::get('Please you are advise to change your temporary password by using the forgetPassword link on login page.'))
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
