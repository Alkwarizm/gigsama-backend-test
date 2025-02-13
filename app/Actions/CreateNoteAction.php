<?php

namespace App\Actions;

use App\Events\NoteCreated;
use App\Models\Note;
use App\Models\User;

class CreateNoteAction
{
    public function execute(User $user, array $data): Note
    {
        $note = $user->notes()->create($data);

        NoteCreated::dispatch($note);

        return $note;
    }
}
