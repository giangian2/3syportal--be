<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FileUploadController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SubmissionController;
use App\Models\User;
use App\Models\Submission;
use App\Http\Controllers\PasswordController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\GoogleDriveController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
// Public routes (free)
Route::post('/register', [AuthController::class, 'register']);

Route::post('/login', [AuthController::class, 'login']);

Route::post('/forgot-password', [PasswordController::class, 'send_reset_password_mail']);

Route::post('/reset-password', [PasswordController::class, 'reset_password']);

Route::post('/change-password', [PasswordController::class, 'change_password'])->middleware('auth:sanctum');

/*
// Account confirmation routes (token)
Route::group(['middleware'=> ['auth:sanctum']], function(){
    Route::post('/account/verify', [AuthController::class, 'confirm_registration']);
});
*/

// Protected routes (token + confirmed)
Route::group(['middleware' => ['auth:sanctum','is_verify_email','user-deleted']], function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/accounts', [AccountController::class, 'index'])->middleware('user-access:admin|manager')->name('confirm');
    Route::get('/accounts/{user:id}', [AccountController::class, 'show']);
    Route::put('/accounts/{user:id}',[AccountController::class, 'update']);
    Route::post('/accounts/{user:id}/image', [AccountController::class, 'update_profile_image']);
    Route::delete('/accounts/{user:id}', [AccountController::class, 'delete'])->middleware('user-access:admin|manager');
    Route::get('/accounts/{user:id}/submissions', [SubmissionController::class, 'index']);
    Route::post('/accounts/{user:id}/submissions/', [SubmissionController::class, 'create'])->middleware('user-access:admin|manager');
    Route::get('/accounts/{user:id}/submissions/{submission}', [SubmissionController::class, 'show']);
    Route::put('/accounts/{user:id}/submissions/{submission}', [SubmissionController::class, 'update'])->middleware('user-access:admin|manager');
    Route::delete('/accounts/{user:id}/submissions/{submission}', [SubmissionController::class, 'delete'])->middleware('user-access:admin|manager');
    Route::post('/accounts/{user:id}/submissions/{submission}/document', [SubmissionController::class, 'update_document']);
});






