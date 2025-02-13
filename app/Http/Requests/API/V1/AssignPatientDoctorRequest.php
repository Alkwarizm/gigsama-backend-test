<?php

namespace App\Http\Requests\API\V1;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class AssignPatientDoctorRequest extends FormRequest
{
    public function getDoctor(): User
    {
        return User::findOrFail($this->input('doctor_id'));
    }

    public function rules(): array
    {
        return [
            'doctor_id' => ['required', 'exists:users,id'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
