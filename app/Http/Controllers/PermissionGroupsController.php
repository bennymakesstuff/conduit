<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePermissionGroupsRequest;
use App\Http\Requests\UpdatePermissionGroupsRequest;
use App\Models\PermissionGroups;
use App\Models\Roles;

class PermissionGroupsController extends Controller
{
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
     * @param  \App\Http\Requests\StorePermissionGroupsRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePermissionGroupsRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\PermissionGroups  $permissionGroups
     * @return \Illuminate\Http\Response
     */
    public function show(PermissionGroups $permissionGroups)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\PermissionGroups  $permissionGroups
     * @return \Illuminate\Http\Response
     */
    public function edit(PermissionGroups $permissionGroups)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdatePermissionGroupsRequest  $request
     * @param  \App\Models\PermissionGroups  $permissionGroups
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePermissionGroupsRequest $request, PermissionGroups $permissionGroups)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\PermissionGroups  $permissionGroups
     * @return \Illuminate\Http\Response
     */
    public function destroy(PermissionGroups $permissionGroups)
    {
        //
    }
}
