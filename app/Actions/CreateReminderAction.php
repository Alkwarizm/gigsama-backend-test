<?php

namespace App\Actions;

use App\Models\Actionable;

class CreateReminderAction
{
    public function execute(Actionable $actionable): void
    {
        $actionable->reminder()->create([
            'counts' => 0,
        ]);
    }
}
