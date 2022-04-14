<?php

namespace Database\Seeders;

use DateTime;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;

class TenantSettingsSeeder extends Seeder
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
      DB::table('tenant_settings')->insert([
        'uuid' => $uuid_factory->fromDateTime(new DateTime('now')),
        'tenant' => env('SEED_BASE_TENANT_UUID'),
        'settings' => env('SEED_BASE_TENANT_JSON'),
      ]);
    }
}
