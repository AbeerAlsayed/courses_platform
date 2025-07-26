<?php


namespace App\Http\Controllers\Api\Admin;

use App\Domains\Auth\Actions\UpdateInstructorStatusAction;
use App\Domains\Auth\DTOs\UpdateInstructorStatusDTO;
use App\Domains\Auth\Enums\InstructorStatus;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Enum;

class InstructorApprovalController extends Controller
{
    public function updateInstructorStatus(Request $request, $id, UpdateInstructorStatusAction $action)
    {
        $validated = validator($request->all(), [
            'status' => ['required', new Enum(InstructorStatus::class)],
        ])->validate();

        $dto = UpdateInstructorStatusDTO::fromArray([
            'instructor_id' => (int) $id,
            'status' => $validated['status'],
        ]);


        $action->execute($dto);

        return successResponse('Instructor status updated successfully.');
    }
}
