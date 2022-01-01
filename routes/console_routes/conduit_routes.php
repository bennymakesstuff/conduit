<?php

use App\Mail\TestMail;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

/*
|--------------------------------------------------------------------------
| Conduit Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands specific to Conduit.
|
*/


/**
 * Checks the Conduit Database Connection
 */
Artisan::command('conduit:check-connection', function () {
  $this->comment('Checking the Conduit Database Connection');
  try {
    $database = DB::connection('pgsql')->getPDO();
    if ($database) {
      $this->comment(sprintf('Connected to database %s', DB::connection('pgsql')->getDatabaseName()));
    }
  } catch (Exception $exeption) {
    $this->comment('No database configured');
    die;
  }
})->purpose('Checks the status of the Conduit Database Connection');



/**
 * Test Email System
 */
Artisan::command('conduit:check-email', function () {
  $this->comment('Checking the Conduit Email System');

  Mail::to('me@benbroad.com')->send(new TestMail('Benjamin Number 2'));

})->purpose('Sends an email to test the Conduit Email System');


