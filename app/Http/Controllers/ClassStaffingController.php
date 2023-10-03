<?php

namespace App\Http\Controllers;

use App\Models\Classes;
use App\Models\ClassStaffing;
use Illuminate\Http\Request;
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
        $classStaffing = ClassStaffing::with(['classes.site', 'classes.program', 'classes.dateRange', 'classes.createdByUser', 'classes.updatedByUser'])
        ->where('active_status', '1')
        ->get();

        if ($classStaffing->isEmpty()) {
            return response()->json(['error' => 'Classes not found'], 404);
        }

        return response()->json([
        'class_staffing' => $classStaffing,
    ]);
    }

    public function index2()
    {
        $classStaffing = ClassStaffing::with(['classes.site', 'classes.program', 'classes.dateRange', 'classes.createdByUser', 'classes.updatedByUser'])
        ->get();

        if ($classStaffing->isEmpty()) {
            return response()->json(['error' => 'Classes not found'], 404);
        }

        return response()->json([
        'class_staffing' => $classStaffing,
    ]);
    }

    /* public function mps()
    {
        $classStaffing = ClassStaffing::with(['classes.site', 'classes.program', 'classes.dateRange', 'classes.createdByUser', 'classes.updatedByUser'])
        ->where('active_status', '1')
        ->get();
    } */

    public function mps()
    {
        $totalTarget = Classes::with([
            'classes.site',
            'classes.program',
            'classes.dateRange',
            'classes.createdByUser',
            'classes.updatedByUser',
        ])->sum('total_target');

        $totalInternal = ClassStaffing::with([
            'classes.site',
            'classes.program',
            'classes.dateRange',
            'classes.createdByUser',
            'classes.updatedByUser',
        ])->sum('show_ups_internal');

        $totalExternal = ClassStaffing::with([
            'classes.site',
            'classes.program',
            'classes.dateRange',
            'classes.createdByUser',
            'classes.updatedByUser',
        ])->sum('show_ups_external');

        $totalShowups = ClassStaffing::with([
            'classes.site',
            'classes.program',
            'classes.dateRange',
            'classes.createdByUser',
            'classes.updatedByUser',
        ])->sum('show_ups_total');

        $totalCapsStarts = ClassStaffing::with([
            'classes.site',
            'classes.program',
            'classes.dateRange',
            'classes.createdByUser',
            'classes.updatedByUser',
        ])->sum('cap_starts');

        $fillRate = 0;
        if ($totalCapsStarts != 0) {
            $fillRate = ($totalTarget / $totalCapsStarts) * 100;
        }
        $totalDay1 = ClassStaffing::with([
            'classes.site',
            'classes.program',
            'classes.dateRange',
            'classes.createdByUser',
            'classes.updatedByUser',
        ])->sum('day_1');
        $totalDay2 = ClassStaffing::with([
            'classes.site',
            'classes.program',
            'classes.dateRange',
            'classes.createdByUser',
            'classes.updatedByUser',
        ])->sum('day_2');
        $totalDay3 = ClassStaffing::with([
            'classes.site',
            'classes.program',
            'classes.dateRange',
            'classes.createdByUser',
            'classes.updatedByUser',
        ])->sum('day_3');
        $totalDay4 = ClassStaffing::with([
            'classes.site',
            'classes.program',
            'classes.dateRange',
            'classes.createdByUser',
            'classes.updatedByUser',
        ])->sum('day_4');
        $totalDay5 = ClassStaffing::with([
            'classes.site',
            'classes.program',
            'classes.dateRange',
            'classes.createdByUser',
            'classes.updatedByUser',
        ])->sum('day_5');
        $fillRateDay1 = ($totalDay1 != 0) ? ($totalTarget / $totalDay1) * 100 : 0;
        $fillRateDay2 = ($totalDay2 != 0) ? ($totalTarget / $totalDay2) * 100 : 0;
        $fillRateDay3 = ($totalDay3 != 0) ? ($totalTarget / $totalDay3) * 100 : 0;
        $fillRateDay4 = ($totalDay4 != 0) ? ($totalTarget / $totalDay4) * 100 : 0;
        $fillRateDay5 = ($totalDay5 != 0) ? ($totalTarget / $totalDay5) * 100 : 0;
        $totalFilled = ClassStaffing::with([
            'classes.site',
            'classes.program',
            'classes.dateRange',
            'classes.createdByUser',
            'classes.updatedByUser',
        ])
        ->where('status', 'FILLED')
        ->sum('classes_number');

        $totalOpen = ClassStaffing::with([
            'classes.site',
            'classes.program',
            'classes.dateRange',
            'classes.createdByUser',
            'classes.updatedByUser',
        ])
        ->where('status', 'OPEN')
        ->sum('classes_number');
        $totalClasses = ClassStaffing::with([
            'classes.site',
            'classes.program',
            'classes.dateRange',
            'classes.createdByUser',
            'classes.updatedByUser',
        ])
        ->sum('classes_number');
        $mps = [
            'total_target' => $totalTarget,
            'internal' => $totalInternal,
            'external' => $totalExternal,
            'overall starts' => $totalShowups,
            'fill rate' => $fillRate,
            'day 1' => $totalDay1,
            'fill_rate_on_day_1' => $fillRateDay1,
            'day 2' => $totalDay2,
            'fill_rate_on_day_2' => $fillRateDay2,
            'day 3' => $totalDay3,
            'fill_rate_on_day_3' => $fillRateDay3,
            'day 4' => $totalDay4,
            'fill_rate_on_day_4' => $fillRateDay4,
            'day 5' => $totalDay5,
            'fill_rate_on_day5' => $fillRateDay5,
            'total_classes' => $totalClasses,
            'total_filled' => $totalFilled,
            'total_open' => $totalOpen,
            'filled_classes' => '',
        ];

        return response()->json([
            'mps' => $mps,
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
    public function update(Request $request, $id)
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
        'wave_no' => 'nullable',
        'agreed_start_date' => 'nullable',
        'erf_number' => 'nullable',
        'date_range_id' => 'required',
    ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $classes = Classes::find($request->input('classes_id'));
        $classes->wave_no = $request->input('wave_no');
        $classes->agreed_start_date = $request->input('agreed_start_date');
        $classes->erf_number = $request->input('erf_number');
        $classes->date_range_id = $request->input('date_range_id');
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
