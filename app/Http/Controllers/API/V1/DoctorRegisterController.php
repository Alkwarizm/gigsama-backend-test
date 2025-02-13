<?php

namespace App\Http\Controllers\API\V1;

use App\Actions\CreateDoctorAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\V1\RegisterDoctorRequest;
use App\Http\Resources\UserResource;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class DoctorRegisterController extends Controller
{
    public function __invoke(RegisterDoctorRequest $request, CreateDoctorAction $action): JsonResponse
    {
        $data = $request->validated();

        $patient = $action->execute($data);

        return response()->json([
            'message' => 'Patient registered successfully',
            'data' => new UserResource($patient),
            'meta' => [
                'token' => $patient->createToken($request->header('User-Agent'))->plainTextToken,
            ],
        ], Response::HTTP_CREATED);
    }
}
