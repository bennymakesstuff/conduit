<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

/*
|--------------------------------------------------------------------------
| Dolibarr Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands specific to Dolbarr.
|
*/

/**
 * Checks the Conduit Database Connection
 */
Artisan::command('dolibarr:check-connection', function () {
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
 * Returns a set of stats for the Dolibarr Instance
 */
Artisan::command('dolibarr:stats', function () {
  $this->comment('Retrieving Stats for Dolibarr');

  try {
    $database = DB::connection('dolibarr_prod')->getPDO();

    if ($database) {
      $this->comment(sprintf('Connected to database %s', DB::connection(env('DOLIBARR_DB_CONNECTION'))->getDatabaseName()));

      $user_count = DB::connection('dolibarr_prod')->table('doli_user')->count();
      if ($user_count) $this->comment(sprintf('Users: %s', $user_count));

      $project_count = DB::connection('dolibarr_prod')->table('doli_projet')->count();
      if ($project_count) $this->comment(sprintf('Projects: %s', $project_count));

      $workorder_count = DB::connection('dolibarr_prod')->table('doli_projet_task')->count();
      if ($workorder_count) $this->comment(sprintf('Workorders: %s', $workorder_count));
    }
  } catch (PDOException $exeption) {
    $this->comment('No database configured');
    die;
  }
})->purpose('Checks the status of the Conduit Database Connection');



/**
 * Syncs users from Dolibarr into Conduit
 */
Artisan::command('dolibarr:sync-users', function () {
  $this->comment('Running Dolibarr User Sync Task');
})->purpose('Sync Users from Dolibarr to Conduit');



/**
 * Syncs Projects from Dolibarr into Conduit
 */
Artisan::command('dolibarr:sync-projects', function () {
  $this->comment('Running Dolibarr Projects Sync Task');
})->purpose('Sync Projects from Dolibarr to Conduit');



/**
 * Syncs Workorders from Dolibarr into Conduit
 */
Artisan::command('dolibarr:sync-workorders', function () {
  $this->comment('Running Dolibarr Workorder Sync Task');
})->purpose('Sync Workorders from Dolibarr to Conduit');



/**
 * Syncs User to Project from Dolibarr into Conduit
 */
Artisan::command('dolibarr:sync-user-project-mapping', function () {
  $this->comment('Running Dolibarr User-Project Mapping Sync Task');
})->purpose('Sync User to Project Mapping from Dolibarr to Conduit');
