<?php

namespace App\Domains\Courses\Actions\Sections;

use App\Domains\Courses\Models\Section;
use App\Domains\Courses\DTOs\SectionData;


class UpdateSectionAction
{
    public function execute(Section $section, SectionData $data): Section
    {
        $section->update($data->toArray());
        return $section;
    }
}
