<?php

namespace Database\Seeders;

use App\Models\Roles;
use DateTime;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
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

    // Setup Array to iterate over permissions
    // Include permission sets here
    $permission_sets = [
      include 'SeederData/Permissions.php',
      include 'SeederData/User.php',
      include 'SeederData/Roles.php',
      include 'SeederData/Personal.php'
    ];

    $full_permission_set = [];

    // Iterate through permission sets
    foreach ($permission_sets as $set) {

      $this->command->getOutput()->writeln(
       '<info>Seeding:</info> Importing permissions set for ' . $set[0]['title']
      );

      // Create the permissions Group
      $set[0]['uuid'] = $uuid_factory->fromDateTime(new DateTime('now'));
      $group_id = DB::table('permission_groups')->insertGetId($set[0]);
      // Iterate through the users array and insert
      foreach ($set[1] as $permission) {
        $uuid = $uuid_factory->fromDateTime(new DateTime('now'));
        $permission['uuid'] = $uuid;
        $permission['permission_group'] = $group_id;
        DB::table('permissions')->insert($permission);

        // Add the permission into the full permission set to save to the super user
        $full_permission_set []= $permission['identifier'];

        $super_role = DB::table('roles')
          ->where('uuid', '=', env('SEED_SUPER_ROLE'))
          ->update([
            'permissions' => $full_permission_set
            ]);

      }

    }




  }
}
