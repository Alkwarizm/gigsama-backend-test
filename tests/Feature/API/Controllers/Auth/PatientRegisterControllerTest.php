<?php


use App\Http\Controllers\API\V1\PatientRegisterController;
use Tests\Factories\Requests\PatientDataRequest;

it('throws 422 for invalid data', function (string $field, array $data) {
    $this->postJson(action(PatientRegisterController::class), $data)
        ->assertStatus(422)
        ->assertJsonValidationErrors($field);
})->with([
    ['name', 'data' => PatientDataRequest::new()->create(['name' => ''])],
    ['email', 'data' => PatientDataRequest::new()->create(['email' => 'some@'])],
    ['password', 'data' => PatientDataRequest::new()->create(['password' => ''])],
    ['password', 'data' => PatientDataRequest::new()->create(['password_confirmation' => ''])],
]);

it('registers a patient', function () {
    $data = $this->postJson(action(PatientRegisterController::class), PatientDataRequest::new()->create())
        ->assertCreated()
        ->json();

    expect($data)
        ->toBeArray()
        ->toHaveKeys(['message', 'data', 'meta', 'meta.token']);
});
