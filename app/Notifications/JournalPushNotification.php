<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

// These classes will only be used when the package is installed.
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;

class JournalPushNotification extends Notification
{
    use Queueable;

    public function __construct(
        public string $title,
        public string $body,
        public ?string $url = null,
        public ?array $data = []
    ) {}

    public function via($notifiable): array
    {
        if (!class_exists(WebPushChannel::class)) {
            return [];
        }
        return [WebPushChannel::class];
    }

    public function toWebPush($notifiable, $notification = null)
    {
        $message = (new WebPushMessage)
            ->title($this->title)
            ->icon(asset('favicon.ico'))
            ->body($this->body)
            ->tag('journal-updates')
            ->data(array_merge([
                'url' => $this->url ?: url('/'),
            ], $this->data))
            ->ttl(3600);

        return $message;
    }
}
