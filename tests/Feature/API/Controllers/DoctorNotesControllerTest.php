<?php

use App\Enums\UserRole;
use App\Http\Controllers\API\V1\DoctorNotesController;
use App\Models\Note;
use App\Models\User;

it('creates a note for a patient by a doctor', function () {
    $doctor = User::factory()->create(['role' => UserRole::DOCTOR]);
    $patient = User::factory()->create(['role' => UserRole::PATIENT]);
    $content = 'notes content';

    $response = $this->actingAs($doctor, 'sanctum')
        ->postJson(action(DoctorNotesController::class), [
            'patient_id' => $patient->id,
            'content' => $content,
        ]);

    $response->assertCreated();

    $this->assertDatabaseHas(Note::class, [
        'patient_id' => $patient->id,
        'doctor_id' => $doctor->id,
        'content' => $content,
    ]);
});
