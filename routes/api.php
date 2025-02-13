<?php

use App\Http\Controllers\API\V1\DoctorNotesController;
use App\Http\Controllers\API\V1\DoctorRegisterController;
use App\Http\Controllers\API\V1\PatientDoctorAssignmentController;
use App\Http\Controllers\API\V1\PatientRegisterController;
use Illuminate\Support\Facades\Route;


Route::prefix('v1')
    ->group(function () {
        Route::prefix('doctors')
            ->group(function () {
                Route::group(['prefix' => 'auth'], function () {
                    Route::post('register', DoctorRegisterController::class);

                    Route::middleware('auth:sanctum')
                        ->group(function () {
                            Route::get('patients', [PatientDoctorAssignmentController::class, 'index']);
                            Route::post('notes', DoctorNotesController::class);
                        });
                });
            });

        Route::prefix('patients')
            ->group(function () {
                Route::group(['prefix' => 'auth'], function () {
                    Route::post('register', PatientRegisterController::class);
                });

                Route::middleware('auth:sanctum')
                    ->group(function () {
                        Route::post('assign-doctor', PatientDoctorAssignmentController::class);
                    });
            });
    });
