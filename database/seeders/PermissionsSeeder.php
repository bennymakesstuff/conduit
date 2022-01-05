<?php

namespace Database\Seeders;

use DateTime;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;

class PermissionsSeeder extends Seeder
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

    // Create the first permission
    DB::table('permissions')->insert([
      'uuid' => $uuid,
      'identifier' => 'admin:super',
      'title' => 'Super Administrator',
      'description' => 'Can do anything',
      'permission_group' => null,
      'active' => true,
    ]);
  }
}
