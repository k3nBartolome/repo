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

    public function mpsWeek()
    {
        
        $uniqueMonths = DB::table('date_ranges')
        ->select([DB::raw('COALESCE(month_num, 0) as month_num')])
        ->distinct()
        ->get()
        ->pluck('month_num')
        ->toArray();

        // Query distinct site_id values from the database
        $uniqueSiteIds = DB::table('sites')
        ->select([DB::raw('COALESCE(site_id, 0) as site_id')])
        ->distinct()
        ->get()
        ->pluck('site_id')
        ->toArray();

        // Query distinct program_id values from the database
        $uniqueProgramIds = DB::table('programs')
        ->select([DB::raw('COALESCE(program_id, 0) as program_id')])
        ->distinct()
        ->get()
        ->pluck('program_id')
        ->toArray();
        $distinctDateRanges = DB::table('date_ranges')
        ->select([
            'date_id',
            DB::raw('COALESCE(date_ranges.date_range, null) as week_name'),
        ])
        ->distinct()
        ->get();

        // Initialize the computed sums array
        $computedSums = [];

        // Initialize the grand totals array
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

        // Iterate through the distinct date_range_id values
        foreach ($distinctDateRanges as $dateRangeData) {
            $dateRangeId = $dateRangeData->date_id;
            $weekName = $dateRangeData->week_name;
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
                DB::raw('COALESCE(date_ranges.month, null) as month'),
                DB::raw('COALESCE(date_ranges.date_range, null) as week_name'),
                DB::raw('COALESCE(sites.site_id, 0) as site_id'),
                DB::raw('COALESCE(programs.program_id, 0) as program_id'),
                DB::raw('COALESCE(sites.name, null) as site_name'),
                DB::raw('COALESCE(programs.name, null) as program_name')
            )
            ->where('class_staffing.active_status', 1)
            ->where('date_ranges.date_id', $dateRangeId)
            ->get();
            $computedSums[$dateRangeId] = [];
            foreach ($uniqueMonths as $month) {
                $computedSums[$dateRangeId][$month] = [];
                foreach ($uniqueSiteIds as $siteId) {
                    $computedSums[$dateRangeId][$month][$siteId] = [];
                    foreach ($uniqueProgramIds as $programId) {
                        $computedSums[$dateRangeId][$month][$siteId][$programId] = [];

                        $WeekMonthSiteProgram = $staffing
                        ->where('month_num', $month)
                        ->where('site_id', $siteId)
                        ->where('program_id', $programId)
                        ->where('date_range_id', $dateRangeId);

                        $sums = [
                        'total_target' => $WeekMonthSiteProgram->sum('total_target'),
                        'internal' => $WeekMonthSiteProgram->sum('show_ups_internal'),
                        'external' => $WeekMonthSiteProgram->sum('show_ups_external'),
                        'total' => $WeekMonthSiteProgram->sum('show_ups_total'),
                        'cap_starts' => $WeekMonthSiteProgram->sum('cap_starts'),
                        'day_1' => $WeekMonthSiteProgram->sum('day_1'),
                        'day_2' => $WeekMonthSiteProgram->sum('day_2'),
                        'day_3' => $WeekMonthSiteProgram->sum('day_3'),
                        'day_4' => $WeekMonthSiteProgram->sum('day_4'),
                        'day_5' => $WeekMonthSiteProgram->sum('day_5'),
                        'filled' => $WeekMonthSiteProgram->sum('open'),
                        'open' => $WeekMonthSiteProgram->sum('filled'),
                        'classes' => $WeekMonthSiteProgram->sum('classes_number'),
                    ];

                        if (array_sum($sums) > 0 && !in_array(null, $WeekMonthSiteProgram->pluck('month')->toArray())) {
                            $computedSums[$dateRangeId][$month][$siteId][$programId] = [
                            'month' => $WeekMonthSiteProgram->first()->month,
                            'week_name' => $WeekMonthSiteProgram->first()->week_name,
                            'site_name' => $WeekMonthSiteProgram->first()->site_name,
                            'program_name' => $WeekMonthSiteProgram->first()->program_name,
                            'total_target' => $sums['total_target'],
                            'internal' => $sums['internal'],
                            'external' => $sums['external'],
                            'total' => $sums['total'],
                            'cap_starts' => $sums['cap_starts'],
                            'day_1' => $sums['day_1'],
                            'day_2' => $sums['day_2'],
                            'day_3' => $sums['day_3'],
                            'day_4' => $sums['day_4'],
                            'day_5' => $sums['day_5'],
                            'filled' => $sums['filled'],
                            'open' => $sums['open'],
                            'classes' => $sums['classes'],
                            'date_range_id' => $dateRangeId,
                        ];
                        }
                    }
                }
            }
        }

        $response = [
        'mps' => $computedSums,
    ];

        return response()->json($response);
    }

    private function removeEmptyArrays(&$array)
    {
        foreach ($array as $key => &$value) {
            if (is_array($value)) {
                $this->removeEmptyArrays($value);
                if (empty($value)) {
                    unset($array[$key]);
                }
            }
        }
    }

    public function mpsMonth(Request $request)
{
    // Get the site ID from the request
    $siteId = $request->input('site_id');

    // Query distinct month_num values and month names from the database
    $distinctMonths = DB::table('date_ranges')
        ->select([
            'month_num',
            DB::raw('COALESCE(date_ranges.month, 0) as month_name'),
        ])
        ->distinct()
        ->get();

    // Initialize the computed sums array
    $computedSums = [];

    // Initialize the grand totals array
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

    // Iterate through the distinct month_num values
    foreach ($distinctMonths as $monthData) {
        $monthNum = $monthData->month_num;
        $monthName = $monthData->month_name;

        // Query the staffing data for the current month with the month name and site filter
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
                DB::raw('COALESCE(date_ranges.month, null) as month_name'),
                DB::raw('COALESCE(date_ranges.date_range, null) as week_name'),
                DB::raw('COALESCE(sites.site_id, 0) as site_id'),
                DB::raw('COALESCE(programs.program_id, 0) as program_id'),
                DB::raw('COALESCE(sites.name, null) as site_name'),
                DB::raw('COALESCE(programs.name, null) as program_name')
            )
            ->where('class_staffing.active_status', 1)
            ->where('date_ranges.month_num', $monthNum)
            ->when($siteId, function ($query) use ($siteId) {
                return $query->where('sites.id', $siteId);
            })
            ->get();

        // Calculate sums for the current month
        $computedSums[$monthName] = [
            'month' => $monthName,
            'total_target' => $staffing->sum('total_target'),
            'internal' => $staffing->sum('show_ups_internal'),
            'external' => $staffing->sum('show_ups_external'),
            'total' => $staffing->sum('show_ups_total'),
            'cap_starts' => $staffing->sum('cap_starts'),
            'day_1' => $staffing->sum('day_1'),
            'day_2' => $staffing->sum('day_2'),
            'day_3' => $staffing->sum('day_3'),
            'day_4' => $staffing->sum('day_4'),
            'day_5' => $staffing->sum('day_5'),
            'filled' => $staffing->sum('open'),
            'open' => $staffing->sum('filled'),
            'classes' => $staffing->sum('classes_number'),
        ];

        // Update the grand totals
        foreach ($grandTotals as $key => $value) {
            $grandTotals[$key] += $computedSums[$monthName][$key];
        }
    }

    // Add the grand totals to the computed sums
    $computedSums['Grand Total'] = $grandTotals;

    return response()->json([
        'mps' => $computedSums,
    ]);
}


