<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\DatabaseMessage;

class NewInstructorRegistered extends Notification
{
    use Queueable;

    protected $instructor;

    public function __construct($instructor)
    {
        $this->instructor = $instructor;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'message' => 'A new instructor is pending approval: ' . $this->instructor->user->name,
            'instructor_id' => $this->instructor->id,
        ];
    }
}
