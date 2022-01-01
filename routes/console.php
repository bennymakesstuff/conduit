<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Syncs users from Dolibarr into Conduit
Artisan::command('dolibarr:sync-users', function () {
    $this->comment('Running Dolibarr User Sync Task');
})->purpose('Sync Users from Dolibarr to Conduit');

// Syncs Projects from Dolibarr into Conduit
Artisan::command('dolibarr:sync-projects', function () {
    $this->comment('Running Dolibarr Projects Sync Task');
})->purpose('Sync Projects from Dolibarr to Conduit');

// Syncs Workorders from Dolibarr into Conduit
Artisan::command('dolibarr:sync-workorders', function () {
    $this->comment('Running Dolibarr Workorder Sync Task');
})->purpose('Sync Workorders from Dolibarr to Conduit');

// Syncs User to Project from Dolibarr into Conduit
Artisan::command('dolibarr:sync-user-project-mapping', function () {
    $this->comment('Running Dolibarr User-Project Mapping Sync Task');
})->purpose('Sync User to Project Mapping from Dolibarr to Conduit');
