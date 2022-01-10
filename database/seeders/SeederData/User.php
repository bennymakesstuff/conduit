<?php
// Permissions relevant to users
return array(
  //Permission Group
  ['identifier' => 'user', 'title' => 'Users', 'description' => null, 'active' => true],

  // Permissions
  [
  ['permission_group' => null, 'identifier' => 'user:view', 'title' => 'View Users', 'description' => 'User can view other users',  'active' => true],
  ['permission_group' => null, 'identifier' => 'user:create', 'title' => 'Create Users', 'description' => 'User can create new users', 'active' => true],
  ['permission_group' => null, 'identifier' => 'user:update', 'title' => 'Update Users', 'description' => 'User can edit users', 'active' => true],
]);
