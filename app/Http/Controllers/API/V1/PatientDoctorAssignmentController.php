<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\V1\AssignPatientDoctorRequest;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PatientDoctorAssignmentController extends Controller
{
    public function __invoke(AssignPatientDoctorRequest $request)
    {
        $patient = $request->user();

        $doctor = $request->getDoctor();

        if ($patient->hasDoctor()) {
            return response()->json([
                'message' => 'Patient already has a doctor',
                'data' => new UserResource($patient->doctor),
            ], Response::HTTP_CONFLICT);
        }

        $patient->doctor()->save($doctor);

        $patient->save();

        return response()->json([
            'message' => 'Doctor assigned successfully',
            'data' => new UserResource($patient->doctor->first()),
        ], Response::HTTP_CREATED);
    }

    public function index(Request $request)
    {
        $doctor = $request->user();

        $patients = $doctor->patients;

        return response()->json([
            'message' => 'List of patients',
            'data' => UserResource::collection($patients),
        ]);

    }
}
