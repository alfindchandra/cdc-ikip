<?php

namespace App\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SendOtpNotification extends Notification
{

    /**
     * Create a new notification instance.
     */
    public function __construct(public string $otp)
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
        return (new MailMessage)
            ->subject('OTP Verifikasi Email - CDC IKIP PGRI Bojonegoro')
            ->greeting("Halo {$notifiable->name}!")
            ->line('Anda telah mendaftar di sistem CDC IKIP PGRI Bojonegoro.')
            ->line('Gunakan kode OTP berikut untuk memverifikasi email Anda:')
            ->line('')
            ->line("**{$this->otp}**")
            ->line('')
            ->line('Kode OTP ini berlaku selama 10 menit.')
            ->line('Jika Anda tidak melakukan registrasi, abaikan email ini.')
            ->action('Verifikasi Sekarang', route('otp.verify.show'))
            ->line('Terima kasih telah menggunakan layanan kami!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'otp' => $this->otp,
        ];
    }
}
