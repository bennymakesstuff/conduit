<?php

namespace Database\Seeders;

use DateTime;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;

class UserRolesSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      $uuid_factory = Uuid::getFactory();

      DB::table('user_roles')->insert([
        'uuid' => $uuid_factory->fromDateTime(new DateTime('now')),
        'user' => env('SEED_ADMIN_UUID'),
        'role' => env('SEED_SUPER_ROLE'),
      ]);
    }
}
