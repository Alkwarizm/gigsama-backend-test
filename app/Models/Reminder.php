<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reminder extends Model
{
    use HasFactory;

    protected $guarded = [
        'id',
    ];

    public function actionable(): BelongsTo
    {
        return $this->belongsTo(Actionable::class);
    }
}
