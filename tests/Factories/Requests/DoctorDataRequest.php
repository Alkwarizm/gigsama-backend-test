<?php

namespace Tests\Factories\Requests;

class DoctorDataRequest
{
    public static function new(): self
    {
        return new self;
    }

    public function create(array $extra = []): array
    {
        return $extra + [
            'name' => 'Doctor Doe',
            'email' => 'doctordoe@gmail.com',
            'password' => 'Jungf@123#!',
            'password_confirmation' => 'Jungf@123#!',
        ];
    }
}
