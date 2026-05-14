<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ResetPasswordNotification extends Notification
{
    use Queueable;

    /**
     * Token reset password.
     */
    public string $token;

    public function __construct(string $token)
    {
        $this->token = $token;
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $url = url(route('password.reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ], false));

        return (new MailMessage)
            ->subject('Reset Kata Sandi - ' . config('app.name'))
            ->greeting('Halo!')
            ->line('Anda menerima email ini karena kami menerima permintaan reset kata sandi untuk akun Anda.')
            ->action('Reset Kata Sandi', $url)
            ->line('Link reset kata sandi ini akan kedaluwarsa dalam **60 menit**.')
            ->line('Jika Anda tidak meminta reset kata sandi, abaikan email ini. Tidak ada perubahan yang akan terjadi.')
            ->salutation('Salam,')
            ->salutation('Tim ' . config('app.name'));
    }
}
