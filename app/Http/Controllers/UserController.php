<?php

namespace App\Http\Controllers;

use App\Models\User;
use DateTime;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use PDOException;
use Ramsey\Uuid\Uuid;

class UserController extends Controller
{
  /**
   * Returns a count of all roles
   *
   * @return string
   */
  public function getUserCount(): string
  {
    $users = User::all('id');

    if ($users === null) {
      return response()->json([
        'status' => false,
        'title' => 'USERS',
        'message' => 'No Users Available'
      ]);
    }

    return json_encode([
      'status' => true,
      'title' => 'USERS',
      'count' => $users->count()
    ]);
  }


  /**
   * Returns a list of all users
   *
   * @return string
   */
  public function getUsers(): string
  {
    $users = User::all();

    if ($users === null) {
      return response()->json([
        'status' => false,
        'title' => 'USERS',
        'message' => 'No Users Available'
      ]);
    }

    return json_encode([
      'status' => true,
      'title' => 'USERS',
      'users' => $users
    ]);
  }

  /**
   * Creates a new user from the provided details
   * @param Request $request
   * @return JsonResponse
   */
  public function newUser(Request $request): JsonResponse
  {
    $uuid_factory = Uuid::getFactory();

    // Validate the contents of the request
    $validator = Validator::make($request->all(), [
      'email' => 'required|string|email|max:255',
      'password' => 'required|string|min:8',
      'firstname' => 'required|string|max:255',
      'lastname' => 'required|string|max:255',
    ]);

    Log::info(json_encode($request->all()));

    // If the validation fails send back the reason
    if ($validator->fails()) {

      $error = [
        'status' => false,
        'title' => 'VALIDATION FAILED',
        'error_id' => 'USER|' . $uuid_factory->fromDateTime(new DateTime('now')),
        'message' => 'The supplied data was not correct',
      ];

      Log::channel('discord')->info(sprintf('%s %s Message: %s %s ID: %s',
        $error['status'],
        PHP_EOL.PHP_EOL,
        $error['message'],
        PHP_EOL.PHP_EOL,
        $error['error_id'],
      ));

      return response()->json($error);
    }

    // Build the new user
    $uuid = $uuid_factory->fromDateTime(new DateTime('now'));

    $user = null;

    $user_data = $request->all();

    try {

      $user = (new User)->create([
        'email' => $request['email'],
        'password' => Hash::make($request['password']),
        'firstname' => $request['firstname'],
        'lastname' => $request['lastname'],
        'uuid' => $uuid,
      ]);

      return response()->json([
        'status' => true,
        'title' => 'USER CREATED',
        'user' => $user
      ]);

    } catch (PDOException $exception) {

      $error = [
        'status' => 'USER CREATE FAILED',
        'message' => 'Failed to create new user',
        'error_id' => 'USER|' . $uuid_factory->fromDateTime(new DateTime('now')),
        'exception_message' => $exception->getMessage(),
        'uuid' => $user,
        'data' => $user_data
      ];

      Log::channel('error_stack')->error(sprintf('%s %s Message: %s %s ID: %s %s Exception: %s',
        $error['status'],
        PHP_EOL.PHP_EOL,
        $error['message'],
        PHP_EOL.PHP_EOL,
        $error['error_id'],
        PHP_EOL.PHP_EOL,
        $error['exception_message']
      ));

      return response()->json([
        'status' => false,
        'title' => $error['status'],
        'error_id' => $error['error_id'],
        'message' => $error['message']
      ]);
    }


  }
}
