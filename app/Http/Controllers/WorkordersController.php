<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreWorkordersRequest;
use App\Http\Requests\UpdateWorkordersRequest;
use App\Models\Workorders;

class WorkordersController extends Controller
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
     * @param  \App\Http\Requests\StoreWorkordersRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreWorkordersRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Workorders  $workorders
     * @return \Illuminate\Http\Response
     */
    public function show(Workorders $workorders)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Workorders  $workorders
     * @return \Illuminate\Http\Response
     */
    public function edit(Workorders $workorders)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateWorkordersRequest  $request
     * @param  \App\Models\Workorders  $workorders
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateWorkordersRequest $request, Workorders $workorders)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Workorders  $workorders
     * @return \Illuminate\Http\Response
     */
    public function destroy(Workorders $workorders)
    {
        //
    }
}
