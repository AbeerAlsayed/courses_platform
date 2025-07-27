<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewCommentNotification extends Notification
{
    use Queueable;

    public function __construct(
        public string $studentName,
        public string $commentContent,
        public string $courseTitle,
    ) {}

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toDatabase($notifiable): array
    {
        return [
            'title' => "تعليق جديد على كورسك: {$this->courseTitle}",
            'body' => "{$this->studentName} كتب تعليق: '{$this->commentContent}'",
            'type' => 'comment',
        ];
    }
}
