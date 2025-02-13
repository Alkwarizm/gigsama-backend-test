<?php

namespace Tests\Factories\Requests;

class PatientDataRequest
{
    public static function new(): self
    {
        return new self();
    }

    public function create(array $extra = []): array
    {
        return $extra + [
            'name' => 'John Doe',
            'email' => 'johndoe@gmail.com',
            'password' => 'Jungf@123#!',
            'password_confirmation' => 'Jungf@123#!',
        ];
    }
}
