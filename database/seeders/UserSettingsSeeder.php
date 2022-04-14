<?php

namespace Database\Seeders;

use DateTime;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;

class UserSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      $uuid_factory = Uuid::getFactory();

      // Create the base tenants settings
      DB::table('user_settings')->insert([
        'uuid' => $uuid_factory->fromDateTime(new DateTime('now')),
        'user' => env('SEED_ADMIN_UUID'),
        'preferences' => env('SEED_ADMIN_SETTINGS'),
      ]);
    }
}
