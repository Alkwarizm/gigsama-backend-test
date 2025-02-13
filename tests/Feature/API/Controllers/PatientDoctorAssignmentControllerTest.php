<?php

use App\Enums\UserRole;
use App\Http\Controllers\API\V1\PatientDoctorAssignmentController;
use App\Models\User;

it('assigns a patient to a doctor', function () {
    $patient = User::factory()->create(['role' => UserRole::PATIENT]);
    $doctor = User::factory()->create(['role' => UserRole::DOCTOR]);

    $data = [
        'doctor_id' => $doctor->id,
    ];

    $this->actingAs($patient)->postJson(action(PatientDoctorAssignmentController::class), $data)
        ->assertCreated()
        ->assertJson([
            'message' => 'Doctor assigned successfully',
            'data' => [
                'id' => $doctor->id,
                'name' => $doctor->name,
                'email' => $doctor->email,
                'role' => UserRole::DOCTOR->value,
            ],
        ]);

    expect($patient->doctor()->count())->toBe(1)
        ->and($patient->hasDoctor())->toBeTrue();

    $this->assertDatabaseHas('doctor_patient', [
        'patient_id' => $patient->id,
        'doctor_id' => $doctor->id,
    ]);
});

it('retrieves a doctor\'s list of patients', function () {
    $doctor = User::factory()->create(['role' => UserRole::DOCTOR]);
    $patients = User::factory()->count(3)->create(['role' => UserRole::PATIENT]);

    $patients->each(function ($patient) use ($doctor) {
        $patient->doctor()->save($doctor);
    });

    $this->actingAs($doctor)->getJson(action([PatientDoctorAssignmentController::class, 'index']))
        ->assertOk()
        ->assertJsonCount(3, 'data')
        ->assertJsonStructure([
            'message',
            'data' => [
                '*' => [
                    'id',
                    'name',
                    'email',
                    'role',
                ],
            ],
        ]);
});
