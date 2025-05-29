<?php

namespace App\Http\Controllers\Admin;

use App\Domains\Auth\Models\Instructor;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Domains\Auth\Enums\InstructorStatus;
use Illuminate\Validation\Rule;
use App\Notifications\InstructorStatusUpdatedNotification;

class InstructorApprovalController extends Controller
{
    public function updateInstructorStatus(Request $request, $id)
    {
        $validated = validator($request->all(), [
            'status' => ['required', Rule::in([
                InstructorStatus::Approved->value,
                InstructorStatus::Rejected->value,
            ])],
        ])->validate();

        $instructor = Instructor::findOrFail($id);
        $instructor->update(['status' => $validated['status']]);

        // إشعار مشترك حسب الحالة
        $instructor->user->notify(new InstructorStatusUpdatedNotification($validated['status']));

        return response()->json([
            'message' => 'Instructor status updated successfully.',
        ]);
    }

}
