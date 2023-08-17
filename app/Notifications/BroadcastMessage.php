<?php

namespace App\Notifications;

use App\Models\Broadcast;
use App\Services\NotificationChannels\SmsMessage;
use App\Services\NotificationChannels\SmsNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeEncrypted;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BroadcastMessage extends Notification implements ShouldQueue, ShouldBeEncrypted
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public Broadcast $broadcast,
    ) {
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        $channels = ['database'];
        if (in_array('sms', $this->broadcast->channels) && $notifiable->phone_verified_at) {
            $channels[] = SmsNotification::class;
        }

        if (in_array('mail', $this->broadcast->channels) && $notifiable->email_verified_at) {
            $channels[] = 'mail';
        }

        if (in_array('push', $this->broadcast->channels)) {
            // $channels[] = 'mail';
        }

        return $channels;
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $mail = (new MailMessage)
            ->subject($this->broadcast->title)
            ->line($this->broadcast->message);

        if ($this->broadcast->hasMedia('attachment')) {
            $mail->attach($this->broadcast->getFirstTemporaryUrl(now()->addHour(), 'attachment'));
        }

        return $mail;
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'message' => $this->broadcast->message . $this->getMediaUrl(),
            'title' => $this->broadcast->title,
        ];
    }

    public function toSms(object $notifiable): SmsMessage
    {
        return new SmsMessage(
            to: $notifiable?->phone_number,
            message: $this->broadcast->message . $this->getMediaUrl(),
        );
    }

    private function getMediaUrl()
    {
        $url = $this->broadcast->hasMedia('attachment') ?
            $this->broadcast->getFirstTemporaryUrl(now()->addDays(6), 'attachment') : null;

        return is_null($url) ? '' : "\nLink: $url";
    }
}
