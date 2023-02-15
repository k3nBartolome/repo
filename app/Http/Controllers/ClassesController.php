<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Classes;
use App\Models\Sla_reason;

class ClassesController extends Controller
 {
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */

    public function index()
    {
        $classes = Classes::with('sites', 'programs', 'sla_reason', 'createdBy', 'updatedBy')->get();
        return response()->json([
            'classes' => $classes
        ], 200);
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
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */

    public function store( Request $request )
 {
        $validatedData = $request->validate( [
            'notice_weeks' => 'nullable',
            'notice_days' => 'nullable',
            'external_target' => 'nullable',
            'internal_target' => 'nullable',
            'target' => 'nullable',
            'pipeline_utilized' => 'nullable',
            'type_of_hiring' => 'nullable',
            'within_sla' => 'nullable',
            'with_erf' => 'nullable',
            'reason_for_counter_proposal' => 'nullable',
            'remarks' => 'nullable',
            'status' => 'nullable',
            'update_status' => 'nullable',
            'approved_status' => 'nullable',
            'approved_date' => 'nullable',
            'cancelled_date' => 'nullable',
            'date_requested' => 'nullable',
            'delivery_date' => 'nullable',
            'entry_date' => 'nullable',
            'original_start_date' => 'nullable',
            'pushback_start_date_ta' => 'nullable',
            'pushback_start_date_wf' => 'nullable',
            'requested_start_date_by_wf' => 'nullable',
            'start_date_committed_by_ta' => 'nullable',
            'supposed_start_date' => 'nullable',
            'wfm_date_requested' => 'nullable',
            'program_id' => 'nullable',
            'site_id' => 'nullable',
            'sla_reason_id' => 'nullable',
            'cancelled_by' => 'nullable',
            'created_by' => 'nullable',
            'requested_by' => 'nullable',
            'updated_by' => 'nullable',
            'approved_by' => 'nullable',
            'is_active' => 'nullable',
        ] );

        $class = Classes::create( $validatedData );

        $validatedSlaData = $request->validate( [
            'reason' => 'nullable',
            'created_by' => 'nullable',
            'updated_by' => 'nullable',
        ] );
        $sla = Sla_reason::create( $validatedSlaData );

        return response()->json( [
            'message' => 'Class and SLA reason successfully created',
            'class' => $class,
            'sla' => $sla,
        ], 201 );
    }

    /**
    * Display the specified resource.
    *
    * @param  \App\Models\Classes  $classes
    * @return \Illuminate\Http\Response
    */

    public function show( Classes $classes )
 {
        //
    }

    /**
    * Show the form for editing the specified resource.
    *
    * @param  \App\Models\Classes  $classes
    * @return \Illuminate\Http\Response
    */

    public function edit( Classes $classes )
 {
        //
    }

    /**
    * Update the specified resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  \App\Models\Classes  $classes
    * @return \Illuminate\Http\Response
    */

    public function update( Request $request, Classes $classes )
 {
        //
    }

    /**
    * Remove the specified resource from storage.
    *
    * @param  \App\Models\Classes  $classes
    * @return \Illuminate\Http\Response
    */

    public function destroy( Classes $classes )
 {
        //
    }
}
