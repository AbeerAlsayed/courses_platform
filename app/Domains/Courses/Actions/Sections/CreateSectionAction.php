<?php

namespace App\Domains\Courses\Actions\Sections;

use App\Domains\Courses\Models\Course;
use App\Domains\Courses\Models\Section;
use App\Domains\Courses\DTOs\SectionData;
use App\Domains\Courses\Enums\CourseStatus;

class CreateSectionAction
{
    public function execute(Course $course, SectionData $data): Section
    {
        if ($course->status !== CourseStatus::Approved) {
            abort(403, 'Cannot add sections unless the course is approved.');
        }

        // توليد ترتيب تلقائي إذا لم يتم تحديده
        if ($data->order === 0) {
            $maxOrder = $course->sections()->max('order') ?? 0;
            $data = new SectionData(
                title: $data->title,
                order: $maxOrder + 1
            );
        }

        return $course->sections()->create($data->toArray());
    }
}
