<?php

use App\Actions\CreateNoteAction;
use App\Events\NoteCreated;
use App\Models\User;
use Illuminate\Support\Facades\Event;

it('fires a note created event', function () {
    Event::fake([NoteCreated::class]);

    $doctor = User::factory()->doctor()->create();
    $patient = User::factory()->patient()->create();
    $action = new CreateNoteAction;

    Event::assertNotDispatched(NoteCreated::class);

    $action->execute($doctor, [
        'patient_id' => $patient->id,
        'content' => 'notes content',
    ]);

    Event::assertDispatched(NoteCreated::class);
});
