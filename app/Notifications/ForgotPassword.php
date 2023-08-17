<?php

namespace App\Notifications;

use App\Services\NotificationChannels\SmsMessage;
use App\Services\NotificationChannels\SmsNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ForgotPassword extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public string $passwordResetCode)
    {
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', SmsNotification::class];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Reset your password')
            ->line('Please, use the code below to reset your password')
            ->line($this->passwordResetCode)
            ->line('Thank you');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'message' => "Use this code to reset your password: {$this->passwordResetCode}",
            'title' => 'Reset your password',
        ];
    }

    public function toSms(object $notifiable): SmsMessage
    {
        return new SmsMessage(
            to: $notifiable?->phone_number,
            message: "Your password reset code is {$this->passwordResetCode}",
        );
    }
}
