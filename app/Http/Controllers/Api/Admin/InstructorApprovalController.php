<?php


namespace App\Http\Controllers\Api\Admin;

use App\Domains\Auth\Actions\UpdateInstructorStatusAction;
use App\Domains\Auth\DTOs\UpdateInstructorStatusDTO;
use App\Domains\Auth\Enums\InstructorStatus;
use App\Domains\Auth\Models\Instructor;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class InstructorApprovalController extends Controller
{

    public function updateInstructorStatus(
        Request $request,
                $id,
        UpdateInstructorStatusAction $action
    ) {

        $validated = validator($request->all(), [
            'status' => [
                'required',
                Rule::in(
                    array_map(
                        fn($s) => $s->value,
                        InstructorStatus::assignableByAdmin()
                    )
                ),
            ],
        ])->validate();

        $dto = UpdateInstructorStatusDTO::fromArray([
            'instructor_id' => (int) $id,
            'status' => $validated['status'],
        ]);

        $instructor = Instructor::findOrFail($id);

        $action->execute($instructor, $dto);

        return successResponse(
            'Instructor status updated successfully.'
        );
    }}
