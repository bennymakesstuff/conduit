<?php

/**
 * Include All Routes for API v1 here
 */

use \Illuminate\Support\Facades\Route;

Route::group([
  'prefix' => 'v1',
], function (){

  /**
   * User Routes
   */
  require_once 'users/user_routes.php';
  require_once 'permissions/permissions_routes.php';
  require_once 'roles/roles_routes.php';


});

