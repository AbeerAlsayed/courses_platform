<?php

namespace App\Domains\Auth\Actions;

use App\Domains\Auth\DTOs\UpdateInstructorStatusDTO;
use App\Domains\Auth\Models\Instructor;
use App\Notifications\InstructorStatusUpdatedNotification;

class UpdateInstructorStatusAction
{
    public function execute(UpdateInstructorStatusDTO $dto): void
    {
        $instructor = Instructor::findOrFail($dto->instructorId);

        $instructor->update([
            'status' => $dto->status,
        ]);

        $instructor->user->notify(new InstructorStatusUpdatedNotification($dto->status));
    }
}
