<?php

use App\Http\Controllers\PermissionsController;
use App\Http\Controllers\RolesController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| V1 API Roles Routes
|--------------------------------------------------------------------------
*/
/**
 * Returns a list of all roles
 */
Route::middleware('auth:sanctum')
  ->get('/roles', [RolesController::class, 'getRoles'])
  ->name('get-roles');

/**
 * Creates a new role
 */
Route::middleware('auth:sanctum')
  ->post('/roles/create', [RolesController::class, 'createRole'])
  ->name('create-role');
