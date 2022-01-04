<?php

use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| V1 API User Routes
|--------------------------------------------------------------------------
*/
/**
 * Registers a user
 */
Route::post('/register', [AuthController::class, 'register'])
  ->name('register');

/**
 * Logs the user in with the provided details
 */
Route::post('/login', [AuthController::class, 'login'])
  ->name('login');

/**
 * Reset password
 */
Route::post('/recover-account', [AuthController::class, 'recoverAccount'])
  ->name('recover-account');

/**
 * Reset password
 */
Route::post('/reset-password', [AuthController::class, 'resetPassword'])
  ->name('reset-password');

/**
 * Returns a message for unsuccessful authentication
 */
Route::get('/login-unsuccessful', function (Request $request)
{
  $login_error = [
    'status' => 'INVALID CREDENTIALS',
    'message' => 'Your credentials are either expired or invalid'
  ];

  return $login_error;
})->name('login-unsuccessful');


Route::middleware('auth:sanctum')
  ->get('/user/details', function (Request $request)
  {
    return $request->user();
  });

/**
 * Returns the user object of the currently authenticated user based on bearer token
 */
Route::middleware('auth:sanctum')
  ->get('/user', function (Request $request)
{
    return $request->user();
});

/**
 * Creates a new user in the database
 */
Route::middleware('auth:sanctum')
  ->post('/user/create', [UserController::class, 'newUser'])
  ->name('user-create');


