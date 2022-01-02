<?php

use App\AncillaryApps\QuickbooksTime;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

/*
|--------------------------------------------------------------------------
| QuickBooks Time Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands specific to Quickbooks Time.
|
*/

/**
 * Checks the Quickbooks Time Database Connection
 */
Artisan::command('quickbooks:check-connection', function () {
  $this->comment('Checking the Dolibarr Database Connection');

  try {
    $database = DB::connection(env('DOLIBARR_DB_CONNECTION'))->getPDO();

    if ($database) {
      $this->comment(sprintf('Connected to database %s', DB::connection(env('DOLIBARR_DB_CONNECTION'))->getDatabaseName()));
    }
  } catch (PDOException $exeption) {
    $this->comment('No database configured');
    die;
  }
})->purpose('Checks the status of the Conduit Database Connection');


/**
 * Retrieve Users from QBT
 */
Artisan::command('quickbooks:get-users', function () {
  $this->comment('Collecting Users from Quickbooks Time');

  $qbt = new QuickbooksTime();
  $users = $qbt->getQBTUsers($this);


})->purpose('Checks the status of the Conduit Database Connection');


/**
 * Sync all users with QBT
 */
Artisan::command('quickbooks:sync-users', function () {
  $this->comment('Syncing all users in Quickbooks Time');

  $qbt = new QuickbooksTime();
  $users = $qbt->syncQBTUsers($this);

})->purpose('Syncs all users with Quickbooks Time');

