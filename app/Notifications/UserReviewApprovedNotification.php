<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class UserReviewApprovedNotification extends Notification
{
    use Queueable;

    private string $bookTitle;
    /**
     * Summary of __construct
     * @param string $bookTitle
     */
    public function __construct(string $bookTitle)
    {
        $this->bookTitle = $bookTitle;
    }
    /**
     * Summary of via
     * @param mixed $notifiable
     * @return string[]
     */
    public function via($notifiable): array
    {
        return ['mail'];
    }
    /**
     * Summary of toMail
     * @param mixed $notifiable
     * @return MailMessage
     */
    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('تم قبول تقييمك 🎉')
            ->greeting('مرحباً ' . $notifiable->name)
            ->line("تمت الموافقة على تقييمك لكتاب: {$this->bookTitle}")
            ->line('شكراً لمشاركتك رأيك معنا.');
    }
}