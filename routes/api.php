<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\TagController;
use App\Http\Controllers\Api\WebsiteController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

// Public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    // User profile and authentication
    Route::get('/user', [AuthController::class, 'profile']);
    Route::post('/logout', [AuthController::class, 'logout']);
    
    // Website routes
    Route::get('/websites', [WebsiteController::class, 'index']);
    Route::post('/websites', [WebsiteController::class, 'store']);
    Route::get('/websites/{website}', [WebsiteController::class, 'show']);
    Route::put('/websites/{website}', [WebsiteController::class, 'update']);
    Route::delete('/websites/{website}', [WebsiteController::class, 'destroy']);
    
    // Website related routes
    Route::get('/websites/{website}/logs', [WebsiteController::class, 'logs']);
    Route::get('/websites/{website}/screenshots', [WebsiteController::class, 'screenshots']);
    Route::post('/websites/{website}/check', [WebsiteController::class, 'checkNow']);
    
    // Website statistics
    Route::get('/stats/websites', [WebsiteController::class, 'stats']);
    
    // Tag routes
    Route::get('/tags', [TagController::class, 'index']);
    Route::post('/tags', [TagController::class, 'store']);
    Route::get('/tags/{tag}', [TagController::class, 'show']);
    Route::put('/tags/{tag}', [TagController::class, 'update']);
    Route::delete('/tags/{tag}', [TagController::class, 'destroy']);
}); 