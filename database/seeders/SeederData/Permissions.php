<?php
// Permissions relevant to permissions
return array(
  //Permission Group
  ['identifier' => 'permissions', 'title' => 'Permissions', 'description' => null, 'active' => true],
  [
  // Permissions
  ['permission_group' => null, 'identifier' => 'permissions:view', 'title' => 'View Permissions', 'description' => 'User can view permissions',  'active' => true],
  ['permission_group' => null, 'identifier' => 'permissions:create', 'title' => 'Create Permissions', 'description' => 'User can create new permissions', 'active' => true],
  ['permission_group' => null, 'identifier' => 'permissions:update', 'title' => 'Update Permissions', 'description' => 'User can edit permissions', 'active' => true],
]);
