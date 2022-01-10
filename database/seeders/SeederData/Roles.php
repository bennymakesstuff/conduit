<?php
// Permissions relevant to Roles
return array(
  //Permission Group
  ['identifier' => 'roles', 'title' => 'Roles', 'description' => null, 'active' => true],

  // Permissions
  [
  ['permission_group' => null, 'identifier' => 'roles:view', 'title' => 'View Roles', 'description' => 'User can view other roles',  'active' => true],
  ['permission_group' => null, 'identifier' => 'roles:create', 'title' => 'Create Roles', 'description' => 'User can create new roles', 'active' => true],
  ['permission_group' => null, 'identifier' => 'roles:update', 'title' => 'Update Roles', 'description' => 'User can edit roles', 'active' => true],
]);
