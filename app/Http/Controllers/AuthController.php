<?php

namespace App\Http\Controllers;

use App\Mail\PasswordResetEmail;
use App\Mail\RecoveryEmail;
use App\Mail\TestMail;
use App\Models\User;
use DateTime;
use Exception;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Ramsey\Uuid\Uuid;
use Illuminate\Support\Facades\Auth;
use Throwable;


class AuthController extends Controller
{

  public function register(Request $request)
  {
    $validatedData = $request->validate([
      'email' => 'required|string|email|max:255',
      'password' => 'required|string|min:8',
      'given_name' => 'required|string',
      'surname' => 'required|string'
    ]);

    // Check if the user already exists
    $check_users = User::query()
      ->where('email', '=', $request->get('email'));

    if ($check_users->count() > 0) {
      return response()->json([
        'status' => false,
        'message' => 'User Already Exists',
      ]);
    }


    try {
      $uuid_factory = Uuid::getFactory();
      $uuid = $uuid_factory->fromDateTime(new DateTime('now'));

      $user = new User([
        'email' => $request->get('email'),
        'password' => Hash::make($request->get('password')),
        'firstname' => $request->get('given_name'),
        'lastname' => $request->get('surname'),
        'uuid' => $uuid,
        'roles' => "[]",
      ]);

      $user->saveOrFail();

      Log::info('New User Created: ' . $user['email']);
      //$token = $user->createToken('auth_token')->plainTextToken;

      return response()->json([
        'status' => true,
        'message' => 'User Created',
        'user' => $user
      ]);
    } catch (Exception $e) {
      return response()->json([
        'status' => false,
        'message' => 'Could not register new user.'
      ], 200);
    } catch (Throwable $e) {
      return response()->json([
        'status' => false,
        'message' => 'Could not register new user.'
      ], 200);
    }
  }


  /**
   * Attempts to log the user in
   * @param Request $request
   * @return \Illuminate\Http\JsonResponse
   */
  public function login(Request $request)
  {

    // Attempt to login with provided details
    if (!Auth::attempt($request->only('email', 'password'))) {
      return response()->json([
        'status' => false,
        'message' => 'Invalid login details'
      ], 200);
    }

    // Try to fetch the user and create a bearer token to send back
    try {
      /** @var User $user */
      $user = (new User)->where('email', $request->get('email'))->first();

      $token = $user->createToken('auth_token')->plainTextToken;

      return response()->json([
        'status' => true,
        'access_token' => $token,
        'token_type' => 'Bearer',
      ]);
    } catch (Exception $e) {
      return response()->json([
        'status' => false,
        'message' => 'Could not login user.'
      ], 200);
    }
  }

  /**
   * Logs the user in with a Token and returns the user details
   * @param Request $request
   * @return \Illuminate\Http\JsonResponse
   */
  public function tokenLogin(Request $request)
  {
    // TODO Create a security record of login
    return response()->json([
      'status' => true,
      'user' => $request->user()->toJson(),
      'message' => "Token Valid ????",
    ]);
  }


  public function recoverAccount(Request $request)
  {
    $validatedData = $request->validate([
      'email' => 'required|string',
    ]);

    Log::info(sprintf('Password Request from %s', $request->get('email')));

    // Check if the user already exists
    $check_users = User::query()
      ->where('email', '=', $request->get('email'));

    // Return a complete message even if no account exists
    if ($check_users->count() < 1) {
      return response()->json([
        'status' => true,
        'message' => 'Password reset request complete',
      ]);
    }

    // Send an email to the user and log a security record / discord message
    // TODO: Create a security log channel
    /** @var User $user */
    $user = $check_users->first();

    $password_reset_token = Password::createToken($user);

    try {
      Mail::to($user['email'])->send(new RecoveryEmail($user, $password_reset_token));
      return response()->json([
        'status' => true,
        'message' => 'Password reset request sent.',
      ]);
    }
    catch (Exception $exception) {
      return response()->json([
        'status' => false,
        'message' => 'Password reset request could not be sent.',
      ]);
    }
  }


  public function resetPassword(Request $request)
  {
    $validatedData = $request->validate([
      'email' => 'required|string',
      'password' => 'required|string',
      'token' => 'required|string'
    ]);

    Log::info(sprintf('Password reset'));

    try {

      $status = Password::reset(
        $request->only('email', 'password', 'token'),
        function ($user, $password) {
          $user->forceFill([
            'password' => Hash::make($password)
          ])->setRememberToken(Str::random(60));

          $user->save();

          event(new PasswordReset($user));
        }
      );


      $user = (new User)->where('email', $request->get('email'))->firstOrFail();

      Log::info($user['email']);

//      if ($status === Password::PASSWORD_RESET) {
//        Mail::to($request->get('email'))->send(new PasswordResetEmail($user));
//      }

      return response()->json([
        'status' => true,
        'message' => 'Password reset request successful.',
      ]);
    }
    catch (Exception $exception) {
      return response()->json([
        'status' => false,
        'message' => 'Password reset could not be completed.',
      ]);
    }
  }
}
