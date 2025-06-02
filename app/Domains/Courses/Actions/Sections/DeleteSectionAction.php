<?php

namespace App\Domains\Courses\Actions\Sections;
use App\Domains\Courses\Models\Section;

class DeleteSectionAction
{
    public function execute(Section $section): void
    {
        $section->delete();
    }
}
