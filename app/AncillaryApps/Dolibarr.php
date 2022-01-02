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

    $console_route->comment('Users to Sync: ' . count($users));

    if (!$users) {
    // TODO: Add error logging here for no users found
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
            'dolibarr_last_sync' => new DateTime('now')
          ],
            [
              'dolibarr_id'
            ],
            [
              'firstname',
              'lastname',
              'password',
              'email',
              'dolibarr_json',
              'dolibarr_last_sync'
            ]);
      }

      DB::commit();
      $console_route->comment('Users Synced Successfully');
      Log::channel('discord')->info(
        sprintf('Dolibarr Users Sync: Completed Successfully %s Synced %s users',PHP_EOL.PHP_EOL, count($users))
      );

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




  /**
   * Syncs projects from Dolibarr into Conduit
   * @returns void
   */
  public static function syncProjects($console_route): void
  {
    $console_route->comment('Starting Sync of Projects from Dolibarr to Conduit');

    $uuid_factory = Uuid::getFactory();
    $dolibarr_con = DB::connection(env('DOLIBARR_DB_CONNECTION'));

    $projects = $dolibarr_con
      ->table(self::DOLI_PROJECT_TABLE)
      ->select()
      ->get();

    $console_route->comment('Projects to Sync: ' . count($projects));

    if (!$projects) {
    // TODO: Add error logging here for no projects found
    }

    DB::beginTransaction();

    try {
      // Iterate through the users and save records to database
      foreach ($projects as $dolibarr_project) {
        $console_route->comment(
          sprintf('Syncing %s %s', $dolibarr_project->ref, $dolibarr_project->title)
        );

        $result = DB::table('projects')
          ->upsert([
            'dolibarr_id' => $dolibarr_project->rowid,
            'title' => $dolibarr_project->title,
            'description' => $dolibarr_project->description,
            'dolibarr_ref' => $dolibarr_project->ref,
            'dolibarr_entity' => $dolibarr_project->entity,
            'dolibarr_last_sync' => new DateTime('now'),
            'dolibarr_json' => json_encode($dolibarr_project),
            'uuid' => $uuid_factory->fromDateTime(new DateTime('now')),
          ],
            [
              'dolibarr_id'
            ],
            [
              'title',
              'description',
              'dolibarr_ref',
              'dolibarr_entity',
              'dolibarr_json',
              'dolibarr_last_sync'
            ]);
      }


      DB::commit();
      $console_route->comment('Projects Synced Successfully');
      Log::channel('discord')->info(
        sprintf('Dolibarr Projects Sync: Completed Successfully %s Synced %s projects',PHP_EOL.PHP_EOL, count($projects))
      );

    } catch (PDOException $exception) {
      $console_route->comment('Failed to Sync Projects');
      DB::rollBack();

      $error = [
        'status' => 'DOLIBARR PROJECTS SYNC FAILED',
        'message' => 'Failed during Dolibarr Project sync task',
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
