<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class UserReviewApprovedNotification extends Notification
{
    use Queueable;

    private string $bookTitle;

    public function __construct(string $bookTitle)
    {
        $this->bookTitle = $bookTitle;
    }

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('تم قبول تقييمك 🎉')
            ->greeting('مرحباً ' . $notifiable->name)
            ->line("تمت الموافقة على تقييمك لكتاب: {$this->bookTitle}")
            ->line('شكراً لمشاركتك رأيك معنا.');
    }
}