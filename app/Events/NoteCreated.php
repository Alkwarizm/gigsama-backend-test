<?php

namespace App\Events;

use App\Models\Note;
use Illuminate\Foundation\Events\Dispatchable;

class NoteCreated
{
    use Dispatchable;

    public function __construct(public Note $note)
    {
    }
}
