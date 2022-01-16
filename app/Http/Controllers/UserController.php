<?php

namespace App\Http\Controllers;

use App\Models\Roles;
use App\Models\User;
use App\Models\UserRoles;
use DateTime;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
   * Returns a list of all users
   *
   * @return string
   */
  public function getSingleUser($uuid): string
  {
    $user = User::where('uuid', '=', $uuid)->first();

    if ($user === null) {
      return response()->json([
        'status' => false,
        'title' => 'USERS',
        'message' => 'User not found'
      ]);
    }

    // Save the users roles to the array
    $returned_user = $user->toArray();
    $returned_user['roles'] = $user->getRoles();

    return json_encode([
      'status' => true,
      'title' => 'USERS',
      'user' => $returned_user
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

      // Create the new user
      $user = (new User)->create([
        'email' => $request['email'],
        'password' => Hash::make($request['password']),
        'firstname' => $request['firstname'],
        'lastname' => $request['lastname'],
        'uuid' => $uuid,
      ]);

      // Add the id of the Authenticated user creating the new user if not system generated
      if (Auth::id()) {
        $user
          ->setAttribute('created_by', Auth::id())
          ->save();
      }

      // Add any selected roles to the user
      if (!is_null($user_data['roles'] && count($user_data['roles']) > 0)) {
        foreach($user_data['roles'] as $role) {
          try {
            $user_role_record = new UserRoles([
              'created_by' => Auth::id(),
              'user' => $user->uuid,
              'role' => $role['uuid'],
              'expires_at' => null,
              'uuid' => $uuid_factory->fromDateTime(new DateTime('now'))
            ]);
            $user_role_record->save();
            Log::channel('error_stack')->info($role['title']);
          } catch (Exception $role_exception) {
            $error = [
              'status' => 'USER CREATE FAILED',
              'message' => 'Failed to create new user',
              'error_id' => 'USER|' . $uuid_factory->fromDateTime(new DateTime('now')),
              'exception_message' => $role_exception->getMessage(),
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
          }
        }
      }


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






  /**
   * Updates a user from the provided details
   * @param Request $request
   * @return JsonResponse
   */
  public function updateUser(Request $request): JsonResponse
  {
    $uuid_factory = Uuid::getFactory();

    // Validate the contents of the request
    $validator = Validator::make($request->all(), [
      'uuid' => 'required|string|max:36',
      'user' => 'required'
    ]);

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

    // Update the user
    $modified_user = $request['user'];

    // Remove the roles data so it does not overwrite actual role information
    if (isset($modified_user['roles'])) {
      unset($modified_user['roles']);
    }

    try {

      // TODO: Add these updated by fields where necessary
      // $user_data['updated_by'] = Auth::id();

      $user = User::query()
        ->where('uuid', '=', $request['uuid'])
        ->first();

      if ($user) {

        $user_array = $user->toArray();

        $user_updates = [];

        // Only updates values where columns exist
        foreach ($modified_user as $key=>$value) {
          if ($user->getConnection()
            ->getSchemaBuilder()
            ->hasColumn('users', $key)) {
            $user_updates[$key] = $value;
          }
        }

        // Remove the UUID as we never update that
        unset($user_updates['uuid']);

        $user = User::query()
          ->where('uuid', '=', $request['uuid'])
          ->update($user_updates);

        return response()->json([
          'status' => true,
          'title' => 'USER UPDATED',
          'user' => $user
        ]);
      }
      else {
        $error = [
          'status' => 'USER UPDATE FAILED',
          'message' => 'Failed to update user',
          'error_id' => 'USER|' . $uuid_factory->fromDateTime(new DateTime('now')),
          'exception_message' => 'No usr matching that ID',
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
      }

    } catch (PDOException $exception) {

      $error = [
        'status' => 'USER UPDATE FAILED',
        'message' => 'Failed to update user',
        'error_id' => 'USER|' . $uuid_factory->fromDateTime(new DateTime('now')),
        'exception_message' => $exception->getMessage(),
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



  /**
   * Adds a role to a user
   * @param Request $request
   * @return JsonResponse
   */
  public function addRole(Request $request): JsonResponse
  {
    $uuid_factory = Uuid::getFactory();

    // Validate the contents of the request
    $validator = Validator::make($request->all(), [
      'user' => 'required|string|max:36',
      'role' => 'required|string|max:36'
    ]);

    // If the validation fails send back the reason
    if ($validator->fails()) {

      $error = [
        'status' => false,
        'title' => 'VALIDATION FAILED',
        'error_id' => 'USER-ROLE|' . $uuid_factory->fromDateTime(new DateTime('now')),
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

    try {

      // TODO: Add these updated by fields where necessary
      // $user_data['updated_by'] = Auth::id();

      $user = User::query()
        ->where('uuid', '=', $request['user'])
        ->first();

      $role = Roles::query()
        ->where('uuid', '=', $request['role'])
        ->first();

      if ($user && $role) {

        $user_role = new UserRoles([
          'user' => $request['user'],
          'role' => $request['role'],
          'uuid' => $uuid_factory->fromDateTime(new DateTime('now')),
        ]);

        $user_role->save();

        return response()->json([
          'status' => true,
          'title' => 'USER UPDATED',
          'user' => $user_role
        ]);
      }
      else {
        $error = [
          'status' => 'USER UPDATE FAILED',
          'message' => 'Failed to update user',
          'error_id' => 'USER|' . $uuid_factory->fromDateTime(new DateTime('now')),
          'exception_message' => 'No usr matching that ID',
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
      }

    } catch (PDOException $exception) {

      $error = [
        'status' => 'USER UPDATE FAILED',
        'message' => 'Failed to update user',
        'error_id' => 'USER|' . $uuid_factory->fromDateTime(new DateTime('now')),
        'exception_message' => $exception->getMessage(),
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
