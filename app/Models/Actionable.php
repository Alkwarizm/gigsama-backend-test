<?php

namespace App\Models;

use App\Enums\ActionableStatus;
use App\Enums\ActionableType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Actionable extends Model
{
    use HasFactory;

    protected $guarded = [
        'id',
    ];

    protected function casts(): array
    {
        return [
            'type' => ActionableType::class,
            'status' => ActionableStatus::class,
        ];
    }

    public function note(): BelongsTo
    {
        return $this->belongsTo(Note::class);
    }

    public function reminder(): HasOne
    {
        return $this->hasOne(Reminder::class)->latestOfMany();
    }
}
