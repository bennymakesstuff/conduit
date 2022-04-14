<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TenantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      // Create the base tenant
      DB::table('tenants')->insert([
        'uuid' => env('SEED_BASE_TENANT_UUID'),
        'name' => env('SEED_BASE_TENANT_NAME'),
        'description' => env('SEED_BASE_TENANT_DESCRIPTION'),
        'active' => true
      ]);
    }
}
