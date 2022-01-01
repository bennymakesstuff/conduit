<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| V1 API User Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/user', function (Request $request) {
  return $request->user();
});

Route::post('/login', function (Request $request) {
  return 'Login Route';
});

Route::post('/tokens/create', function (Request $request) {
  $token = $request->user()->createToken($request->token_name);

  return ['token' => $token->plainTextToken];
});
