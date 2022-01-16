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

      // User arrays
      $users[] = [
        'uuid_seed' => false,
        'uuid' => env('SEED_SUPER_ROLE'),
        'title' => 'Super Admin',
        'description' => 'Has access to everything',
        'active' => true,
        'permissions' => null,
      ];

      // Iterate through the users array and insert
      foreach ($users as $user) {
        if (isset($user['uuid_seed']) && $user['uuid_seed'] === false) {
          unset($user['uuid_seed']);
        }
        else {
          unset($user['uuid_seed']);
          $uuid = $uuid_factory->fromDateTime(new DateTime('now'));
          $user['uuid'] = $uuid;
        }
        DB::table('roles')->insert($user);
      }
    }
}
