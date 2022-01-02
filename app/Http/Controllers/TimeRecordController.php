<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTimeRecordRequest;
use App\Http\Requests\UpdateTimeRecordRequest;
use App\Models\TimeRecord;

class TimeRecordController extends Controller
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
     * @param  \App\Http\Requests\StoreTimeRecordRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreTimeRecordRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\TimeRecord  $timeRecord
     * @return \Illuminate\Http\Response
     */
    public function show(TimeRecord $timeRecord)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\TimeRecord  $timeRecord
     * @return \Illuminate\Http\Response
     */
    public function edit(TimeRecord $timeRecord)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateTimeRecordRequest  $request
     * @param  \App\Models\TimeRecord  $timeRecord
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateTimeRecordRequest $request, TimeRecord $timeRecord)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\TimeRecord  $timeRecord
     * @return \Illuminate\Http\Response
     */
    public function destroy(TimeRecord $timeRecord)
    {
        //
    }
}
