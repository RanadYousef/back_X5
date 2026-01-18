<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class LowStockNotification extends Notification
{
    use Queueable;

    protected $book;

    public function __construct($book)
    {
        $this->book = $book;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'title' => 'تنبيه: نفاد الكمية',
            'message' => 'لقد وصلت كمية الكتاب ("' . $this->book->title . '") إلى الصفر.',
            'book_id' => $this->book->id,
        ];
    }
}