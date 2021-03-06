<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePermissionsRequest;
use App\Http\Requests\UpdatePermissionsRequest;
use App\Models\PermissionGroups;
use App\Models\Permissions;

class PermissionsController extends Controller
{

   /**
    * Returns a list of all permissions
    *
    * @return string
    */
    public function getPermissions(): string
    {
      $permissions = Permissions::all();

      if ($permissions === null) {
        return response()->json([
          'status' => false,
          'title' => 'PERMISSIONS',
          'message' => 'No Permissions Available'
        ]);
      }

      /** @var Permissions $permission */
      foreach ($permissions as $permission) {

        $group = PermissionGroups::query()
          ->find($permission->getAttribute('permission_group'));
        $permission['permission_group'] = $group;
      }

      return json_encode([
        'status' => true,
        'title' => 'PERMISSIONS',
        'permissions' => $permissions
      ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StorePermissionsRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePermissionsRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Permissions  $permissions
     * @return \Illuminate\Http\Response
     */
    public function show(Permissions $permissions)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Permissions  $permissions
     * @return \Illuminate\Http\Response
     */
    public function edit(Permissions $permissions)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdatePermissionsRequest  $request
     * @param  \App\Models\Permissions  $permissions
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePermissionsRequest $request, Permissions $permissions)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Permissions  $permissions
     * @return \Illuminate\Http\Response
     */
    public function destroy(Permissions $permissions)
    {
        //
    }
}
