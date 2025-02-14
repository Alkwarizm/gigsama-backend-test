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

it('retrieves a note with its actionable steps', function () {
    $doctor = User::factory()->create(['role' => UserRole::DOCTOR]);
    $patient = User::factory()->create(['role' => UserRole::PATIENT]);
    $note = Note::factory()->create([
        'patient_id' => $patient->id,
        'doctor_id' => $doctor->id,
    ]);

    $response = $this->actingAs($doctor, 'sanctum')
        ->getJson(action([DoctorNotesController::class, 'show'], $note->id));

    $response->assertOk()
        ->assertJson([
            'message' => 'Note retrieved successfully',
            'data' => [
                'id' => $note->id,
                'content' => $note->content,
                'actionable_steps' => [],
            ],
        ]);
});
