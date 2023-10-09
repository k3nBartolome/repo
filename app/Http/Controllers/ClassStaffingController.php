<?php

namespace App\Http\Controllers;

use App\Models\Classes;
use App\Models\ClassStaffing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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

    public function mps($dateRangeId = null, $monthNum = null, $siteId = null, $programId = null)
    {
        $staffing = DB::table('class_staffing')
    ->leftJoin('classes', 'class_staffing.classes_id', '=', 'classes.id')
    ->leftJoin('date_ranges', 'classes.date_range_id', '=', 'date_ranges.id')
    ->leftJoin('sites', 'classes.site_id', '=', 'sites.id')
    ->leftJoin('programs', 'classes.program_id', '=', 'programs.id')
    ->select(
        'class_staffing.*',
        'classes.*',
        'sites.*',
        'programs.*',
        'date_ranges.*',
        DB::raw('COALESCE(date_ranges.month_num, 0) as month_num'),
        DB::raw('COALESCE(date_ranges.month, 0) as month'),
        DB::raw('COALESCE(date_ranges.date_id, 0) as date_range_id'),
        DB::raw('COALESCE(sites.site_id, 0) as site_id'),
        DB::raw('COALESCE(programs.program_id, 0) as program_id')
    )
    ->where('class_staffing.active_status', 1);

        if ($dateRangeId !== null) {
            $staffing->where('date_ranges.id', $dateRangeId);
        }

        if ($monthNum !== null) {
            $staffing->orWhere('date_ranges.month_num', $monthNum);
        }

        if ($siteId !== null) {
            $staffing->where('sites.id', $siteId);
        }

        if ($programId !== null) {
            $staffing->where('programs.id', $programId);
        }

        $staffing = $staffing->get();

        $monthNames = [
    1 => 'January',
    2 => 'February',
    3 => 'March',
    4 => 'April',
    5 => 'May',
    6 => 'June',
    7 => 'July',
    8 => 'August',
    9 => 'September',
    10 => 'October',
    11 => 'November',
    12 => 'December',
];

        $groupedStaffing = $staffing->groupBy([
    'month_num',
]);

        $computedSums = [];

        $allMonthNums = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12];

        $grandTotals = [
    'total_target' => 0,
    'internal' => 0,
    'external' => 0,
    'total' => 0,
    'cap_starts' => 0,
    'day_1' => 0,
    'day_2' => 0,
    'day_3' => 0,
    'day_4' => 0,
    'day_5' => 0,
    'filled' => 0,
    'open' => 0,
    'classes' => 0,
];
        foreach ($allMonthNums as $monthNum) {
            $group = $groupedStaffing->get($monthNum, collect());
            $monthName = $monthNames[$monthNum];
            $computedSums[$monthName] = [
    'month' => $monthName,
        'total_target' => $group->sum('total_target'),
        'internal' => $group->sum('show_ups_internal'),
        'external' => $group->sum('show_ups_external'),
        'total' => $group->sum('show_ups_total'),
        'cap_starts' => $group->sum('cap_starts'),
        'day_1' => $group->sum('day_1'),
        'day_2' => $group->sum('day_2'),
        'day_3' => $group->sum('day_3'),
        'day_4' => $group->sum('day_4'),
        'day_5' => $group->sum('day_5'),
        'filled' => $group->sum('open'),
        'open' => $group->sum('filled'),
        'classes' => $group->sum('classes_number'),
    ];
            foreach ($grandTotals as $key => $value) {
                $grandTotals[$key] += $computedSums[$monthName][$key];
            }
        }
        $computedSums['Grand Total'] = $grandTotals;

        return response()->json([
    'mps' => $computedSums,
]);
    }

    public function mpsWeek($dateRangeId = null, $monthNum = null, $siteId = null, $programId = null)
    {
        $staffing = DB::table('class_staffing')
    ->leftJoin('classes', 'class_staffing.classes_id', '=', 'classes.id')
    ->leftJoin('date_ranges', 'classes.date_range_id', '=', 'date_ranges.id')
    ->leftJoin('sites', 'classes.site_id', '=', 'sites.id')
    ->leftJoin('programs', 'classes.program_id', '=', 'programs.id')
    ->select(
        'class_staffing.*',
        'classes.*',
        'sites.*',
        'programs.*',
        'date_ranges.*',
        DB::raw('COALESCE(date_ranges.date_id, 0) as date_range_id'),
        DB::raw('COALESCE(date_ranges.month_num, 0) as month_num'),
        DB::raw('COALESCE(date_ranges.date_range, 0) as week_name'),
        DB::raw('COALESCE(sites.site_id, 0) as site_id'),
        DB::raw('COALESCE(programs.program_id, 0) as program_id'),
        DB::raw('COALESCE(sites.name, 0) as site_name'),
        DB::raw('COALESCE(programs.name, 0) as program_name'),
    )
    ->where('class_staffing.active_status', 1);

        if ($dateRangeId !== null) {
            $staffing->where('date_ranges.id', $dateRangeId);
        }

        if ($monthNum !== null) {
            $staffing->orWhere('date_ranges.month_num', $monthNum);
        }

        if ($siteId !== null) {
            $staffing->where('sites.id', $siteId);
        }

        if ($programId !== null) {
            $staffing->where('programs.id', $programId);
        }

        $staffing = $staffing->get();
        $dateRangeIds = DB::table('date_ranges')->distinct()->pluck('date_id')->toArray();
        $siteIds = DB::table('sites')->distinct()->pluck('site_id')->toArray();
        $programIds = DB::table('programs')->distinct()->pluck('program_id')->toArray();
        $groupedStaffing = $staffing->groupBy('month_num');

        $computedSums = [];

        $grandTotals = [
            'total_target' => 0,
            'internal' => 0,
            'external' => 0,
            'total' => 0,
            'cap_starts' => 0,
            'day_1' => 0,
            'day_2' => 0,
            'day_3' => 0,
            'day_4' => 0,
            'day_5' => 0,
            'filled' => 0,
            'open' => 0,
            'classes' => 0,
        ];

        foreach ($dateRangeIds as $dateRangeId) {
            foreach ($siteIds as $siteId) {
                foreach ($programIds as $programId) {
            $group = $groupedStaffing->get($programId, collect());
            $computedSums[$programId] = [
                'month' => $group->isEmpty() ? null : $group->first()->month,
        'week_name' => $group->isEmpty() ? null : $group->first()->week_name,
        'program_name' => $group->isEmpty() ? null : $group->first()->program_name,
        'site_name' => $group->isEmpty() ? null : $group->first()->site_name,
                'total_target' => $group->sum('total_target'),
                'internal' => $group->sum('show_ups_internal'),
                'external' => $group->sum('show_ups_external'),
                'total' => $group->sum('show_ups_total'),
                'cap_starts' => $group->sum('cap_starts'),
                'day_1' => $group->sum('day_1'),
                'day_2' => $group->sum('day_2'),
                'day_3' => $group->sum('day_3'),
                'day_4' => $group->sum('day_4'),
                'day_5' => $group->sum('day_5'),
                'filled' => $group->sum('open'),
                'open' => $group->sum('filled'),
                'classes' => $group->sum('classes_number'),
            ];
            foreach ($grandTotals as $key => $value) {
                $grandTotals[$key] += $computedSums[$dateRangeId][$key];
            }
            if (!$group->isEmpty()) {
           
            }}}
        }
    
        $computedSums[] = [
            'month_num' => 'Grand Total',
            'week_name' => '',
            'total_target' => $grandTotals['total_target'],
            'internal' => $grandTotals['internal'],
            'external' => $grandTotals['external'],
            'total' => $grandTotals['total'],
            'cap_starts' => $grandTotals['cap_starts'],
            'day_1' => $grandTotals['day_1'],
            'day_2' => $grandTotals['day_2'],
            'day_3' => $grandTotals['day_3'],
            'day_4' => $grandTotals['day_4'],
            'day_5' => $grandTotals['day_5'],
            'filled' => $grandTotals['filled'],
            'open' => $grandTotals['open'],
            'classes' => $grandTotals['classes'],
        ];
    
        return response()->json([
            'mps' => $computedSums,
        ]);
    }

    /*     public function mps($dateRangeId = null, $monthNum = null, $siteId = null, $programId = null)
        {
            $staffing = DB::table('class_staffing')
                ->leftJoin('classes', 'class_staffing.classes_id', '=', 'classes.id')
                ->leftJoin('date_ranges', 'classes.date_range_id', '=', 'date_ranges.id')
                ->leftJoin('sites', 'classes.site_id', '=', 'sites.id')
                ->leftJoin('programs', 'classes.program_id', '=', 'programs.id')
                ->select(
                    'class_staffing.*',
                    'classes.*',
                    'sites.site_id as site_id',
                    'programs.program_id as program_id',
                    'date_ranges.date_id as date_range_id',
                    'date_ranges.month_num'
                )
                ->where('class_staffing.active_status', 1);


            if ($dateRangeId !== null) {
                $staffing->where('date_ranges.id', $dateRangeId);
            }

            if ($monthNum !== null) {
                $staffing->orWhere('date_ranges.month_num', $monthNum);
            }

            if ($siteId !== null) {
                $staffing->where('sites.id', $siteId);
            }

            if ($programId !== null) {
                $staffing->where('programs.id', $programId);
            }

            $staffing = $staffing->get();

            $groupedStaffing = $staffing->groupBy([
                'date_range_id',
                'site_id',
                'program_id',
            ]);

            $computedSums = [];

            foreach ($groupedStaffing as $key => $group) {
                $computedSums[$key] = [
                    'total_target' => $group->sum('total_target'),
                    'internal' => $group->sum('show_ups_internal'),
                    'external' => $group->sum('show_ups_external'),
                    'total' => $group->sum('show_ups_total'),
                    'cap_starts' => $group->sum('cap_starts'),
                    'day_1' => $group->sum('day_1'),
                    'day_2' => $group->sum('day_2'),
                    'day_3' => $group->sum('day_3'),
                    'day_4' => $group->sum('day_4'),
                    'day_5' => $group->sum('day_5'),
                    'filled' => $group->sum('open'),
                    'open' => $group->sum('filled'),
                    'classes' => $group->sum('classes_number'),
                ];
            }

            return response()->json([
                'mps' => $computedSums,
            ]);
        } */
    /*
    public function mps()
    {
        $withRelations = [
        'classes.site',
        'classes.program',
        'classes.dateRange',
        'classes.createdByUser',
        'classes.updatedByUser',
    ];
        $withRelations2 = [
        'site', 'program', 'dateRange', 'createdByUser', 'updatedByUser',
    ];

        $classStaffingQuery = ClassStaffing::with($withRelations);
        $classesQuery = Classes::with($withRelations2);

        $totalTarget = $classesQuery->sum('total_target');
        $totalInternal = $classStaffingQuery->sum('show_ups_internal');
        $totalExternal = $classStaffingQuery->sum('show_ups_external');
        $totalShowups = $classStaffingQuery->sum('show_ups_total');
        $totalCapsStarts = $classStaffingQuery->sum('cap_starts');
        $totalDay1 = $classStaffingQuery->sum('day_1');
        $totalDay2 = $classStaffingQuery->sum('day_2');
        $totalDay3 = $classStaffingQuery->sum('day_3');
        $totalDay4 = $classStaffingQuery->sum('day_4');
        $totalDay5 = $classStaffingQuery->sum('day_5');

        $totalFilled = $classStaffingQuery->where('status', 'FILLED')->sum('classes_number');

        $filledClasses = ($totalTarget != 0) ? (($totalFilled / $totalTarget) * 100) : 0;

        $mps = [
        'total_target' => $totalTarget,
        'internal' => $totalInternal,
        'external' => $totalExternal,
        'overall starts' => $totalShowups,
        'fill rate' => ($totalCapsStarts != 0) ? (($totalTarget / $totalCapsStarts) * 100) : 0,
        'day 1' => $totalDay1,
        'fill_rate_on_day_1' => ($totalDay1 != 0) ? ($totalTarget / $totalDay1) * 100 : 0,
        'day 2' => $totalDay2,
        'fill_rate_on_day_2' => ($totalDay2 != 0) ? ($totalTarget / $totalDay2) * 100 : 0,
        'day 3' => $totalDay3,
        'fill_rate_on_day_3' => ($totalDay3 != 0) ? ($totalTarget / $totalDay3) * 100 : 0,
        'day 4' => $totalDay4,
        'fill_rate_on_day_4' => ($totalDay4 != 0) ? ($totalTarget / $totalDay4) * 100 : 0,
        'day 5' => $totalDay5,
        'fill_rate_on_day5' => ($totalDay5 != 0) ? ($totalTarget / $totalDay5) * 100 : 0,
        'total_classes' => $classStaffingQuery->sum('classes_number'),
        'total_filled' => $totalFilled,
        'total_open' => $classStaffingQuery->where('status', 'OPEN')->sum('classes_number'),
        'filled_classes' => $filledClasses,
    ];

        return response()->json([
        'mps' => $mps,
    ]);
    } */

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
            'open' => 'required',
            'filled' => 'required',
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
