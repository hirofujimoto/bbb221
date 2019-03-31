<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Lang;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class CustomResetPassword extends \Illuminate\Auth\Notifications\ResetPassword
{
    use Queueable;

    public $token;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($token)
    {
        $this->token = $token;//
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
        if (static::$toMailCallback) {
            return call_user_func(static::$toMailCallback, $notifiable, $this->token);
        }

        return (new MailMessage)
            ->from(Lang::getFromJson('bbb221@xemem.com'))
            ->subject(Lang::getFromJson('パスワード再設定'))
            ->line(Lang::getFromJson('リクエストがありましたのでパスワード再設定の為のURLをお送りします。'))
            ->action(Lang::getFromJson('再設定'), url(config('app.url').route('password.reset', ['token' => $this->token], false)))
            ->line(Lang::getFromJson('このURLの有効期間は :mm 分です。',['mm' => config('auth.passwords.users.expire')]) )
            ->line(Lang::getFromJson('もしパスワード再設定をリクエストした覚えがなければこのメールを無視してください。'));
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
