<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class InstructorStatusUpdatedNotification extends Notification
{
//    use Queueable;

    protected string $status;

    public function __construct(string $status)
    {
        $this->status = $status;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $subject = $this->status === 'approved' ? 'Instructor Profile Approved' : 'Instructor Profile Rejected';
        $line = $this->status === 'approved'
            ? 'Congratulations! Your instructor profile has been approved.'
            : 'Unfortunately, your instructor profile has been rejected.';

        return (new MailMessage)
            ->subject($subject)
            ->line($line);
    }

}
