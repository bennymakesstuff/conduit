<?php

namespace App\Http\Controllers;

use App\Models\User;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Ramsey\Uuid\Uuid;
use Illuminate\Support\Facades\Auth;


class AuthController extends Controller
{

  public function register(Request $request)
  {
    $validatedData = $request->validate([
      'email' => 'required|string|email|max:255|unique:users',
      'password' => 'required|string|min:8',
    ]);

    $uuid_factory = Uuid::getFactory();
    $uuid = $uuid_factory->fromDateTime(new DateTime('now'));

    $user = new User([
      'email' => env('SEED_ADMIN_EMAIL'),
      'password' => Hash::make(env('SEED_ADMIN_PASSWORD')),
      'firstname' => env('SEED_ADMIN_FIRSTNAME'),
      'lastname' => env('SEED_ADMIN_LASTNAME'),
      'uuid' => $uuid,
      'roles' => "[]",
      'dolibarr_last_login' => null,
      'dolibarr_last_sync' => null
    ]);

    $token = $user->createToken('auth_token')->plainTextToken;

    return response()->json([
      'access_token' => $token,
      'token_type' => 'Bearer',
    ]);
  }



  public function login(Request $request)
  {
    if (!Auth::attempt($request->only('email', 'password'))) {
      return response()->json([
        'message' => 'Invalid login details'
      ], 401);
    }

    $user = (new User)->where('email', $request['email'])->firstOrFail();

    $token = $user->createToken('auth_token')->plainTextToken;

    return response()->json([
      'access_token' => $token,
      'token_type' => 'Bearer',
    ]);
  }

}