public function mpsSite(Request $request)
{
    // Get the month number and program ID from the request
    $monthNum = $request->input('month_num');
    $programId = $request->input('program_id'); // Add this line

    // Query distinct site_id values and site names from the database
    $distinctSites = DB::table('sites')
        ->select([
            'site_id',
            DB::raw('COALESCE(sites.name, 0) as site_name'),
        ])
        ->distinct()
        ->get();

    // Initialize the computed sums array
    $computedSums = [];

    // Initialize the grand totals array
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

    foreach ($distinctSites as $siteData) {
        $siteId = $siteData->site_id;
        $siteName = $siteData->site_name;

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
                DB::raw('COALESCE(date_ranges.month, null) as month'),
                DB::raw('COALESCE(date_ranges.date_range, null) as week_name'),
                DB::raw('COALESCE(sites.site_id, 0) as site_id'),
                DB::raw('COALESCE(programs.program_id, 0) as program_id'),
                DB::raw('COALESCE(sites.name, null) as site_name'),
                DB::raw('COALESCE(programs.name, null) as program_name')
            )
            ->where('class_staffing.active_status', 1)
            ->where('sites.site_id', $siteId)
            ->when($monthNum, function ($query) use ($monthNum) {
                return $query->where('date_ranges.month_num', 'LIKE', '%' . $monthNum . '%');
            })
            ->when($programId, function ($query) use ($programId) {
                return $query->where('programs.program_id', 'LIKE', '%' . $programId . '%');
            })
            ->get();

        // Calculate sums for the current site
        $computedSums[$siteId] = [
            'site' => $siteName,
            'total_target' => $staffing->sum('total_target'),
            'internal' => $staffing->sum('show_ups_internal'),
            'external' => $staffing->sum('show_ups_external'),
            'total' => $staffing->sum('show_ups_total'),
            'cap_starts' => $staffing->sum('cap_starts'),
            'day_1' => $staffing->sum('day_1'),
            'day_2' => $staffing->sum('day_2'),
            'day_3' => $staffing->sum('day_3'),
            'day_4' => $staffing->sum('day_4'),
            'day_5' => $staffing->sum('day_5'),
            'filled' => $staffing->sum('open'),
            'open' => $staffing->sum('filled'),
            'classes' => $staffing->sum('classes_number'),
        ];

        // Update the grand totals
        foreach ($grandTotals as $key => $value) {
            $grandTotals[$key] += $computedSums[$siteId][$key];
        }
    }

    // Add the grand totals to the computed sums
    $computedSums['Grand Total'] = $grandTotals;

    return response()->json([
        'mps' => $computedSums,
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
