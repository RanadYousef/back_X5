<?php
/*
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class EmployeeActionNotification extends Notification
{
    use Queueable;

    private string $title;
    private string $message;

    public function __construct(string $title, string $message)
    {
        $this->title   = $title;
        $this->message = $message;
    }

    /**
     * قناة الإرسال
     *//*
    public function via($notifiable): array
    {
        return ['mail'];
    }

    /**
     * محتوى الإيميل
     *//*
    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject($this->title)
            ->greeting('مرحباً ' . $notifiable->name)
            ->line($this->message)
            ->line('يرجى مراجعة النظام.');
    }
}