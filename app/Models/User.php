<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Enums\UserRole;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are not mass assignable.
     *
     * @var list<string>
     */
    protected $guarded = [
        'id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'role' => UserRole::class,
            'password' => 'hashed',
        ];
    }

    public function patients(): BelongsToMany
    {
        return $this->belongsToMany(
            related: User::class,
            table: 'doctor_patient',
            foreignPivotKey: 'doctor_id',
            relatedPivotKey: 'patient_id'
        )->as('assignments')->withTimestamps();
    }

    public function doctor(): BelongsToMany
    {
        return $this->belongsToMany(
            related: User::class,
            table: 'doctor_patient',
            foreignPivotKey: 'patient_id',
            relatedPivotKey: 'doctor_id'
        )->latest()->withTimestamps();
    }

    public function hasDoctor(): bool
    {
        if ($this->role === UserRole::DOCTOR) {
            return true;
        }

        return $this->doctor()->exists();
    }

    public function notes(): HasMany
    {
        return $this->hasMany(Note::class, 'doctor_id');
    }

    public function patientNotes(): HasMany
    {
        return $this->hasMany(Note::class, 'patient_id');
    }
}
