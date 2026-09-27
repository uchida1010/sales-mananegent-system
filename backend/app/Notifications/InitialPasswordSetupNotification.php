<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class InitialPasswordSetupNotification extends Notification
{
    use Queueable;

    private string $token;

    /**
     * Create a new notification instance.
     */
    public function __construct(string $token)
    {
        $this->token = $token;
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

        $query = http_build_query([
            'token' => $this->token,
            'email' => $notifiable->email,
        ]);

        $url = config('app.frontend_url').'/password/setup?'.$query;

        $expire = config('auth.passwords.users.expire');

        return (new MailMessage)
            ->line('初期パスワードの設定案内を送ります')
            ->action('初期パスワードを設定する', $url)
            ->line("上記のリンクから初期パスワードを設定してください。このリンクの有効期限は{$expire}分です");
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
