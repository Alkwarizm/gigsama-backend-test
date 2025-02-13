<?php

use App\Actions\CreatePatientAction;
use App\Models\User;
use Tests\Factories\Requests\PatientDataRequest;

it('creates a patient in the database', function () {
    $data = PatientDataRequest::new()->create();
    $patient = (new CreatePatientAction)->execute($data);

    expect($patient)
        ->toBeInstanceOf(User::class)
        ->name->toBe($data['name'])
        ->email->toBe($data['email']);

    $this->assertDatabaseHas($patient::class, [
        'name' => $data['name'],
        'email' => $data['email'],
    ]);
});
