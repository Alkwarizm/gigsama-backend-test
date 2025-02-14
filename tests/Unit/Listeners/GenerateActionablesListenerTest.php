<?php

use App\Events\NoteCreated;
use App\Listeners\GenerateActionablesListener;
use App\Models\Note;
use Illuminate\Support\Facades\Event;

it('listens to note created event', function () {
    Event::fake([NoteCreated::class]);

    NoteCreated::dispatch($note = Note::factory()->create());

    Event::assertListening(NoteCreated::class, GenerateActionablesListener::class);
});
