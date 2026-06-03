<?php

namespace App\Domains\Auth\Actions;

use App\Domains\Auth\DTOs\UpdateInstructorStatusDTO;
use App\Domains\Auth\Models\Instructor;
use App\Notifications\InstructorStatusUpdatedNotification;

class UpdateInstructorStatusAction
{
    public function execute(
        Instructor $instructor,
        UpdateInstructorStatusDTO $dto
    ): void {

        if ($instructor->status->isTerminal()) {
            throw new \DomainException(
                "Cannot change a terminal instructor status."
            );
        }

        $instructor->update([
            'status' => $dto->status,
        ]);

        $instructor
            ->loadMissing('user')
            ->user
            ->notify(
                new InstructorStatusUpdatedNotification(
                    $dto->status
                )
            );
    }
}
