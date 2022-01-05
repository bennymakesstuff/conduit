<?php

namespace Database\Seeders;

use DateTime;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Ramsey\Uuid\Uuid;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      $uuid_factory = Uuid::getFactory();
      $uuid = $uuid_factory->fromDateTime(new DateTime('now'));

      // Create the Main Admin User
      DB::table('roles')->insert([
        'uuid' => $uuid,
        'title' => 'Super Admin',
        'description' => 'Has access to everything',
        'active' => true,
        'permissions' => null,
      ]);
    }
}
