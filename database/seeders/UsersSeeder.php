<?php

namespace Database\Seeders;

use DateTime;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Ramsey\Uuid\Uuid;

class UsersSeeder extends Seeder
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
        DB::table('users')->insert([
          'email' => env('SEED_ADMIN_EMAIL'),
          'password' => Hash::make(env('SEED_ADMIN_PASSWORD')),
          'firstname' => env('SEED_ADMIN_FIRSTNAME'),
          'lastname' => env('SEED_ADMIN_LASTNAME'),
          'uuid' => env('SEED_ADMIN_UUID'),
          'roles' => "[]",
        ]);
    }
}
