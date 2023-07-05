<?php

namespace App\Http\Controllers;

use App\Models\ClassStaffing;
use App\Models\Classes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;

class ClassStaffingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $minutes = 60;
        $classStaffing = Cache::remember('classStaffing', $minutes, function () {
            $classStaffing = ClassStaffing::with(['classes.site', 'classes.program', 'classes.dateRange', 'classes.createdByUser', 'classes.updatedByUser'])
            ->where('active_status', '1')
            ->get();

            if ($classStaffing->isEmpty()) {
                return null;
            }

            return $classStaffing;
        });

        if ($classStaffing === null) {
            return response()->json(['error' => 'Classes not found'], 404);
        }

        return response()->json([
        'class_staffing' => $classStaffing,
    ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'show_ups_internal' => 'required',
            'show_ups_external' => 'required',
            'show_ups_total' => 'required',
            'deficit' => 'required',
            'percentage' => 'required',
            'status' => 'required',
            'day_1' => 'required',
            'day_2' => 'required',
            'day_3' => 'required',
            'day_4' => 'required',
            'day_5' => 'required',
            'day_6' => 'required',
            'total_endorsed' => 'required',
            'internals_hires' => 'required',
            'additional_extended_jo' => 'required',
            'with_jo' => 'required',
            'pending_jo' => 'required',
            'pending_berlitz' => 'required',
            'pending_ov' => 'required',
            'pending_pre_emps' => 'required',
            'classes_number' => 'required',
            'pipeline_total' => 'required',
            'pipeline_target' => 'required',
            'cap_starts' => 'required',
            'internals_hires_all' => 'required',
            'pipeline' => 'required',
            'additional_remarks' => 'required',
            'classes_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $staffing = new ClassStaffing();
        $staffing->fill($request->all());
        $staffing->transaction = 'Add Class Staffing';
        $staffing->active_status = '1';
        $staffing->save();
        $staffing->class_staffing_id = $staffing->id;
        $staffing->save();

        return response()->json([
            'staffing' => $staffing,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $class = ClassStaffing::with(['classes.site', 'classes.program', 'classes.dateRange', 'classes.createdByUser', 'classes.updatedByUser'])->find($id);

        if (!$class) {
            return response()->json(['error' => 'Classes not found'], 404);
        }

        return response()->json([
            'class' => $class,
        ]);
    }

    public function transaction($id)
    {
        $class = ClassStaffing::with(['classes.site', 'classes.program', 'classes.dateRange', 'classes.createdByUser', 'classes.updatedByUser'])->find($id);

        if (!$class) {
            return response()->json(['error' => 'Classes not found'], 404);
        }
        $class = ClassStaffing::with(['classes.site', 'classes.program', 'classes.dateRange', 'classes.createdByUser', 'classes.updatedByUser'])
    ->where('class_staffing_id', $class->class_staffing_id)
    ->get();

        return response()->json([
        'class' => $class,
    ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(ClassStaffing $ClassStaffing)
    {
    }

    /**
     * Update the specified resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id ,$classesId)
    {
        $validator = Validator::make($request->all(), [
            'show_ups_internal' => 'required',
            'show_ups_external' => 'required',
            'show_ups_total' => 'required',
            'deficit' => 'required',
            'percentage' => 'required',
            'status' => 'required',
            'day_1' => 'required',
            'day_2' => 'required',
            'day_3' => 'required',
            'day_4' => 'required',
            'day_5' => 'required',
            'day_6' => 'required',
            'total_endorsed' => 'required',
            'internals_hires' => 'required',
            'additional_extended_jo' => 'required',
            'with_jo' => 'required',
            'pending_jo' => 'required',
            'pending_berlitz' => 'required',
            'pending_ov' => 'required',
            'pending_pre_emps' => 'required',
            'classes_number' => 'required',
            'pipeline_total' => 'required',
            'cap_starts' => 'required',
            'internals_hires_all' => 'required',
            'pipeline' => 'required',
            'pipeline_target' => 'required',
            'additional_remarks' => 'required',
            'classes_id' => 'required',
            'updated_by' => 'required',
            'wave_no' => 'required',
            'agreed_start_date' => 'required',
            'erf_number' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }
        $classes = Classes::find($classesId);
        $classes->wave_no =  $request->input('wave_no');
        $classes->agreed_start_date =  $request->input('agreed_start_date');
        $classes->erf_number =  $request->input('erf_number');
        $classes->save();

        $class = ClassStaffing::find($id);
        $class->active_status = '0';
        $class->save();
        $newClass = $class->replicate();
        $newClass->transaction = 'Update';
        $newClass->updated_by = $request->input('updated_by');
        $newClass->fill($request->all());
        $newClass->active_status = '1';
        $newClass->save();

        return response()->json([
            'class' => $class,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(ClassStaffing $ClassStaffing)
    {
    }
}
