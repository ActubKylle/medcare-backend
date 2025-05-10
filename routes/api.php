<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\PatientChartController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Public Auth routes (no authentication required)
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Simple test route to verify API is working
Route::get('/test', function () {
    return response()->json(['message' => 'API is working!']);
});

// All other routes - authentication is checked in the controllers
// Auth routes
Route::post('/logout', [AuthController::class, 'logout']);
Route::get('/user', [AuthController::class, 'user']);

// Doctor-specific patient routes
// Add this to your routes/api.php
Route::middleware('auth:sanctum')->get('/doctor/patients', [PatientChartController::class, 'getPatientsByDoctor']);
// Patient routes
Route::apiResource('patients', PatientChartController::class);

// Admin routes
Route::apiResource('admins', AdminController::class);

// Doctor routes
Route::apiResource('doctors', DoctorController::class);

// For development purposes only (optional)
if (app()->environment('local')) {
    Route::get('/dev/patients', [PatientChartController::class, 'index']);
    Route::get('/dev/doctors', [DoctorController::class, 'index']);
    Route::get('/dev/admins', [AdminController::class, 'index']);
}


