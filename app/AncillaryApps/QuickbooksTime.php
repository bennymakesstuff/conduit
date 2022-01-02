<?php

namespace App\AncillaryApps;

use App\Models\User;
use App\Models\Workorders;
use DateTime;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use PDOException;
use PHPUnit\Framework\Constraint\Operator;
use Ramsey\Uuid\Uuid;
use Illuminate\Support\Facades\Http;

/**
 * Class for interfacing with Quickbooks Time
 */
class QuickbooksTime
{

  public const QBT_BEARER_TOKEN = "S.15__476bbf822541d7b57a00fefd67434fcd24378079";
  public const QBT_SERVER_URI = "https://rest.tsheets.com/api/v1/";
  public const QBT_USERS = "users";
  public const QBT_JOBCODES = "jobcodes";
  public const REQUEST_TYPE_GET = 'GET';
  public const REQUEST_TYPE_POST = 'POST';
  public const REQUEST_TYPE_PUT = 'PUT';

  /**
   * Returns the resultant data from a web request
   * @param string $resource
   * @param array $params
   * @param string $type
   * @return null
   */
  private function makeRequest(string $resource, array $params = [], string $type = self::REQUEST_TYPE_GET)
  {
    // TODO: Add connection and timeout exception handling

    $token = self::QBT_BEARER_TOKEN;
    $url = self::QBT_SERVER_URI . $resource;
    $data = [
      "data" => $params
    ];

    if ($type === self::REQUEST_TYPE_GET) {
      return Http::withToken($token)->get($url, $data);
    }
    elseif ($type === self::REQUEST_TYPE_POST) {
      return Http::withToken($token)->post($url, $data);
    }
    elseif ($type === self::REQUEST_TYPE_PUT) {
      return Http::withToken($token)->put($url, $data);
    }

    return null;
  }


  /**
   * Retrieves all users from Quickbooks
   * @param $route_instance
   * @return string
   */
  public function getQBTUsers($route_instance = null)
  {
    $response = self::makeRequest('users');

    // Return null if there is no response
    if ($response === null) return $response;

    if ($response->successful()) {
      $route_instance->comment('Success');
      $results = $response->object()->results;
      $users = $results->users;

      foreach ($users as $user) {
        $route_instance->comment(' --- Details for User: ' . $user->first_name . ' ' . $user->last_name . ' --- ');
        var_dump($user);
      }

    }
    elseif ($response->failed()) {
      $route_instance->comment('Failure');
      var_dump($response->json());
    }

    $route_instance->comment('');
    return '';
  }


  public function syncQBTUsers($route_instance)
  {


    $users = User::all()
      ->whereNull('qbt_last_sync');

    if (is_null($users)) return null;

    $route_instance->comment('Syncing ' . count($users) . 'Users to Quickbooks Time');

    foreach ($users as $user) {

      $route_instance->comment('Syncing User: ' . $user->dolibarr_username);

      $qbt_user = (object)[
        "username" => $user->dolibarr_username,
        "first_name" => $user->firstname,
        "last_name" => $user->lastname,
        "email" => "changeme@example.com", //TODO: Change this to actually setup emails from the users
        "employee_number" => $user->dolibarr_id,
        "mobile_number" => $user->mobile,
      ];

      $params = [$qbt_user];

      $response = self::makeRequest('users', $params, self::REQUEST_TYPE_POST);

      // Return null if there is no response
      if ($response === null) return $response;

      if ($response->successful()) {
        $route_instance->comment('Success');
        $results = $response->object()->results;

      } elseif ($response->failed()) {
        $route_instance->comment('Failure');
      }

      $user->qbt_last_sync = new DateTime('now');
      $user->save();

    }


    $route_instance->comment('Finished Quickbooks Time User Sync');
    return '';
  }
}
