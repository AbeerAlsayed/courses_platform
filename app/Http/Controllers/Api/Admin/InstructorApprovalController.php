<?php


namespace App\Http\Controllers\Api\Admin;

use App\Domains\Auth\Actions\UpdateInstructorStatusAction;
use App\Domains\Auth\DTOs\UpdateInstructorStatusDTO;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class InstructorApprovalController extends Controller
{
    public function updateInstructorStatus(Request $request, $id, UpdateInstructorStatusAction $action)
    {
        $validated = validator($request->all(), [
            'status' => ['required'],
        ])->validate();

        $dto = new UpdateInstructorStatusDTO(
            instructorId: (int)$id,
            status: $validated['status']
        );

        $action->execute($dto);

        return successResponse('Instructor status updated successfully.');
    }
}
