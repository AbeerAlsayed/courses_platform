<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Domains\Auth\Enums\InstructorStatus;
class InstructorStatusUpdatedNotification extends Notification
{
//    use Queueable;

    public function __construct(
        protected InstructorStatus $status
    ) {}

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $approved = $this->status === InstructorStatus::Approved;

        return (new MailMessage)
            ->subject(
                $approved
                    ? 'Instructor Profile Approved'
                    : 'Instructor Profile Rejected'
            )
            ->line(
                $approved
                    ? 'Congratulations! Your instructor profile has been approved.'
                    : 'Unfortunately, your instructor profile has been rejected.'
            );
    }
}
