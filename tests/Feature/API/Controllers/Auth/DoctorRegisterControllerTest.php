<?php


use App\Http\Controllers\API\V1\DoctorRegisterController;
use Tests\Factories\Requests\DoctorDataRequest;

it('throws 422 for invalid data', function (string $field, array $data) {
    $this->postJson(action(DoctorRegisterController::class), $data)
        ->assertStatus(422)
        ->assertJsonValidationErrors($field);
})->with([
    ['name', 'data' => DoctorDataRequest::new()->create(['name' => ''])],
    ['email', 'data' => DoctorDataRequest::new()->create(['email' => 'some@'])],
    ['password', 'data' => DoctorDataRequest::new()->create(['password' => ''])],
    ['password', 'data' => DoctorDataRequest::new()->create(['password_confirmation' => ''])],
]);

it('registers a doctor', function () {
    $data = $this->postJson(action(DoctorRegisterController::class), DoctorDataRequest::new()->create())
        ->assertCreated()
        ->json();

    expect($data)
        ->toBeArray()
        ->toHaveKeys(['message', 'data', 'meta', 'meta.token']);
});
