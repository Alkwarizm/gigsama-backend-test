<?php

use App\Actions\CreateActionableAction;
use App\Models\Actionable;
use App\Models\Note;

it('creates a step actionable', function () {
    $data = [
        'description' => 'Step description',
        'type' => 'step',
    ];

    $note = Note::factory()->create();

    $step = (new CreateActionableAction)->execute($note, $data);

    expect($step)
        ->toBeInstanceOf(Actionable::class)
        ->description->toBe($data['description'])
        ->type->value->toBe($data['type'])
        ->status->value->toBe('pending');
});

it('creates a schedule actionable', function () {
    $data = [
        'description' => 'Schedule a stress test within the next 7 days',
        'type' => 'schedule',
        'schedule' => 'Every day at 9:00 AM, starting tomorrow, for the next 7 days',
        'cron_expression' => '0 9 * * *',
    ];

    $note = Note::factory()->create();

    $schedule = (new CreateActionableAction)->execute($note, $data);

    expect($schedule)
        ->toBeInstanceOf(Actionable::class)
        ->note->id->toBe($note->id)
        ->description->toBe($data['description'])
        ->type->value->toBe($data['type'])
        ->status->value->toBe('pending')
        ->schedule->toBe($data['schedule'])
        ->cron_expression->toBe($data['cron_expression']);
});
