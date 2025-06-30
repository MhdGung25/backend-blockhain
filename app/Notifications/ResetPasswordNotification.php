<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ResetPasswordNotification extends Notification
{
    
    use Queueable;

    /**
     * Create a new notification instance.
     */

     public $token;

     public function __construct($token)
     {
         $this->token = $token; // <- INI WAJIB
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
    public function toMail($notifiable)
    {
        $resetUrl = url('/reset-password?token=' . $this->token . '&email=' . $notifiable->getEmailForPasswordReset());
    
        return (new MailMessage)
            ->subject('Reset Password Anda')
            ->line('Klik tombol berikut untuk mengatur ulang password:')
            ->action('Reset Password', $resetUrl)
            ->line('Jika Anda tidak meminta reset, abaikan email ini.');
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
