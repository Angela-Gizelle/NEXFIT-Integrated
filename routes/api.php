<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AssessmentController;
use App\Http\Controllers\MedicalHistoryController;
use App\Http\Controllers\ParqController;
use App\Http\Controllers\SlotController;

// Real-time slot availability (called by JS on slot picker)
Route::get('/slots/available', [SlotController::class, 'available']);

// Assessment & health forms (API variants for JS-heavy frontends)
Route::post('/assessment',     [AssessmentController::class, 'store']);
Route::post('/medical-history',[MedicalHistoryController::class, 'store']);
Route::post('/parq',           [ParqController::class, 'store']);
