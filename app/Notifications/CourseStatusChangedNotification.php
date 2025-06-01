<?php

namespace App\Notifications;

use App\Domains\Courses\Models\Course;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CourseStatusChangedNotification extends Notification
{
    public function __construct(public Course $course) {}

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Course Status Updated')
            ->greeting("Hello {$notifiable->name},")
            ->line("The status of your course \"{$this->course->title}\" has been changed to \"{$this->course->status->value}\".")
            ->action('View Course', url("/courses/{$this->course->slug}"))
            ->line('Thank you for using our platform!');
    }


}
