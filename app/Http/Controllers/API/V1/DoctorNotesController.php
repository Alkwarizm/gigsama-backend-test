<?php

namespace App\Http\Controllers\API\V1;

use App\Actions\CreateNoteAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\V1\StoreNoteRequest;
use App\Http\Resources\NoteResource;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class DoctorNotesController extends Controller
{
    public function __invoke(StoreNoteRequest $request, CreateNoteAction $action): JsonResponse
    {
        $data = $request->validated();

        $note = $action->execute($request->user(), $data);

        return response()->json([
            'message' => 'Note created successfully',
            'data' => NoteResource::make($note),
        ], Response::HTTP_CREATED);
    }
}
