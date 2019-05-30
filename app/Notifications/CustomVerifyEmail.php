<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Log;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class CustomVerifyEmail extends \Illuminate\Auth\Notifications\VerifyEmail
{
    use Queueable;

    protected $user;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
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
        $user_data = $notifiable->toArray();
        Log::info(sprintf("%s:%s", $user_data['email'], $this->verificationUrl($notifiable) ));

        if (static::$toMailCallback) {
            return call_user_func(static::$toMailCallback, $notifiable);
        }

        return (new MailMessage)
            ->from(Lang::getFromJson('bbb221@xemem.com'))
            ->subject(Lang::getFromJson('BBB221.NET登録確認'))
            ->line(Lang::getFromJson('メールアドレスを確認するため下のボタンをクリックしてください。'))
            ->action(
                Lang::getFromJson('確認'), $this->verificationUrl($notifiable)
            )
            ->line(Lang::getFromJson('もし参加申し込みをした覚えがない場合，このメールは無視してください。'));
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
