<?php
// Permissions relevant to permissions
return array(
  //Permission Group
  ['identifier' => 'personal', 'title' => 'Personal', 'description' => null, 'active' => true],
  [
  // Permissions
  ['permission_group' => null, 'identifier' => 'personal:passwordchange', 'title' => 'Change Password', 'description' => 'User can change their own password',  'active' => true],
  ['permission_group' => null, 'identifier' => 'personal:namechange', 'title' => 'Change Names', 'description' => 'User can change their own names', 'active' => true],
  ['permission_group' => null, 'identifier' => 'personal:emailchange', 'title' => 'Change Email', 'description' => 'User can change their own email address', 'active' => true],
  ['permission_group' => null, 'identifier' => 'personal:phonechange', 'title' => 'Change Phone', 'description' => 'User can change their own phone number', 'active' => true],
]);
