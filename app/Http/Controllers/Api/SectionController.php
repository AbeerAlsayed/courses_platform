<?php

namespace App\Http\Controllers\Api;

use App\Domains\Courses\Actions\Sections\CreateSectionAction;
use App\Domains\Courses\Actions\Sections\DeleteSectionAction;
use App\Domains\Courses\Actions\Sections\UpdateSectionAction;
use App\Domains\Courses\DTOs\SectionData;
use App\Domains\Courses\Http\Resources\SectionResource;
use App\Domains\Courses\Models\Course;
use App\Domains\Courses\Models\Section;
use App\Http\Controllers\Controller;
use App\Http\Requests\Sections\StoreSectionRequest;
use App\Http\Requests\Sections\UpdateSectionRequest;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class SectionController extends Controller
{
    use AuthorizesRequests;

    public function index(Course $course)
    {
        $sections = $course->sections()->orderBy('order')->paginate(10);
        return SectionResource::collection($sections);
    }

    public function show(Course $course, Section $section)
    {
        return new SectionResource($section);
    }

    public function store(Course $course, StoreSectionRequest $request, CreateSectionAction $action)
    {
        $this->authorize('create', Section::class);
        $data = SectionData::fromArray($request->validated());
        $section = $action->execute($course, $data);
        return successResponse('Section created successfully', new SectionResource($section));
    }

    public function update(Course $course, Section $section, UpdateSectionRequest $request, UpdateSectionAction $action)
    {
        $this->authorize('update', $section);
        $data = SectionData::fromArray($request->validated());
        $section = $action->execute($section, $data);
        return successResponse('Section updated successfully', new SectionResource($section));
    }

    public function destroy(Course $course, Section $section, DeleteSectionAction $action)
    {
        $this->authorize('delete', $section);
        $action->execute($section);
        return successResponse('Section deleted successfully');
    }
}
