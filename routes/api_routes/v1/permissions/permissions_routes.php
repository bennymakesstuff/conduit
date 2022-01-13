<?php

use App\Http\Controllers\PermissionGroupsController;
use App\Http\Controllers\PermissionsController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| V1 API Permissions Routes
|--------------------------------------------------------------------------
*/
/**
 * Returns a list of all permissions
 */
Route::middleware('auth:sanctum')
  ->get('/permissions', [PermissionsController::class, 'getPermissions'])
  ->name('get-permissions');

/**
 * Returns a list of permission groups
 */
Route::middleware('auth:sanctum')
  ->get('/permissions/groups', [PermissionGroupsController::class, 'getPermissionGroups'])
  ->name('permission-groups-get');
