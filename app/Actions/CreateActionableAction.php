<?php

namespace App\Actions;

use App\Enums\ActionableStatus;
use App\Enums\ActionableType;
use App\Models\Actionable;
use App\Models\Note;

class CreateActionableAction
{
    public function execute(Note $note, array $data): Actionable
    {
        $actionable = $note->actionable()->create([
            'description' => $data['description'],
            'type' => $data['type'],
            'status' => ActionableStatus::PENDING->value,
            'schedule' => $data['schedule'] ?? null,
            'cron_expression' => $data['cron_expression'] ?? null,
        ]);

        if ($actionable->type === ActionableType::SCHEDULE) {
            (new CreateReminderAction)->execute($actionable);
        }

        return $actionable;
    }
}
