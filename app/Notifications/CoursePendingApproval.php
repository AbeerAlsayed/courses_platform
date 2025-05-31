<?php

namespace App\Notifications;

use App\Domains\Courses\Models\Course;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class CoursePendingApproval extends Notification
{
    use Queueable;

    public function __construct(public Course $course) {}

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'course_id'   => $this->course->id,
            'title'       => $this->course->title,
            'instructor'  => $this->course->instructor->user->name,
            'message'     => "A new course titled '{$this->course->title}' was submitted by instructor {$this->course->instructor->user->name} and is pending review.",
            'action_url'  => url("/admin/courses/{$this->course->id}"),
        ];
    }
}
