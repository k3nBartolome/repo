<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Classes;
use App\Models\Sla_reason;
use App\Http\Resources\ClassesResource;
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
            'notice_weeks' => 'required',
            'notice_days' => 'required',
            'external_target' => 'required',
            'internal_target' => 'required',
            'total_target' => 'required',
            'type_of_hiring' => 'required',
            'within_sla' => 'required',
            'with_erf' => 'required',
            'remarks' => 'required',
            'status' => 'required',
            'approved_status' => 'required',
            'original_start_date' => 'required',
            'wfm_date_requested' => 'required',
            'program_id' => 'required',
            'site_id' => 'required',
            'created_by' => 'required',
            'is_active' => 'required',
        ] );

        $class = Classes::create( $validatedData );

        $validatedSlaData = $request->validate( [
            'out_of_sla_reason' => 'required',
        ] );
        $sla = Sla_reason::create( $validatedSlaData );

        return new  ClassesResource($class);
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
