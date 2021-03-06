<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRolesRequest;
use App\Http\Requests\UpdateRolesRequest;
use App\Models\Roles;
use DateTime;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Ramsey\Uuid\Uuid;

class RolesController extends Controller
{
    /**
     * Returns a count of all roles
     *
     * @return string
     */
    public function getRolesCount(): string
    {
      $roles = Roles::all();

      if ($roles === null) {
        return response()->json([
          'status' => false,
          'title' => 'ROLES',
          'message' => 'No Roles Available'
        ]);
      }

      return json_encode([
        'status' => true,
        'title' => 'ROLES',
        'count' => $roles->count()
      ]);
    }

    /**
     * Returns a list of all roles
     *
     * @return string
     */
    public function getRoles(): string
    {
      $roles = Roles::all();

      if ($roles === null) {
        return response()->json([
          'status' => false,
          'title' => 'ROLES',
          'message' => 'No Roles Available'
        ]);
      }

      return json_encode([
        'status' => true,
        'title' => 'ROLES',
        'roles' => $roles
      ]);
    }


  /**
   * Returns details of a single role
   *
   * @param $uuid
   * @return string
   */
  public function getSingleRole($uuid): string
  {
    $role = Roles::where('uuid', '=', $uuid)->first();

    $role->permissions = json_decode($role->permissions);

    if ($role === null) {
      return response()->json([
        'status' => false,
        'title' => 'ROLES',
        'message' => 'Role not found'
      ]);
    }

    return json_encode([
      'status' => true,
      'title' => 'ROLES',
      'role' => $role
    ]);
  }


    public function createRole(Request $request)
    {
      $role = (object) $request->get('new_role');

      try {
        // Generate UUID
        $uuid_factory = Uuid::getFactory();
        $uuid = $uuid_factory->fromDateTime(new DateTime('now'));

        $new_role = new Roles();
        $new_role->setAttribute('uuid', $uuid);
        $new_role->setAttribute('title', $role->title);
        $new_role->setAttribute('description', $role->description);
        $new_role->setAttribute('active', true);
        $new_role->setAttribute('group', $role->group);
        $new_role->setAttribute('permissions', json_encode($role->permissions));
        $new_role->save();

        return response()->json([
          'status' => true,
          'title' => 'ROLE CREATE',
          'message' => 'Role created successfully'
        ]);
      }
      catch (Exception $e) {
        return response()->json([
          'status' => false,
          'title' => 'ROLE CREATE',
          'message' => 'Could not create new role',
          'error' => $e
        ]);
      }
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
     * @param  \App\Http\Requests\StoreRolesRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRolesRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Roles  $roles
     * @return \Illuminate\Http\Response
     */
    public function show(Roles $roles)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Roles  $roles
     * @return \Illuminate\Http\Response
     */
    public function edit(Roles $roles)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateRolesRequest  $request
     * @param  \App\Models\Roles  $roles
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRolesRequest $request, Roles $roles)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Roles  $roles
     * @return \Illuminate\Http\Response
     */
    public function destroy(Roles $roles)
    {
        //
    }
}
