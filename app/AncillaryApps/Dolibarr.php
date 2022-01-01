<?php

namespace App\AncillaryApps;

use App\Models\User;
use DateTime;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use PDOException;
use PHPUnit\Framework\Constraint\Operator;
use Ramsey\Uuid\Uuid;

/**
 * Class for interfacing with Dolibarr
 */
class Dolibarr
{

  public const DOLI_USER_TABLE = 'doli_user';
  public const DOLI_PROJECT_TABLE = 'doli_projet';
  public const DOLI_WORKORDER_TABLE = 'doli_projet_task';

  /**
   * Syncs users from Dolibarr into Conduit
   * @returns void
   */
  public static function syncUsers($console_route): void
  {
    $uuid_factory = Uuid::getFactory();
    $dolibarr_con = DB::connection(env('DOLIBARR_DB_CONNECTION'));

    $users = $dolibarr_con
      ->table(self::DOLI_USER_TABLE)
      ->select()
      ->get();

    $console_route->comment(count($users));

    if (!$users) {

    }

    DB::beginTransaction();

    try {
      // Iterate through the users and save records to database
      foreach ($users as $dolibarr_user) {
        $console_route->comment(
          sprintf('Syncing %s %s', $dolibarr_user->firstname, $dolibarr_user->lastname)
        );

        if ($dolibarr_user->email === '') {
          $dolibarr_user->email = null;
        }

        $result = DB::table('users')
          ->upsert([
            'dolibarr_id' => $dolibarr_user->rowid,
            'firstname' => $dolibarr_user->firstname,
            'lastname' => $dolibarr_user->lastname,
            'email' => $dolibarr_user->email,
            'password' => $dolibarr_user->pass,
            //'password' => $dolibarr_user->pass_crypted
            'uuid' => $uuid_factory->fromDateTime(new DateTime('now')),
            'dolibarr_json' => json_encode($dolibarr_user),
          ],
            [
              'dolibarr_id'
            ],
            [
              'firstname',
              'lastname',
              'password',
              'email',
              'dolibarr_json'
            ]);
      }

      DB::commit();
      $console_route->comment('Users Synced Successfully');
      Log::channel('discord')->info(sprintf('Dolibarr User Sync: Completed Successfully'));

    } catch (PDOException $exception) {
      $console_route->comment('Failed to Sync Users');
      DB::rollBack();

      $error = [
        'status' => 'DOLIBARR USER SYNC FAILED',
        'message' => 'Failed during Dolibarr User sync task',
        'error_id' => 'DOLIBARR-SYNC|' . $uuid_factory->fromDateTime(new DateTime('now')),
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
    }


  }


}
