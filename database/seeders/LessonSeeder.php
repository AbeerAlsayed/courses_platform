<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Domains\Courses\Models\Lesson;
use App\Domains\Courses\Models\Section;

class LessonSeeder extends Seeder
{
    public function run(): void
    {
        $sections = Section::with('course')->get(); // لتحميل الكورس المرتبط بالقسم مسبقًا

        if ($sections->isEmpty()) {
            $this->command->warn('No sections found. Please seed sections first.');
            return;
        }

        foreach ($sections as $section) {
            $courseId = $section->course_id;

            Lesson::create([
                'course_id'   => $courseId,
                'section_id'  => $section->id,
                'title'       => 'مقدمة عن الدورة',
                'description' => 'في هذا الدرس نقدم نظرة عامة عن الدورة.',
                'order'       => 1,
                'duration'    => 5,
                'is_free'     => true,
            ]);

            Lesson::create([
                'course_id'   => $courseId,
                'section_id'  => $section->id,
                'title'       => 'محتوى الدرس الأول',
                'description' => 'تفاصيل الدرس الأول.',
                'order'       => 2,
                'duration'    => 15,
                'is_free'     => false,
            ]);

            Lesson::create([
                'course_id'   => $courseId,
                'section_id'  => $section->id,
                'title'       => 'خاتمة القسم',
                'description' => 'تلخيص لأهم ما ورد في هذا القسم.',
                'order'       => 3,
                'duration'    => 10,
                'is_free'     => false,
            ]);
        }

        $this->command->info('Lessons seeded successfully.');
    }
}
