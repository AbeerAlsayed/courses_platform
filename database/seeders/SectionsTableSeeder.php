<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Domains\Courses\Models\Section;
use App\Domains\Courses\Models\Course;

class SectionsTableSeeder extends Seeder
{
    public function run()
    {
        $courses = Course::all();

        if ($courses->isEmpty()) {
            $this->command->warn('⚠️ لا توجد كورسات. الرجاء تنفيذ CourseSeeder أولاً.');
            return;
        }

        foreach ($courses as $course) {
            Section::create([
                'course_id' => $course->id,
                'title'     => 'مقدمة',
                'order'     => 1,
            ]);

            Section::create([
                'course_id' => $course->id,
                'title'     => 'المحتوى الرئيسي',
                'order'     => 2,
            ]);

            Section::create([
                'course_id' => $course->id,
                'title'     => 'الخاتمة',
                'order'     => 3,
            ]);
        }

        $this->command->info('✅ تم إنشاء الأقسام لكل كورس بنجاح.');
    }
}
