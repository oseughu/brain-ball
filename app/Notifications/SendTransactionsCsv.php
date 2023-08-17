<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SendTransactionsCsv extends Notification implements ShouldQueue
{
    use Queueable;

    protected $csv;

    /**
     * Create a new notification instance.
     */
    public function __construct($csvFilePath)
    {
        $this->csv = $csvFilePath;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        if ($notifiable->email_verified_at) {
            return ['mail'];
        }
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Your transactions summary')
            ->line('Please, see the attachment below for a summary of your transactions')
            ->line('Thank you')
            ->attach($this->csv);
    }
    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'message' => 'Please, see the attachment below for a summary of your transactions',
            'title' => 'Your transactions summary',
        ];
    }
}
