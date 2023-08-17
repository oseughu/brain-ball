<?php

namespace App\Notifications;

use App\Services\NotificationChannels\SmsMessage;
use App\Services\NotificationChannels\SmsNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ResetPin extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public string $code,)
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
            ->subject('Here is your pin reset code')
            ->line('Please, use the code below to reset your pin')
            ->line($this->code)
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
            'message' => "Your pin reset code is {$this->code}",
            'title' => 'Reset PIN',
        ];
    }

    public function toSms(object $notifiable): SmsMessage
    {
        return new SmsMessage(
            to: $notifiable?->phone_number,
            message: "Your pin reset code is {$this->code}",
        );
    }
}
