<?php

namespace App\Http\Controllers;

use App\Exports\DashboardClassesExport;
use App\Exports\MyExport;
use App\Http\Resources\ClassesResource;
use App\Models\Classes;
use App\Models\ClassStaffing;
use App\Models\DateRange;
use App\Models\Program;
use App\Models\SmartRecruitData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class ClassesController extends Controller
{
    public function srCompliance(Request $request)
    {
        $appstepIDs = [1, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 17, 18, 19, 20, 30, 32, 33, 34, 36, 40, 41, 42, 43, 44, 45, 46, 50, 53, 54, 55, 56, 59, 60, 69, 70, 73, 74, 76, 78, 79, 80, 81, 87, 88];

        $result = SmartRecruitData::on('secondary_sqlsrv')
        ->select('Step', 'AppStep', 'Site', \DB::raw('COUNT(*) as Count'))
        ->groupBy('Step', 'AppStep', 'Site')
        ->orderBy('Step')
        ->orderBy('AppStep')
        ->whereIn('ApplicationStepStatusId', $appstepIDs)
        ->orderBy('Site')

        ->get();

        $groupedData = [];

        foreach ($result as $item) {
            $combinedStepAppStep = $item->AppStep;

            if (!isset($groupedData[$combinedStepAppStep])) {
                $groupedData[$combinedStepAppStep] = [
                'Bridgetowne' => 0,
                'Clark' => 0,
                'Davao' => 0,
                'Makati' => 0,
                'MOA' => 0,
                'QC North EDSA' => 0,
            ];
            }

            $groupedData[$combinedStepAppStep][$item->Site] += $item->Count;
        }

        $formattedResult = [];

        foreach ($groupedData as $combinedStepAppStep => $siteCounts) {
            $formattedResult[] = array_merge(['CombinedStepAppStep' => $combinedStepAppStep], $siteCounts);
        }

        return response()->json(['sr' => $formattedResult]);
    }

    /*  public function srCompliance(Request $request)
    {
    $fourMonthsBeforeNow = Carbon::now()->subMonth(12)->startOfMonth();

    $filteredData = SmartRecruitData::on('secondary_sqlsrv')
    ->select([
    'ApplicantId','ApplicationInfoId', 'DateOfApplication', 'LastName',
    'FirstName', 'MiddleName', 'MobileNo', 'Site', 'GenSource', 'SpecSource',
    'Status', 'QueueDate', 'Interviewer', 'LOB', 'RescheduleDate', 'Step',
    'AppStep', 'ApplicationStepStatusId', 'WordQuiz', 'SVA', 'Address',
    'Municipality', 'Province', 'last_update_date'
    ])
    ->where('DateOfApplication', '>=', $fourMonthsBeforeNow)
    ->get();

    $counts = [];
    foreach ($filteredData as $data) {
    foreach ($data->toArray() as $key => $value) {
    if (!isset($counts[$key])) {
    $counts[$key] = [];
    }
    if (!isset($counts[$key][$value])) {
    $counts[$key][$value] = 0;
    }
    $counts[$key][$value]++;
    }
    }

    return response()->json([
    'sr' =>  $counts,
    ]);
    } */
    /*
    public function srCompliance(Request $request)
    {
    $fourMonthsBeforeNow = Carbon::now()->subDay(1);

    $filteredData = SmartRecruitData::on('secondary_sqlsrv')
    ->select([
    'ApplicantId', 'DateOfApplication', 'Address',
    'Municipality', 'Province', /* 'ApplicationInfoId', 'DateOfApplication', 'LastName',
    'FirstName', 'MiddleName', 'MobileNo', 'Site', 'GenSource', 'SpecSource',
    'Status', 'QueueDate', 'Interviewer', 'LOB', 'RescheduleDate', 'Step',sss
    'AppStep', 'ApplicationStepStatusId', 'WordQuiz', 'SVA', 'Address',
    'Municipality', 'Province', 'last_update_date'
    ])

    ->where('DateOfApplication', '>=', $fourMonthsBeforeNow)
    ->get();

    return response()->json([
    'sr' => $filteredData,
    ]);
    }
     */

    public function perxFilter(Request $request)
    {
        $query = DB::connection('secondary_sqlsrv')
            ->table('PERX_DATA');

        if ($request->has('filter_lastname')) {
            $filterLastName = $request->input('filter_lastname');

            if (!empty($filterLastName)) {
                $query->where('LastName', 'LIKE', '%'.$filterLastName.'%');
            }
        }

        if ($request->has('filter_firstname')) {
            $filterFirstName = $request->input('filter_firstname');

            if (!empty($filterFirstName)) {
                $query->where('FirstName', 'LIKE', '%'.$filterFirstName.'%');
            }
        }

        if ($request->has('filter_site')) {
            $filterSite = $request->input('filter_site');

            if (!empty($filterSite)) {
                $query->where('Site', 'LIKE', '%'.$filterSite.'%');
            }
        }
        if ($request->has('filter_date_start') && $request->has('filter_date_end')) {
            $filterDateStart = $request->input('filter_date_start');
            $filterDateEnd = $request->input('filter_date_end');

            if (!empty($filterDateStart) && !empty($filterDateEnd)) {
                $startDate = date('Y-m-d', strtotime($filterDateStart));
                $endDate = date('Y-m-d', strtotime($filterDateEnd));

                $endDate = date('Y-m-d', strtotime($endDate.' +1 day'));

                $query->where('DateOfApplication', '>=', $startDate)
                    ->where('DateOfApplication', '<', $endDate);
            }
        }

        if ($request->has('filter_contact')) {
            $filterContact = $request->input('filter_contact');

            if (!empty($filterContact)) {
                $query->where('MobileNo', 'LIKE', '%'.$filterContact.'%');
            }
        }

        $filteredData = $query->get();

        return response()->json([
            'perx' => $filteredData,
        ]);
    }

    public function exportFilteredData(Request $request)
    {
        $query = DB::connection('secondary_sqlsrv')
            ->table('PERX_DATA');

        if ($request->has('filter_lastname')) {
            $filterLastName = $request->input('filter_lastname');

            if (!empty($filterLastName)) {
                $query->where('LastName', 'LIKE', '%'.$filterLastName.'%');
            }
        }

        if ($request->has('filter_firstname')) {
            $filterFirstName = $request->input('filter_firstname');

            if (!empty($filterFirstName)) {
                $query->where('FirstName', 'LIKE', '%'.$filterFirstName.'%');
            }
        }

        if ($request->has('filter_site')) {
            $filterSite = $request->input('filter_site');

            if (!empty($filterSite)) {
                $query->where('Site', 'LIKE', '%'.$filterSite.'%');
            }
        }
        if ($request->has('filter_date_start') && $request->has('filter_date_end')) {
            $filterDateStart = $request->input('filter_date_start');
            $filterDateEnd = $request->input('filter_date_end');

            if (!empty($filterDateStart) && !empty($filterDateEnd)) {
                $startDate = date('Y-m-d', strtotime($filterDateStart));
                $endDate = date('Y-m-d', strtotime($filterDateEnd));

                $endDate = date('Y-m-d', strtotime($endDate.' +1 day'));

                $query->where('DateOfApplication', '>=', $startDate)
                    ->where('DateOfApplication', '<', $endDate);
            }
        }

        if ($request->has('filter_contact')) {
            $filterContact = $request->input('filter_contact');

            if (!empty($filterContact)) {
                $query->where('MobileNo', 'LIKE', '%'.$filterContact.'%');
            }
        }

        $filteredData = $query->get();

        $filteredDataArray = $filteredData->toArray();

        return Excel::download(new MyExport($filteredDataArray), 'filtered_perx_data.xlsx');
    }

    public function index()
    {
        $minutes = 60;
        $data = DB::connection('secondary_sqlsrv')
            ->table('PERX_DATA')
            ->select(
                'DateOfApplication',
                'LastName',
                'FirstName',
                'MiddleName',
                'MobileNo',
                'Site',
                'GenSource',
                'SpecSource',
                'Step',
                'AppStep',
                'PERX_HRID',
                'PERX_NAME',
                'OSS_HRID',
                'OSS_FNAME',
                'OSS_LNAME',
                'OSS_LOB',
                'OSS_SITE'
            )->get();

        $data = cache()->remember('perx_data', $minutes, function () use ($data) {
            return $data;
        });

        return response()->json([
            'perx' => $data,
        ]);
    }

    public function sumTotalTarget()
    {
        $total = Classes::where('status', 'active')
            ->sum('total_target');

        return response()->json(['total_target' => $total]);
    }

    public function countStatus()
    {
        $counts = [
            'active' => Classes::whereHas('site', function ($query) {
                $query->where('country', '=', 'Philippines');
            })
                ->where('status', 'active')
                ->count(),

            'cancelled' => Classes::whereHas('site', function ($query) {
                $query->where('country', '=', 'Philippines');
            })
                ->where('status', 'cancelled')
                ->count(),

            'moved' => Classes::whereHas('site', function ($query) {
                $query->where('country', '=', 'Philippines');
            })
                ->where('status', 'moved')
                ->count(),
        ];

        return $counts;
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'notice_weeks' => 'required',
            'notice_days' => 'required',
            'external_target' => 'required',
            'internal_target' => 'required',
            'total_target' => 'required',
            'type_of_hiring' => 'required',
            'within_sla' => 'required',
            'with_erf' => 'required',
            'erf_number' => 'nullable',
            'remarks' => 'required',
            'status' => 'required',
            'approved_status' => 'required',
            'original_start_date' => 'required',
            'wfm_date_requested' => 'required',
            'program_id' => 'required',
            'site_id' => 'required',
            'created_by' => 'required',
            'date_range_id' => 'required',
            'category' => 'required',
            'approved_by' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $class = new Classes();
        $class->fill($request->all());
        $class->agreed_start_date = $request->input('original_start_date');
        $class->changes = 'Add Class';
        $class->save();
        $class->pushedback_id = $class->id;
        $class->save();
        $classStaffing = new ClassStaffing();
        $classStaffing->classes_id = $class->id;
        $classStaffing->show_ups_internal = '0';
        $classStaffing->show_ups_external = '0';
        $classStaffing->show_ups_total = '0';
        $classStaffing->deficit = '0';
        $classStaffing->percentage = '0';
        $classStaffing->status = '0';
        $classStaffing->day_1 = '0';
        $classStaffing->day_2 = '0';
        $classStaffing->day_3 = '0';
        $classStaffing->day_4 = '0';
        $classStaffing->day_5 = '0';
        $classStaffing->day_6 = '0';
        $classStaffing->open = '0';
        $classStaffing->filled = '0';
        $classStaffing->total_endorsed = '0';
        $classStaffing->internals_hires = '0';
        $classStaffing->additional_extended_jo = '0';
        $classStaffing->with_jo = '0';
        $classStaffing->pending_jo = '0';
        $classStaffing->pending_berlitz = '0';
        $classStaffing->pending_ov = '0';
        $classStaffing->pending_pre_emps = '0';
        $classStaffing->classes_number = '0';
        $classStaffing->pipeline_total = '0';
        $classStaffing->pipeline_target = '0';
        $classStaffing->cap_starts = '0';
        $classStaffing->internals_hires_all = '0';
        $classStaffing->pipeline = '0';
        $classStaffing->additional_remarks = '0';
        $classStaffing->transaction = 'Add Class Staffing';
        $classStaffing->active_status = 1;
        $classStaffing->save();
        $classStaffing->class_staffing_id = $classStaffing->id;
        $classStaffing->save();
        /*
        $customEmail = 'padillakryss@gmail.com';

        Notification::route('mail', $customEmail)->notify(new ClassNotification($customEmail)); */

        // @ts-ignore
        return new ClassesResource($class);
    }

    public function destroy($id)
    {
        $classes = Classes::find($id);

        if (!$classes) {
            return response()->json(['error' => 'Classes not found'], 404);
        }

        $classes->delete();

        return response()->json(null, 204);
    }

    public function show($id)
    {
        $class = Classes::with(['site', 'program', 'dateRange', 'createdByUser', 'updatedByUser'])->find($id);

        if (!$class) {
            return response()->json(['error' => 'Class not found'], 404);
        }

        return response()->json([
            'class' => $class,
        ]);
    }

    public function staffing($classesId)
    {
        $class = Classes::with(['site', 'program', 'dateRange', 'createdByUser', 'updatedByUser'])->find($classesId);

        if (!$class) {
            return response()->json(['error' => 'Class not found'], 404);
        }

        return response()->json([
            'class' => $class,
        ]);
    }

    public function transaction($id)
    {
        $class = Classes::with(['site', 'program', 'dateRange', 'createdByUser', 'updatedByUser'])->find($id);

        if (!$class) {
            return response()->json(['error' => 'Class not found'], 404);
        }

        $classes = Classes::with(['site', 'program', 'dateRange', 'createdByUser', 'updatedByUser'])
            ->where('pushedback_id', $class->pushedback_id)
            ->get();

        return response()->json([
            'classes' => $classes,
        ]);
    }

    public function classesall()
    {
        $classes = Classes::whereHas('site', function ($query) {
            $query->where('country', '=', 'Philippines');
        })
            ->with('site', 'program', 'dateRange', 'createdByUser', 'updatedByUser')
            ->where('status', 'Active')
            ->get();

        return response()->json([
            'classes' => $classes,
        ]);
    }

    public function cStat()
    {
        $classes = Classes::whereHas('site', function ($query) {
            $query->where('country', '=', 'Philippines');
        })
            ->with('site', 'program', 'dateRange', 'createdByUser', 'updatedByUser')
            ->get();

        return response()->json([
            'classes' => $classes,
        ]);
    }

    //hiring summary
    public function dashboardClassesExport(Request $request)
    {
        $siteId = $request->input('site_id');
        $programId = $request->input('program_id');

        $programs = Program::with('site')
            ->when(!empty($siteId), function ($query) use ($siteId) {
                $query->whereIn('site_id', $siteId);
            })
            ->when(!empty($programId), function ($query) use ($programId) {
                $query->whereIn('program_id', $programId);
            })
            ->get();

        $dateRanges = DateRange::all();

        $groupedClasses = [];
        $grandTotalByWeek = [];
        $grandTotalByMonth = [];
        $grandTotalByProgram = [];

        foreach ($programs as $program) {
            $siteName = $program->site->name;
            $programName = $program->name;

            if (!isset($grandTotalByProgram[$siteName])) {
                $grandTotalByProgram[$siteName] = [];
            }
            $grandTotalByProgram[$siteName][$programName] = 0;

            foreach ($dateRanges as $dateRange) {
                $programId = $program->id;
                $week = $dateRange->id;
                $month = $dateRange->month;

                $classes = Classes::where('site_id', $program->site_id)
                    ->where('program_id', $programId)
                    ->where('date_range_id', $dateRange->id)
                    ->where('status', 'Active')
                    ->get();

                $totalTarget = $classes->sum('total_target');

                if (!isset($grandTotalByWeek[$week])) {
                    $grandTotalByWeek[$week] = 0;
                }
                if (!isset($grandTotalByMonth[$month])) {
                    $grandTotalByMonth[$month] = 0;
                }

                $grandTotalByWeek[$week] += $totalTarget;
                $grandTotalByMonth[$month] += $totalTarget;

                $grandTotalByProgram[$siteName][$programName] += $totalTarget;

                if (!isset($groupedClasses[$siteName][$programName][$week])) {
                    $groupedClasses[$siteName][$programName][$week] = [
                        'total_target' => 0,
                    ];
                }
                if (!isset($groupedClasses[$siteName][$programName][$month])) {
                    $groupedClasses[$siteName][$programName][$month] = [
                        'total_target' => 0,
                    ];
                }

                $groupedClasses[$siteName][$programName][$week]['total_target'] += $totalTarget;
            }
        }

        $mappedGroupedClasses = [];

        foreach ($groupedClasses as $siteName => $siteData) {
            foreach ($siteData as $programName => $programData) {
                $weeklyData = [
                    '1' => 0,
                    '2' => 0,
                    '3' => 0,
                    '4' => 0,
                    '5' => 0,
                    '6' => 0,
                    '7' => 0,
                    '8' => 0,
                    '9' => 0,
                    '10' => 0,
                    '11' => 0,
                    '12' => 0,
                    '13' => 0,
                    '14' => 0,
                    '15' => 0,
                    '16' => 0,
                    '17' => 0,
                    '18' => 0,
                    '19' => 0,
                    '20' => 0,
                    '21' => 0,
                    '22' => 0,
                    '23' => 0,
                    '24' => 0,
                    '25' => 0,
                    '26' => 0,
                    '27' => 0,
                    '28' => 0,
                    '29' => 0,
                    '30' => 0,
                    '31' => 0,
                    '32' => 0,
                    '33' => 0,
                    '34' => 0,
                    '35' => 0,
                    '36' => 0,
                    '37' => 0,
                    '38' => 0,
                    '39' => 0,
                    '40' => 0,
                    '41' => 0,
                    '42' => 0,
                    '43' => 0,
                    '44' => 0,
                    '45' => 0,
                    '46' => 0,
                    '47' => 0,
                    '48' => 0,
                    '49' => 0,
                    '50' => 0,
                    '51' => 0,
                    '52' => 0,
                ];

                foreach ($programData as $week => $weekData) {
                    $weeklyData[$week] = isset($weekData['total_target']) ? $weekData['total_target'] : 0;
                }
                $grandTotal = $grandTotalByProgram[$siteName][$programName];
                if ($grandTotal != 0) {
                    $mappedGroupedClasses[] = [
                        'Site' => $siteName,
                        'Program' => $programName,
                        'Week1' => $weeklyData['1'] != 0 ? $weeklyData['1'] : 0,
                        'Week2' => $weeklyData['2'] != 0 ? $weeklyData['2'] : 0,
                        'Week3' => $weeklyData['3'] != 0 ? $weeklyData['3'] : 0,
                        'Week4' => $weeklyData['4'] != 0 ? $weeklyData['4'] : 0,
                        'Jan' => collect($weeklyData)->only([1, 2, 3, 4])->sum(),
                        'Week5' => $weeklyData['5'] != 0 ? $weeklyData['5'] : 0,
                        'Week6' => $weeklyData['6'] != 0 ? $weeklyData['6'] : 0,
                        'Week7' => $weeklyData['7'] != 0 ? $weeklyData['7'] : 0,
                        'Week8' => $weeklyData['8'] != 0 ? $weeklyData['8'] : 0,
                        'Feb' => collect($weeklyData)->only([5, 6, 7, 8])->sum(),
                        'Week9' => $weeklyData['9'] != 0 ? $weeklyData['9'] : 0,
                        'Week10' => $weeklyData['10'] != 0 ? $weeklyData['10'] : 0,
                        'Week11' => $weeklyData['11'] != 0 ? $weeklyData['11'] : 0,
                        'Week12' => $weeklyData['12'] != 0 ? $weeklyData['12'] : 0,
                        'Mar' => collect($weeklyData)->only([9, 10, 11, 12])->sum(),
                        'Week13' => $weeklyData['13'] != 0 ? $weeklyData['13'] : 0,
                        'Week14' => $weeklyData['14'] != 0 ? $weeklyData['14'] : 0,
                        'Week15' => $weeklyData['15'] != 0 ? $weeklyData['15'] : 0,
                        'Week16' => $weeklyData['16'] != 0 ? $weeklyData['16'] : 0,
                        'Week17' => $weeklyData['17'] != 0 ? $weeklyData['17'] : 0,
                        'Apr' => collect($weeklyData)->only([13, 14, 15, 16, 17])->sum(),
                        'Week18' => $weeklyData['18'] != 0 ? $weeklyData['18'] : 0,
                        'Week19' => $weeklyData['19'] != 0 ? $weeklyData['19'] : 0,
                        'Week20' => $weeklyData['21'] != 0 ? $weeklyData['21'] : 0,
                        'Week21' => $weeklyData['21'] != 0 ? $weeklyData['21'] : 0,
                        'May' => collect($weeklyData)->only([18, 19, 20, 21])->sum(),
                        'Week22' => $weeklyData['21'] != 0 ? $weeklyData['21'] : 0,
                        'Week23' => $weeklyData['23'] != 0 ? $weeklyData['23'] : 0,
                        'Week24' => $weeklyData['24'] != 0 ? $weeklyData['24'] : 0,
                        'Week25' => $weeklyData['25'] != 0 ? $weeklyData['25'] : 0,
                        'Jun' => collect($weeklyData)->only([22, 23, 24, 25])->sum(),
                        'Week26' => $weeklyData['26'] != 0 ? $weeklyData['26'] : 0,
                        'Week27' => $weeklyData['27'] != 0 ? $weeklyData['27'] : 0,
                        'Week28' => $weeklyData['28'] != 0 ? $weeklyData['28'] : 0,
                        'Week29' => $weeklyData['29'] != 0 ? $weeklyData['29'] : 0,
                        'Week30' => $weeklyData['30'] != 0 ? $weeklyData['30'] : 0,
                        'Jul' => collect($weeklyData)->only([26, 27, 28, 29, 30])->sum(),
                        'Week31' => $weeklyData['31'] != 0 ? $weeklyData['31'] : 0,
                        'Week32' => $weeklyData['32'] != 0 ? $weeklyData['32'] : 0,
                        'Week33' => $weeklyData['33'] != 0 ? $weeklyData['33'] : 0,
                        'Week34' => $weeklyData['34'] != 0 ? $weeklyData['34'] : 0,
                        'Aug' => collect($weeklyData)->only([31, 32, 33, 34])->sum(),
                        'Week35' => $weeklyData['35'] != 0 ? $weeklyData['35'] : 0,
                        'Week36' => $weeklyData['36'] != 0 ? $weeklyData['36'] : 0,
                        'Week37' => $weeklyData['37'] != 0 ? $weeklyData['37'] : 0,
                        'Week38' => $weeklyData['38'] != 0 ? $weeklyData['38'] : 0,
                        'Week39' => $weeklyData['39'] != 0 ? $weeklyData['39'] : 0,
                        'Sep' => collect($weeklyData)->only([35, 36, 37, 38, 39])->sum(),
                        'Week40' => $weeklyData['40'] != 0 ? $weeklyData['40'] : 0,
                        'Week41' => $weeklyData['41'] != 0 ? $weeklyData['41'] : 0,
                        'Week42' => $weeklyData['42'] != 0 ? $weeklyData['42'] : 0,
                        'Week43' => $weeklyData['43'] != 0 ? $weeklyData['43'] : 0,
                        'Oct' => collect($weeklyData)->only([40, 41, 42, 43])->sum(),
                        'Week44' => $weeklyData['44'] != 0 ? $weeklyData['44'] : 0,
                        'Week45' => $weeklyData['45'] != 0 ? $weeklyData['45'] : 0,
                        'Week46' => $weeklyData['46'] != 0 ? $weeklyData['46'] : 0,
                        'Week47' => $weeklyData['47'] != 0 ? $weeklyData['47'] : 0,
                        'Nov' => collect($weeklyData)->only([44, 45, 46, 47])->sum(),
                        'Week48' => $weeklyData['48'] != 0 ? $weeklyData['48'] : 0,
                        'Week49' => $weeklyData['49'] != 0 ? $weeklyData['49'] : 0,
                        'Week50' => $weeklyData['50'] != 0 ? $weeklyData['50'] : 0,
                        'Week51' => $weeklyData['51'] != 0 ? $weeklyData['51'] : 0,
                        'Week52' => $weeklyData['52'] != 0 ? $weeklyData['52'] : 0,
                        'Dec' => collect($weeklyData)->only([48, 49, 50, 51, 52])->sum(),

                        'GrandTotalByProgram' => $grandTotal,
                    ];
                }
            }
        }

        $grandTotalForAllPrograms = array_sum(array_map('array_sum', $grandTotalByProgram));

        $mappedGroupedClasses[] = [
            'Site' => 'Grand Total',
            'Program' => '',
            'Week1' => $grandTotalByWeek['1'],
            'Week2' => $grandTotalByWeek['2'],
            'Week3' => $grandTotalByWeek['3'],
            'Week4' => $grandTotalByWeek['4'],
            'Jan' => collect($grandTotalByWeek)->only([1, 2, 3, 4])->sum(),
            'Week5' => $grandTotalByWeek['5'],
            'Week6' => $grandTotalByWeek['6'],
            'Week7' => $grandTotalByWeek['7'],
            'Week8' => $grandTotalByWeek['8'],
            'Feb' => collect($grandTotalByWeek)->only([5, 6, 7, 8])->sum(),
            'Week9' => $grandTotalByWeek['9'],
            'Week10' => $grandTotalByWeek['10'],
            'Week11' => $grandTotalByWeek['11'],
            'Week12' => $grandTotalByWeek['12'],
            'Mar' => collect($grandTotalByWeek)->only([9, 10, 11, 12])->sum(),
            'Week13' => $grandTotalByWeek['13'],
            'Week14' => $grandTotalByWeek['14'],
            'Week15' => $grandTotalByWeek['15'],
            'Week16' => $grandTotalByWeek['16'],
            'Week17' => $grandTotalByWeek['17'],
            'Apr' => collect($grandTotalByWeek)->only([13, 14, 15, 16, 17])->sum(),
            'Week18' => $grandTotalByWeek['18'],
            'Week19' => $grandTotalByWeek['19'],
            'Week20' => $grandTotalByWeek['21'],
            'Week21' => $grandTotalByWeek['21'],
            'May' => collect($grandTotalByWeek)->only([18, 19, 20, 21])->sum(),
            'Week22' => $grandTotalByWeek['21'],
            'Week23' => $grandTotalByWeek['23'],
            'Week24' => $grandTotalByWeek['24'],
            'Week25' => $grandTotalByWeek['25'],
            'Jun' => collect($grandTotalByWeek)->only([22, 23, 24, 25])->sum(),
            'Week26' => $grandTotalByWeek['26'],
            'Week27' => $grandTotalByWeek['27'],
            'Week28' => $grandTotalByWeek['28'],
            'Week29' => $grandTotalByWeek['29'],
            'Week30' => $grandTotalByWeek['30'],
            'Jul' => collect($grandTotalByWeek)->only([26, 27, 28, 29, 30])->sum(),
            'Week31' => $grandTotalByWeek['31'],
            'Week32' => $grandTotalByWeek['32'],
            'Week33' => $grandTotalByWeek['33'],
            'Week34' => $grandTotalByWeek['34'],
            'Aug' => collect($grandTotalByWeek)->only([31, 32, 33, 34])->sum(),
            'Week35' => $grandTotalByWeek['35'],
            'Week36' => $grandTotalByWeek['36'],
            'Week37' => $grandTotalByWeek['37'],
            'Week38' => $grandTotalByWeek['38'],
            'Week39' => $grandTotalByWeek['39'],
            'Sep' => collect($grandTotalByWeek)->only([35, 36, 37, 38, 39])->sum(),
            'Week40' => $grandTotalByWeek['40'],
            'Week41' => $grandTotalByWeek['41'],
            'Week42' => $grandTotalByWeek['42'],
            'Week43' => $grandTotalByWeek['43'],
            'Oct' => collect($grandTotalByWeek)->only([40, 41, 42, 43])->sum(),
            'Week44' => $grandTotalByWeek['44'],
            'Week45' => $grandTotalByWeek['45'],
            'Week46' => $grandTotalByWeek['46'],
            'Week47' => $grandTotalByWeek['47'],
            'Nov' => collect($grandTotalByWeek)->only([44, 45, 46, 47])->sum(),
            'Week48' => $grandTotalByWeek['48'],
            'Week49' => $grandTotalByWeek['49'],
            'Week50' => $grandTotalByWeek['50'],
            'Week51' => $grandTotalByWeek['51'],
            'Week52' => $grandTotalByWeek['52'],
            'Dec' => collect($grandTotalByWeek)->only([48, 49, 50, 51, 52])->sum(),
            'GrandTotalByProgram' => $grandTotalForAllPrograms,
        ];

        $response = [
            'classes' => $mappedGroupedClasses,
        ];

        return Excel::download(new DashboardClassesExport($response), 'filtered_data.xlsx');
    }

    public function dashboardClassesExport3(Request $request)
    {
        $siteId = $request->input('site_id');
        $programId = $request->input('program_id');

        $programs = Program::with('site')
            ->when(!empty($siteId), function ($query) use ($siteId) {
                $query->whereIn('site_id', $siteId);
            })
            ->when(!empty($programId), function ($query) use ($programId) {
                $query->whereIn('program_id', $programId);
            })
            ->get();

        $dateRanges = DateRange::all();

        $groupedClasses = [];
        $grandTotalByWeek = [];
        $grandTotalByMonth = [];
        $grandTotalByProgram = [];

        foreach ($programs as $program) {
            $siteName = $program->site->name;
            $programName = $program->name;

            if (!isset($grandTotalByProgram[$siteName])) {
                $grandTotalByProgram[$siteName] = [];
            }
            $grandTotalByProgram[$siteName][$programName] = 0;

            foreach ($dateRanges as $dateRange) {
                $programId = $program->id;
                $week = $dateRange->id;
                $month = $dateRange->month;

                $classes = Classes::where('site_id', $program->site_id)
                    ->where('program_id', $programId)
                    ->where('date_range_id', $dateRange->id)
                    ->where('status', 'Cancelled')
                    ->get();

                $totalTarget = $classes->sum('total_target');

                if (!isset($grandTotalByWeek[$week])) {
                    $grandTotalByWeek[$week] = 0;
                }
                if (!isset($grandTotalByMonth[$month])) {
                    $grandTotalByMonth[$month] = 0;
                }

                $grandTotalByWeek[$week] += $totalTarget;
                $grandTotalByMonth[$month] += $totalTarget;

                $grandTotalByProgram[$siteName][$programName] += $totalTarget;

                if (!isset($groupedClasses[$siteName][$programName][$week])) {
                    $groupedClasses[$siteName][$programName][$week] = [
                        'total_target' => 0,
                    ];
                }
                if (!isset($groupedClasses[$siteName][$programName][$month])) {
                    $groupedClasses[$siteName][$programName][$month] = [
                        'total_target' => 0,
                    ];
                }

                $groupedClasses[$siteName][$programName][$week]['total_target'] += $totalTarget;
            }
        }

        $mappedGroupedClasses = [];

        foreach ($groupedClasses as $siteName => $siteData) {
            foreach ($siteData as $programName => $programData) {
                $weeklyData = [
                    '1' => 0,
                    '2' => 0,
                    '3' => 0,
                    '4' => 0,
                    '5' => 0,
                    '6' => 0,
                    '7' => 0,
                    '8' => 0,
                    '9' => 0,
                    '10' => 0,
                    '11' => 0,
                    '12' => 0,
                    '13' => 0,
                    '14' => 0,
                    '15' => 0,
                    '16' => 0,
                    '17' => 0,
                    '18' => 0,
                    '19' => 0,
                    '20' => 0,
                    '21' => 0,
                    '22' => 0,
                    '23' => 0,
                    '24' => 0,
                    '25' => 0,
                    '26' => 0,
                    '27' => 0,
                    '28' => 0,
                    '29' => 0,
                    '30' => 0,
                    '31' => 0,
                    '32' => 0,
                    '33' => 0,
                    '34' => 0,
                    '35' => 0,
                    '36' => 0,
                    '37' => 0,
                    '38' => 0,
                    '39' => 0,
                    '40' => 0,
                    '41' => 0,
                    '42' => 0,
                    '43' => 0,
                    '44' => 0,
                    '45' => 0,
                    '46' => 0,
                    '47' => 0,
                    '48' => 0,
                    '49' => 0,
                    '50' => 0,
                    '51' => 0,
                    '52' => 0,
                ];

                foreach ($programData as $week => $weekData) {
                    $weeklyData[$week] = isset($weekData['total_target']) ? $weekData['total_target'] : 0;
                }
                $grandTotal = $grandTotalByProgram[$siteName][$programName];

                $mappedGroupedClasses[] = [
                    'Site' => $siteName,
                    'Program' => $programName,
                    'Week1' => $weeklyData['1'] != 0 ? $weeklyData['1'] : 0,
                    'Week2' => $weeklyData['2'] != 0 ? $weeklyData['2'] : 0,
                    'Week3' => $weeklyData['3'] != 0 ? $weeklyData['3'] : 0,
                    'Week4' => $weeklyData['4'] != 0 ? $weeklyData['4'] : 0,
                    'Jan' => collect($weeklyData)->only([1, 2, 3, 4])->sum(),
                    'Week5' => $weeklyData['5'] != 0 ? $weeklyData['5'] : 0,
                    'Week6' => $weeklyData['6'] != 0 ? $weeklyData['6'] : 0,
                    'Week7' => $weeklyData['7'] != 0 ? $weeklyData['7'] : 0,
                    'Week8' => $weeklyData['8'] != 0 ? $weeklyData['8'] : 0,
                    'Feb' => collect($weeklyData)->only([5, 6, 7, 8])->sum(),
                    'Week9' => $weeklyData['9'] != 0 ? $weeklyData['9'] : 0,
                    'Week10' => $weeklyData['10'] != 0 ? $weeklyData['10'] : 0,
                    'Week11' => $weeklyData['11'] != 0 ? $weeklyData['11'] : 0,
                    'Week12' => $weeklyData['12'] != 0 ? $weeklyData['12'] : 0,
                    'Mar' => collect($weeklyData)->only([9, 10, 11, 12])->sum(),
                    'Week13' => $weeklyData['13'] != 0 ? $weeklyData['13'] : 0,
                    'Week14' => $weeklyData['14'] != 0 ? $weeklyData['14'] : 0,
                    'Week15' => $weeklyData['15'] != 0 ? $weeklyData['15'] : 0,
                    'Week16' => $weeklyData['16'] != 0 ? $weeklyData['16'] : 0,
                    'Week17' => $weeklyData['17'] != 0 ? $weeklyData['17'] : 0,
                    'Apr' => collect($weeklyData)->only([13, 14, 15, 16, 17])->sum(),
                    'Week18' => $weeklyData['18'] != 0 ? $weeklyData['18'] : 0,
                    'Week19' => $weeklyData['19'] != 0 ? $weeklyData['19'] : 0,
                    'Week20' => $weeklyData['21'] != 0 ? $weeklyData['21'] : 0,
                    'Week21' => $weeklyData['21'] != 0 ? $weeklyData['21'] : 0,
                    'May' => collect($weeklyData)->only([18, 19, 20, 21])->sum(),
                    'Week22' => $weeklyData['21'] != 0 ? $weeklyData['21'] : 0,
                    'Week23' => $weeklyData['23'] != 0 ? $weeklyData['23'] : 0,
                    'Week24' => $weeklyData['24'] != 0 ? $weeklyData['24'] : 0,
                    'Week25' => $weeklyData['25'] != 0 ? $weeklyData['25'] : 0,
                    'Jun' => collect($weeklyData)->only([22, 23, 24, 25])->sum(),
                    'Week26' => $weeklyData['26'] != 0 ? $weeklyData['26'] : 0,
                    'Week27' => $weeklyData['27'] != 0 ? $weeklyData['27'] : 0,
                    'Week28' => $weeklyData['28'] != 0 ? $weeklyData['28'] : 0,
                    'Week29' => $weeklyData['29'] != 0 ? $weeklyData['29'] : 0,
                    'Week30' => $weeklyData['30'] != 0 ? $weeklyData['30'] : 0,
                    'Jul' => collect($weeklyData)->only([26, 27, 28, 29, 30])->sum(),
                    'Week31' => $weeklyData['31'] != 0 ? $weeklyData['31'] : 0,
                    'Week32' => $weeklyData['32'] != 0 ? $weeklyData['32'] : 0,
                    'Week33' => $weeklyData['33'] != 0 ? $weeklyData['33'] : 0,
                    'Week34' => $weeklyData['34'] != 0 ? $weeklyData['34'] : 0,
                    'Aug' => collect($weeklyData)->only([31, 32, 33, 34])->sum(),
                    'Week35' => $weeklyData['35'] != 0 ? $weeklyData['35'] : 0,
                    'Week36' => $weeklyData['36'] != 0 ? $weeklyData['36'] : 0,
                    'Week37' => $weeklyData['37'] != 0 ? $weeklyData['37'] : 0,
                    'Week38' => $weeklyData['38'] != 0 ? $weeklyData['38'] : 0,
                    'Week39' => $weeklyData['39'] != 0 ? $weeklyData['39'] : 0,
                    'Sep' => collect($weeklyData)->only([35, 36, 37, 38, 39])->sum(),
                    'Week40' => $weeklyData['40'] != 0 ? $weeklyData['40'] : 0,
                    'Week41' => $weeklyData['41'] != 0 ? $weeklyData['41'] : 0,
                    'Week42' => $weeklyData['42'] != 0 ? $weeklyData['42'] : 0,
                    'Week43' => $weeklyData['43'] != 0 ? $weeklyData['43'] : 0,
                    'Oct' => collect($weeklyData)->only([40, 41, 42, 43])->sum(),
                    'Week44' => $weeklyData['44'] != 0 ? $weeklyData['44'] : 0,
                    'Week45' => $weeklyData['45'] != 0 ? $weeklyData['45'] : 0,
                    'Week46' => $weeklyData['46'] != 0 ? $weeklyData['46'] : 0,
                    'Week47' => $weeklyData['47'] != 0 ? $weeklyData['47'] : 0,
                    'Nov' => collect($weeklyData)->only([44, 45, 46, 47])->sum(),
                    'Week48' => $weeklyData['48'] != 0 ? $weeklyData['48'] : 0,
                    'Week49' => $weeklyData['49'] != 0 ? $weeklyData['49'] : 0,
                    'Week50' => $weeklyData['50'] != 0 ? $weeklyData['50'] : 0,
                    'Week51' => $weeklyData['51'] != 0 ? $weeklyData['51'] : 0,
                    'Week52' => $weeklyData['52'] != 0 ? $weeklyData['52'] : 0,
                    'Dec' => collect($weeklyData)->only([48, 49, 50, 51, 52])->sum(),

                    'GrandTotalByProgram' => $grandTotal,
                ];
            }
        }

        $grandTotalForAllPrograms = array_sum(array_map('array_sum', $grandTotalByProgram));

        $mappedGroupedClasses[] = [
            'Site' => 'Grand Total',
            'Program' => '',
            'Week1' => $grandTotalByWeek['1'],
            'Week2' => $grandTotalByWeek['2'],
            'Week3' => $grandTotalByWeek['3'],
            'Week4' => $grandTotalByWeek['4'],
            'Jan' => collect($grandTotalByWeek)->only([1, 2, 3, 4])->sum(),
            'Week5' => $grandTotalByWeek['5'],
            'Week6' => $grandTotalByWeek['6'],
            'Week7' => $grandTotalByWeek['7'],
            'Week8' => $grandTotalByWeek['8'],
            'Feb' => collect($grandTotalByWeek)->only([5, 6, 7, 8])->sum(),
            'Week9' => $grandTotalByWeek['9'],
            'Week10' => $grandTotalByWeek['10'],
            'Week11' => $grandTotalByWeek['11'],
            'Week12' => $grandTotalByWeek['12'],
            'Mar' => collect($grandTotalByWeek)->only([9, 10, 11, 12])->sum(),
            'Week13' => $grandTotalByWeek['13'],
            'Week14' => $grandTotalByWeek['14'],
            'Week15' => $grandTotalByWeek['15'],
            'Week16' => $grandTotalByWeek['16'],
            'Week17' => $grandTotalByWeek['17'],
            'Apr' => collect($grandTotalByWeek)->only([13, 14, 15, 16, 17])->sum(),
            'Week18' => $grandTotalByWeek['18'],
            'Week19' => $grandTotalByWeek['19'],
            'Week20' => $grandTotalByWeek['21'],
            'Week21' => $grandTotalByWeek['21'],
            'May' => collect($grandTotalByWeek)->only([18, 19, 20, 21])->sum(),
            'Week22' => $grandTotalByWeek['21'],
            'Week23' => $grandTotalByWeek['23'],
            'Week24' => $grandTotalByWeek['24'],
            'Week25' => $grandTotalByWeek['25'],
            'Jun' => collect($grandTotalByWeek)->only([22, 23, 24, 25])->sum(),
            'Week26' => $grandTotalByWeek['26'],
            'Week27' => $grandTotalByWeek['27'],
            'Week28' => $grandTotalByWeek['28'],
            'Week29' => $grandTotalByWeek['29'],
            'Week30' => $grandTotalByWeek['30'],
            'Jul' => collect($grandTotalByWeek)->only([26, 27, 28, 29, 30])->sum(),
            'Week31' => $grandTotalByWeek['31'],
            'Week32' => $grandTotalByWeek['32'],
            'Week33' => $grandTotalByWeek['33'],
            'Week34' => $grandTotalByWeek['34'],
            'Aug' => collect($grandTotalByWeek)->only([31, 32, 33, 34])->sum(),
            'Week35' => $grandTotalByWeek['35'],
            'Week36' => $grandTotalByWeek['36'],
            'Week37' => $grandTotalByWeek['37'],
            'Week38' => $grandTotalByWeek['38'],
            'Week39' => $grandTotalByWeek['39'],
            'Sep' => collect($grandTotalByWeek)->only([35, 36, 37, 38, 39])->sum(),
            'Week40' => $grandTotalByWeek['40'],
            'Week41' => $grandTotalByWeek['41'],
            'Week42' => $grandTotalByWeek['42'],
            'Week43' => $grandTotalByWeek['43'],
            'Oct' => collect($grandTotalByWeek)->only([40, 41, 42, 43])->sum(),
            'Week44' => $grandTotalByWeek['44'],
            'Week45' => $grandTotalByWeek['45'],
            'Week46' => $grandTotalByWeek['46'],
            'Week47' => $grandTotalByWeek['47'],
            'Nov' => collect($grandTotalByWeek)->only([44, 45, 46, 47])->sum(),
            'Week48' => $grandTotalByWeek['48'],
            'Week49' => $grandTotalByWeek['49'],
            'Week50' => $grandTotalByWeek['50'],
            'Week51' => $grandTotalByWeek['51'],
            'Week52' => $grandTotalByWeek['52'],
            'Dec' => collect($grandTotalByWeek)->only([48, 49, 50, 51, 52])->sum(),
            'GrandTotalByProgram' => $grandTotalForAllPrograms,
        ];

        $response = [
            'classes' => $mappedGroupedClasses,
        ];

        return Excel::download(new DashboardClassesExport($response), 'filtered_data.xlsx');
    }

    public function dashboardClassesExport4(Request $request)
    {
        $siteId = $request->input('site_id');
        $programId = $request->input('program_id');

        $programs = Program::with('site')
            ->when(!empty($siteId), function ($query) use ($siteId) {
                $query->whereIn('site_id', $siteId);
            })
            ->when(!empty($programId), function ($query) use ($programId) {
                $query->whereIn('program_id', $programId);
            })
            ->get();

        $dateRanges = DateRange::all();

        $groupedClasses = [];
        $grandTotalByWeek = [];
        $grandTotalByMonth = [];
        $grandTotalByProgram = [];

        foreach ($programs as $program) {
            $siteName = $program->site->name;
            $programName = $program->name;

            if (!isset($grandTotalByProgram[$siteName])) {
                $grandTotalByProgram[$siteName] = [];
            }
            $grandTotalByProgram[$siteName][$programName] = 0;

            foreach ($dateRanges as $dateRange) {
                $programId = $program->id;
                $week = $dateRange->id;
                $month = $dateRange->month;

                $classes = Classes::where('site_id', $program->site_id)
                    ->where('program_id', $programId)
                    ->where('date_range_id', $dateRange->id)
                    ->where('status', 'Moved')
                    ->get();

                $totalTarget = $classes->sum('total_target');

                if (!isset($grandTotalByWeek[$week])) {
                    $grandTotalByWeek[$week] = 0;
                }
                if (!isset($grandTotalByMonth[$month])) {
                    $grandTotalByMonth[$month] = 0;
                }

                $grandTotalByWeek[$week] += $totalTarget;
                $grandTotalByMonth[$month] += $totalTarget;

                $grandTotalByProgram[$siteName][$programName] += $totalTarget;

                if (!isset($groupedClasses[$siteName][$programName][$week])) {
                    $groupedClasses[$siteName][$programName][$week] = [
                        'total_target' => 0,
                    ];
                }
                if (!isset($groupedClasses[$siteName][$programName][$month])) {
                    $groupedClasses[$siteName][$programName][$month] = [
                        'total_target' => 0,
                    ];
                }

                $groupedClasses[$siteName][$programName][$week]['total_target'] += $totalTarget;
            }
        }

        $mappedGroupedClasses = [];

        foreach ($groupedClasses as $siteName => $siteData) {
            foreach ($siteData as $programName => $programData) {
                $weeklyData = [
                    '1' => 0,
                    '2' => 0,
                    '3' => 0,
                    '4' => 0,
                    '5' => 0,
                    '6' => 0,
                    '7' => 0,
                    '8' => 0,
                    '9' => 0,
                    '10' => 0,
                    '11' => 0,
                    '12' => 0,
                    '13' => 0,
                    '14' => 0,
                    '15' => 0,
                    '16' => 0,
                    '17' => 0,
                    '18' => 0,
                    '19' => 0,
                    '20' => 0,
                    '21' => 0,
                    '22' => 0,
                    '23' => 0,
                    '24' => 0,
                    '25' => 0,
                    '26' => 0,
                    '27' => 0,
                    '28' => 0,
                    '29' => 0,
                    '30' => 0,
                    '31' => 0,
                    '32' => 0,
                    '33' => 0,
                    '34' => 0,
                    '35' => 0,
                    '36' => 0,
                    '37' => 0,
                    '38' => 0,
                    '39' => 0,
                    '40' => 0,
                    '41' => 0,
                    '42' => 0,
                    '43' => 0,
                    '44' => 0,
                    '45' => 0,
                    '46' => 0,
                    '47' => 0,
                    '48' => 0,
                    '49' => 0,
                    '50' => 0,
                    '51' => 0,
                    '52' => 0,
                ];

                foreach ($programData as $week => $weekData) {
                    $weeklyData[$week] = isset($weekData['total_target']) ? $weekData['total_target'] : 0;
                }
                $grandTotal = $grandTotalByProgram[$siteName][$programName];

                $mappedGroupedClasses[] = [
                    'Site' => $siteName,
                    'Program' => $programName,
                    'Week1' => $weeklyData['1'] != 0 ? $weeklyData['1'] : 0,
                    'Week2' => $weeklyData['2'] != 0 ? $weeklyData['2'] : 0,
                    'Week3' => $weeklyData['3'] != 0 ? $weeklyData['3'] : 0,
                    'Week4' => $weeklyData['4'] != 0 ? $weeklyData['4'] : 0,
                    'Jan' => collect($weeklyData)->only([1, 2, 3, 4])->sum(),
                    'Week5' => $weeklyData['5'] != 0 ? $weeklyData['5'] : 0,
                    'Week6' => $weeklyData['6'] != 0 ? $weeklyData['6'] : 0,
                    'Week7' => $weeklyData['7'] != 0 ? $weeklyData['7'] : 0,
                    'Week8' => $weeklyData['8'] != 0 ? $weeklyData['8'] : 0,
                    'Feb' => collect($weeklyData)->only([5, 6, 7, 8])->sum(),
                    'Week9' => $weeklyData['9'] != 0 ? $weeklyData['9'] : 0,
                    'Week10' => $weeklyData['10'] != 0 ? $weeklyData['10'] : 0,
                    'Week11' => $weeklyData['11'] != 0 ? $weeklyData['11'] : 0,
                    'Week12' => $weeklyData['12'] != 0 ? $weeklyData['12'] : 0,
                    'Mar' => collect($weeklyData)->only([9, 10, 11, 12])->sum(),
                    'Week13' => $weeklyData['13'] != 0 ? $weeklyData['13'] : 0,
                    'Week14' => $weeklyData['14'] != 0 ? $weeklyData['14'] : 0,
                    'Week15' => $weeklyData['15'] != 0 ? $weeklyData['15'] : 0,
                    'Week16' => $weeklyData['16'] != 0 ? $weeklyData['16'] : 0,
                    'Week17' => $weeklyData['17'] != 0 ? $weeklyData['17'] : 0,
                    'Apr' => collect($weeklyData)->only([13, 14, 15, 16, 17])->sum(),
                    'Week18' => $weeklyData['18'] != 0 ? $weeklyData['18'] : 0,
                    'Week19' => $weeklyData['19'] != 0 ? $weeklyData['19'] : 0,
                    'Week20' => $weeklyData['21'] != 0 ? $weeklyData['21'] : 0,
                    'Week21' => $weeklyData['21'] != 0 ? $weeklyData['21'] : 0,
                    'May' => collect($weeklyData)->only([18, 19, 20, 21])->sum(),
                    'Week22' => $weeklyData['21'] != 0 ? $weeklyData['21'] : 0,
                    'Week23' => $weeklyData['23'] != 0 ? $weeklyData['23'] : 0,
                    'Week24' => $weeklyData['24'] != 0 ? $weeklyData['24'] : 0,
                    'Week25' => $weeklyData['25'] != 0 ? $weeklyData['25'] : 0,
                    'Jun' => collect($weeklyData)->only([22, 23, 24, 25])->sum(),
                    'Week26' => $weeklyData['26'] != 0 ? $weeklyData['26'] : 0,
                    'Week27' => $weeklyData['27'] != 0 ? $weeklyData['27'] : 0,
                    'Week28' => $weeklyData['28'] != 0 ? $weeklyData['28'] : 0,
                    'Week29' => $weeklyData['29'] != 0 ? $weeklyData['29'] : 0,
                    'Week30' => $weeklyData['30'] != 0 ? $weeklyData['30'] : 0,
                    'Jul' => collect($weeklyData)->only([26, 27, 28, 29, 30])->sum(),
                    'Week31' => $weeklyData['31'] != 0 ? $weeklyData['31'] : 0,
                    'Week32' => $weeklyData['32'] != 0 ? $weeklyData['32'] : 0,
                    'Week33' => $weeklyData['33'] != 0 ? $weeklyData['33'] : 0,
                    'Week34' => $weeklyData['34'] != 0 ? $weeklyData['34'] : 0,
                    'Aug' => collect($weeklyData)->only([31, 32, 33, 34])->sum(),
                    'Week35' => $weeklyData['35'] != 0 ? $weeklyData['35'] : 0,
                    'Week36' => $weeklyData['36'] != 0 ? $weeklyData['36'] : 0,
                    'Week37' => $weeklyData['37'] != 0 ? $weeklyData['37'] : 0,
                    'Week38' => $weeklyData['38'] != 0 ? $weeklyData['38'] : 0,
                    'Week39' => $weeklyData['39'] != 0 ? $weeklyData['39'] : 0,
                    'Sep' => collect($weeklyData)->only([35, 36, 37, 38, 39])->sum(),
                    'Week40' => $weeklyData['40'] != 0 ? $weeklyData['40'] : 0,
                    'Week41' => $weeklyData['41'] != 0 ? $weeklyData['41'] : 0,
                    'Week42' => $weeklyData['42'] != 0 ? $weeklyData['42'] : 0,
                    'Week43' => $weeklyData['43'] != 0 ? $weeklyData['43'] : 0,
                    'Oct' => collect($weeklyData)->only([40, 41, 42, 43])->sum(),
                    'Week44' => $weeklyData['44'] != 0 ? $weeklyData['44'] : 0,
                    'Week45' => $weeklyData['45'] != 0 ? $weeklyData['45'] : 0,
                    'Week46' => $weeklyData['46'] != 0 ? $weeklyData['46'] : 0,
                    'Week47' => $weeklyData['47'] != 0 ? $weeklyData['47'] : 0,
                    'Nov' => collect($weeklyData)->only([44, 45, 46, 47])->sum(),
                    'Week48' => $weeklyData['48'] != 0 ? $weeklyData['48'] : 0,
                    'Week49' => $weeklyData['49'] != 0 ? $weeklyData['49'] : 0,
                    'Week50' => $weeklyData['50'] != 0 ? $weeklyData['50'] : 0,
                    'Week51' => $weeklyData['51'] != 0 ? $weeklyData['51'] : 0,
                    'Week52' => $weeklyData['52'] != 0 ? $weeklyData['52'] : 0,
                    'Dec' => collect($weeklyData)->only([48, 49, 50, 51, 52])->sum(),

                    'GrandTotalByProgram' => $grandTotal,
                ];
            }
        }

        $grandTotalForAllPrograms = array_sum(array_map('array_sum', $grandTotalByProgram));

        $mappedGroupedClasses[] = [
            'Site' => 'Grand Total',
            'Program' => '',
            'Week1' => $grandTotalByWeek['1'],
            'Week2' => $grandTotalByWeek['2'],
            'Week3' => $grandTotalByWeek['3'],
            'Week4' => $grandTotalByWeek['4'],
            'Jan' => collect($grandTotalByWeek)->only([1, 2, 3, 4])->sum(),
            'Week5' => $grandTotalByWeek['5'],
            'Week6' => $grandTotalByWeek['6'],
            'Week7' => $grandTotalByWeek['7'],
            'Week8' => $grandTotalByWeek['8'],
            'Feb' => collect($grandTotalByWeek)->only([5, 6, 7, 8])->sum(),
            'Week9' => $grandTotalByWeek['9'],
            'Week10' => $grandTotalByWeek['10'],
            'Week11' => $grandTotalByWeek['11'],
            'Week12' => $grandTotalByWeek['12'],
            'Mar' => collect($grandTotalByWeek)->only([9, 10, 11, 12])->sum(),
            'Week13' => $grandTotalByWeek['13'],
            'Week14' => $grandTotalByWeek['14'],
            'Week15' => $grandTotalByWeek['15'],
            'Week16' => $grandTotalByWeek['16'],
            'Week17' => $grandTotalByWeek['17'],
            'Apr' => collect($grandTotalByWeek)->only([13, 14, 15, 16, 17])->sum(),
            'Week18' => $grandTotalByWeek['18'],
            'Week19' => $grandTotalByWeek['19'],
            'Week20' => $grandTotalByWeek['21'],
            'Week21' => $grandTotalByWeek['21'],
            'May' => collect($grandTotalByWeek)->only([18, 19, 20, 21])->sum(),
            'Week22' => $grandTotalByWeek['21'],
            'Week23' => $grandTotalByWeek['23'],
            'Week24' => $grandTotalByWeek['24'],
            'Week25' => $grandTotalByWeek['25'],
            'Jun' => collect($grandTotalByWeek)->only([22, 23, 24, 25])->sum(),
            'Week26' => $grandTotalByWeek['26'],
            'Week27' => $grandTotalByWeek['27'],
            'Week28' => $grandTotalByWeek['28'],
            'Week29' => $grandTotalByWeek['29'],
            'Week30' => $grandTotalByWeek['30'],
            'Jul' => collect($grandTotalByWeek)->only([26, 27, 28, 29, 30])->sum(),
            'Week31' => $grandTotalByWeek['31'],
            'Week32' => $grandTotalByWeek['32'],
            'Week33' => $grandTotalByWeek['33'],
            'Week34' => $grandTotalByWeek['34'],
            'Aug' => collect($grandTotalByWeek)->only([31, 32, 33, 34])->sum(),
            'Week35' => $grandTotalByWeek['35'],
            'Week36' => $grandTotalByWeek['36'],
            'Week37' => $grandTotalByWeek['37'],
            'Week38' => $grandTotalByWeek['38'],
            'Week39' => $grandTotalByWeek['39'],
            'Sep' => collect($grandTotalByWeek)->only([35, 36, 37, 38, 39])->sum(),
            'Week40' => $grandTotalByWeek['40'],
            'Week41' => $grandTotalByWeek['41'],
            'Week42' => $grandTotalByWeek['42'],
            'Week43' => $grandTotalByWeek['43'],
            'Oct' => collect($grandTotalByWeek)->only([40, 41, 42, 43])->sum(),
            'Week44' => $grandTotalByWeek['44'],
            'Week45' => $grandTotalByWeek['45'],
            'Week46' => $grandTotalByWeek['46'],
            'Week47' => $grandTotalByWeek['47'],
            'Nov' => collect($grandTotalByWeek)->only([44, 45, 46, 47])->sum(),
            'Week48' => $grandTotalByWeek['48'],
            'Week49' => $grandTotalByWeek['49'],
            'Week50' => $grandTotalByWeek['50'],
            'Week51' => $grandTotalByWeek['51'],
            'Week52' => $grandTotalByWeek['52'],
            'Dec' => collect($grandTotalByWeek)->only([48, 49, 50, 51, 52])->sum(),
            'GrandTotalByProgram' => $grandTotalForAllPrograms,
        ];

        $response = [
            'classes' => $mappedGroupedClasses,
        ];

        return Excel::download(new DashboardClassesExport($response), 'filtered_data.xlsx');
    }

    public function dashboardClasses(Request $request)
    {
        $siteId = $request->input('site_id');
        $programId = $request->input('program_id');

        $programs = Program::with('site')
            ->when(!empty($siteId), function ($query) use ($siteId) {
                $query->whereIn('site_id', $siteId);
            })
            ->when(!empty($programId), function ($query) use ($programId) {
                $query->whereIn('program_id', $programId);
            })
            ->get();

        $dateRanges = DateRange::all();

        $groupedClasses = [];
        $grandTotalByWeek = [];
        $grandTotalByMonth = [];
        $grandTotalByProgram = [];

        foreach ($programs as $program) {
            $siteName = $program->site->name;
            $programName = $program->name;

            if (!isset($grandTotalByProgram[$siteName])) {
                $grandTotalByProgram[$siteName] = [];
            }
            $grandTotalByProgram[$siteName][$programName] = 0;

            foreach ($dateRanges as $dateRange) {
                $programId = $program->id;
                $week = $dateRange->id;
                $month = $dateRange->month;

                $classes = Classes::where('site_id', $program->site_id)
                    ->where('program_id', $programId)
                    ->where('date_range_id', $dateRange->id)
                    ->where('status', 'Active')
                    ->get();

                $totalTarget = $classes->sum('total_target');

                if (!isset($grandTotalByWeek[$week])) {
                    $grandTotalByWeek[$week] = 0;
                }
                if (!isset($grandTotalByMonth[$month])) {
                    $grandTotalByMonth[$month] = 0;
                }

                $grandTotalByWeek[$week] += $totalTarget;
                $grandTotalByMonth[$month] += $totalTarget;

                $grandTotalByProgram[$siteName][$programName] += $totalTarget;

                if (!isset($groupedClasses[$siteName][$programName][$week])) {
                    $groupedClasses[$siteName][$programName][$week] = [
                        'total_target' => 0,
                    ];
                }
                if (!isset($groupedClasses[$siteName][$programName][$month])) {
                    $groupedClasses[$siteName][$programName][$month] = [
                        'total_target' => 0,
                    ];
                }

                $groupedClasses[$siteName][$programName][$week]['total_target'] += $totalTarget;
            }
        }

        $mappedGroupedClasses = [];

        foreach ($groupedClasses as $siteName => $siteData) {
            foreach ($siteData as $programName => $programData) {
                $weeklyData = [
                    '1' => 0,
                    '2' => 0,
                    '3' => 0,
                    '4' => 0,
                    '5' => 0,
                    '6' => 0,
                    '7' => 0,
                    '8' => 0,
                    '9' => 0,
                    '10' => 0,
                    '11' => 0,
                    '12' => 0,
                    '13' => 0,
                    '14' => 0,
                    '15' => 0,
                    '16' => 0,
                    '17' => 0,
                    '18' => 0,
                    '19' => 0,
                    '20' => 0,
                    '21' => 0,
                    '22' => 0,
                    '23' => 0,
                    '24' => 0,
                    '25' => 0,
                    '26' => 0,
                    '27' => 0,
                    '28' => 0,
                    '29' => 0,
                    '30' => 0,
                    '31' => 0,
                    '32' => 0,
                    '33' => 0,
                    '34' => 0,
                    '35' => 0,
                    '36' => 0,
                    '37' => 0,
                    '38' => 0,
                    '39' => 0,
                    '40' => 0,
                    '41' => 0,
                    '42' => 0,
                    '43' => 0,
                    '44' => 0,
                    '45' => 0,
                    '46' => 0,
                    '47' => 0,
                    '48' => 0,
                    '49' => 0,
                    '50' => 0,
                    '51' => 0,
                    '52' => 0,
                ];

                foreach ($programData as $week => $weekData) {
                    $weeklyData[$week] = isset($weekData['total_target']) ? $weekData['total_target'] : 0;
                }
                $grandTotal = $grandTotalByProgram[$siteName][$programName];
                /*  if ($grandTotal != 0) { */
                $mappedGroupedClasses[] = [
                    'Site' => $siteName,
                    'Program' => $programName,
                    'Week1' => $weeklyData['1'] != 0 ? $weeklyData['1'] : '',
                    'Week2' => $weeklyData['2'] != 0 ? $weeklyData['2'] : '',
                    'Week3' => $weeklyData['3'] != 0 ? $weeklyData['3'] : '',
                    'Week4' => $weeklyData['4'] != 0 ? $weeklyData['4'] : '',
                    'Jan' => collect($weeklyData)->only([1, 2, 3, 4])->sum(),
                    'Week5' => $weeklyData['5'] != 0 ? $weeklyData['5'] : '',
                    'Week6' => $weeklyData['6'] != 0 ? $weeklyData['6'] : '',
                    'Week7' => $weeklyData['7'] != 0 ? $weeklyData['7'] : '',
                    'Week8' => $weeklyData['8'] != 0 ? $weeklyData['8'] : '',
                    'Feb' => collect($weeklyData)->only([5, 6, 7, 8])->sum(),
                    'Week9' => $weeklyData['9'] != 0 ? $weeklyData['9'] : '',
                    'Week10' => $weeklyData['10'] != 0 ? $weeklyData['10'] : '',
                    'Week11' => $weeklyData['11'] != 0 ? $weeklyData['11'] : '',
                    'Week12' => $weeklyData['12'] != 0 ? $weeklyData['12'] : '',
                    'Mar' => collect($weeklyData)->only([9, 10, 11, 12])->sum(),
                    'Week13' => $weeklyData['13'] != 0 ? $weeklyData['13'] : '',
                    'Week14' => $weeklyData['14'] != 0 ? $weeklyData['14'] : '',
                    'Week15' => $weeklyData['15'] != 0 ? $weeklyData['15'] : '',
                    'Week16' => $weeklyData['16'] != 0 ? $weeklyData['16'] : '',
                    'Week17' => $weeklyData['17'] != 0 ? $weeklyData['17'] : '',
                    'Apr' => collect($weeklyData)->only([13, 14, 15, 16, 17])->sum(),
                    'Week18' => $weeklyData['18'] != 0 ? $weeklyData['18'] : '',
                    'Week19' => $weeklyData['19'] != 0 ? $weeklyData['19'] : '',
                    'Week20' => $weeklyData['21'] != 0 ? $weeklyData['21'] : '',
                    'Week21' => $weeklyData['21'] != 0 ? $weeklyData['21'] : '',
                    'May' => collect($weeklyData)->only([18, 19, 20, 21])->sum(),
                    'Week22' => $weeklyData['21'] != 0 ? $weeklyData['21'] : '',
                    'Week23' => $weeklyData['23'] != 0 ? $weeklyData['23'] : '',
                    'Week24' => $weeklyData['24'] != 0 ? $weeklyData['24'] : '',
                    'Week25' => $weeklyData['25'] != 0 ? $weeklyData['25'] : '',
                    'Jun' => collect($weeklyData)->only([22, 23, 24, 25])->sum(),
                    'Week26' => $weeklyData['26'] != 0 ? $weeklyData['26'] : '',
                    'Week27' => $weeklyData['27'] != 0 ? $weeklyData['27'] : '',
                    'Week28' => $weeklyData['28'] != 0 ? $weeklyData['28'] : '',
                    'Week29' => $weeklyData['29'] != 0 ? $weeklyData['29'] : '',
                    'Week30' => $weeklyData['30'] != 0 ? $weeklyData['30'] : '',
                    'Jul' => collect($weeklyData)->only([26, 27, 28, 29, 30])->sum(),
                    'Week31' => $weeklyData['31'] != 0 ? $weeklyData['31'] : '',
                    'Week32' => $weeklyData['32'] != 0 ? $weeklyData['32'] : '',
                    'Week33' => $weeklyData['33'] != 0 ? $weeklyData['33'] : '',
                    'Week34' => $weeklyData['34'] != 0 ? $weeklyData['34'] : '',
                    'Aug' => collect($weeklyData)->only([31, 32, 33, 34])->sum(),
                    'Week35' => $weeklyData['35'] != 0 ? $weeklyData['35'] : '',
                    'Week36' => $weeklyData['36'] != 0 ? $weeklyData['36'] : '',
                    'Week37' => $weeklyData['37'] != 0 ? $weeklyData['37'] : '',
                    'Week38' => $weeklyData['38'] != 0 ? $weeklyData['38'] : '',
                    'Week39' => $weeklyData['39'] != 0 ? $weeklyData['39'] : '',
                    'Sep' => collect($weeklyData)->only([35, 36, 37, 38, 39])->sum(),
                    'Week40' => $weeklyData['40'] != 0 ? $weeklyData['40'] : '',
                    'Week41' => $weeklyData['41'] != 0 ? $weeklyData['41'] : '',
                    'Week42' => $weeklyData['42'] != 0 ? $weeklyData['42'] : '',
                    'Week43' => $weeklyData['43'] != 0 ? $weeklyData['43'] : '',
                    'Oct' => collect($weeklyData)->only([40, 41, 42, 43])->sum(),
                    'Week44' => $weeklyData['44'] != 0 ? $weeklyData['44'] : '',
                    'Week45' => $weeklyData['45'] != 0 ? $weeklyData['45'] : '',
                    'Week46' => $weeklyData['46'] != 0 ? $weeklyData['46'] : '',
                    'Week47' => $weeklyData['47'] != 0 ? $weeklyData['47'] : '',
                    'Nov' => collect($weeklyData)->only([44, 45, 46, 47])->sum(),
                    'Week48' => $weeklyData['48'] != 0 ? $weeklyData['48'] : '',
                    'Week49' => $weeklyData['49'] != 0 ? $weeklyData['49'] : '',
                    'Week50' => $weeklyData['50'] != 0 ? $weeklyData['50'] : '',
                    'Week51' => $weeklyData['51'] != 0 ? $weeklyData['51'] : '',
                    'Week52' => $weeklyData['52'] != 0 ? $weeklyData['52'] : '',
                    'Dec' => collect($weeklyData)->only([48, 49, 50, 51, 52])->sum(),

                    'GrandTotalByProgram' => $grandTotal,
                ];
            }
            /* } */
        }

        $grandTotalForAllPrograms = array_sum(array_map('array_sum', $grandTotalByProgram));

        $mappedGroupedClasses[] = [
            'Site' => 'Grand Total',
            'Program' => '',
            'Week1' => $grandTotalByWeek['1'],
            'Week2' => $grandTotalByWeek['2'],
            'Week3' => $grandTotalByWeek['3'],
            'Week4' => $grandTotalByWeek['4'],
            'Jan' => collect($grandTotalByWeek)->only([1, 2, 3, 4])->sum(),
            'Week5' => $grandTotalByWeek['5'],
            'Week6' => $grandTotalByWeek['6'],
            'Week7' => $grandTotalByWeek['7'],
            'Week8' => $grandTotalByWeek['8'],
            'Feb' => collect($grandTotalByWeek)->only([5, 6, 7, 8])->sum(),
            'Week9' => $grandTotalByWeek['9'],
            'Week10' => $grandTotalByWeek['10'],
            'Week11' => $grandTotalByWeek['11'],
            'Week12' => $grandTotalByWeek['12'],
            'Mar' => collect($grandTotalByWeek)->only([9, 10, 11, 12])->sum(),
            'Week13' => $grandTotalByWeek['13'],
            'Week14' => $grandTotalByWeek['14'],
            'Week15' => $grandTotalByWeek['15'],
            'Week16' => $grandTotalByWeek['16'],
            'Week17' => $grandTotalByWeek['17'],
            'Apr' => collect($grandTotalByWeek)->only([13, 14, 15, 16, 17])->sum(),
            'Week18' => $grandTotalByWeek['18'],
            'Week19' => $grandTotalByWeek['19'],
            'Week20' => $grandTotalByWeek['21'],
            'Week21' => $grandTotalByWeek['21'],
            'May' => collect($grandTotalByWeek)->only([18, 19, 20, 21])->sum(),
            'Week22' => $grandTotalByWeek['21'],
            'Week23' => $grandTotalByWeek['23'],
            'Week24' => $grandTotalByWeek['24'],
            'Week25' => $grandTotalByWeek['25'],
            'Jun' => collect($grandTotalByWeek)->only([22, 23, 24, 25])->sum(),
            'Week26' => $grandTotalByWeek['26'],
            'Week27' => $grandTotalByWeek['27'],
            'Week28' => $grandTotalByWeek['28'],
            'Week29' => $grandTotalByWeek['29'],
            'Week30' => $grandTotalByWeek['30'],
            'Jul' => collect($grandTotalByWeek)->only([26, 27, 28, 29, 30])->sum(),
            'Week31' => $grandTotalByWeek['31'],
            'Week32' => $grandTotalByWeek['32'],
            'Week33' => $grandTotalByWeek['33'],
            'Week34' => $grandTotalByWeek['34'],
            'Aug' => collect($grandTotalByWeek)->only([31, 32, 33, 34])->sum(),
            'Week35' => $grandTotalByWeek['35'],
            'Week36' => $grandTotalByWeek['36'],
            'Week37' => $grandTotalByWeek['37'],
            'Week38' => $grandTotalByWeek['38'],
            'Week39' => $grandTotalByWeek['39'],
            'Sep' => collect($grandTotalByWeek)->only([35, 36, 37, 38, 39])->sum(),
            'Week40' => $grandTotalByWeek['40'],
            'Week41' => $grandTotalByWeek['41'],
            'Week42' => $grandTotalByWeek['42'],
            'Week43' => $grandTotalByWeek['43'],
            'Oct' => collect($grandTotalByWeek)->only([40, 41, 42, 43])->sum(),
            'Week44' => $grandTotalByWeek['44'],
            'Week45' => $grandTotalByWeek['45'],
            'Week46' => $grandTotalByWeek['46'],
            'Week47' => $grandTotalByWeek['47'],
            'Nov' => collect($grandTotalByWeek)->only([44, 45, 46, 47])->sum(),
            'Week48' => $grandTotalByWeek['48'],
            'Week49' => $grandTotalByWeek['49'],
            'Week50' => $grandTotalByWeek['50'],
            'Week51' => $grandTotalByWeek['51'],
            'Week52' => $grandTotalByWeek['52'],
            'Dec' => collect($grandTotalByWeek)->only([48, 49, 50, 51, 52])->sum(),
            'GrandTotalByProgram' => $grandTotalForAllPrograms,
        ];

        $response = [
            'classes' => $mappedGroupedClasses,
        ];

        return response()->json($response);
    }

    public function dashboardClasses2()
    {
        $classes = Classes::whereHas('site', function ($query) {
            $query->where('country', '=', 'Philippines');
        })
            ->with('site', 'program', 'dateRange', 'createdByUser', 'updatedByUser')
            ->get();

        return response()->json([
            'classes' => $classes,
        ]);
    }

    public function dashboardClasses3(Request $request)
    {
        $siteId = $request->input('site_id');
        $programId = $request->input('program_id');

        $programs = Program::with('site')
            ->when(!empty($siteId), function ($query) use ($siteId) {
                $query->whereIn('site_id', $siteId);
            })
            ->when(!empty($programId), function ($query) use ($programId) {
                $query->whereIn('program_id', $programId);
            })
            ->get();

        $dateRanges = DateRange::all();

        $groupedClasses = [];
        $grandTotalByWeek = [];
        $grandTotalByMonth = [];
        $grandTotalByProgram = [];

        foreach ($programs as $program) {
            $siteName = $program->site->name;
            $programName = $program->name;

            if (!isset($grandTotalByProgram[$siteName])) {
                $grandTotalByProgram[$siteName] = [];
            }
            $grandTotalByProgram[$siteName][$programName] = 0;

            foreach ($dateRanges as $dateRange) {
                $programId = $program->id;
                $week = $dateRange->id;
                $month = $dateRange->month;

                $classes = Classes::where('site_id', $program->site_id)
                    ->where('program_id', $programId)
                    ->where('date_range_id', $dateRange->id)
                    ->where('status', 'Cancelled')
                    ->get();

                $totalTarget = $classes->sum('total_target');

                if (!isset($grandTotalByWeek[$week])) {
                    $grandTotalByWeek[$week] = 0;
                }
                if (!isset($grandTotalByMonth[$month])) {
                    $grandTotalByMonth[$month] = 0;
                }

                $grandTotalByWeek[$week] += $totalTarget;
                $grandTotalByMonth[$month] += $totalTarget;

                $grandTotalByProgram[$siteName][$programName] += $totalTarget;

                if (!isset($groupedClasses[$siteName][$programName][$week])) {
                    $groupedClasses[$siteName][$programName][$week] = [
                        'total_target' => 0,
                    ];
                }
                if (!isset($groupedClasses[$siteName][$programName][$month])) {
                    $groupedClasses[$siteName][$programName][$month] = [
                        'total_target' => 0,
                    ];
                }

                $groupedClasses[$siteName][$programName][$week]['total_target'] += $totalTarget;
            }
        }

        $mappedGroupedClasses = [];

        foreach ($groupedClasses as $siteName => $siteData) {
            foreach ($siteData as $programName => $programData) {
                $weeklyData = [
                    '1' => 0,
                    '2' => 0,
                    '3' => 0,
                    '4' => 0,
                    '5' => 0,
                    '6' => 0,
                    '7' => 0,
                    '8' => 0,
                    '9' => 0,
                    '10' => 0,
                    '11' => 0,
                    '12' => 0,
                    '13' => 0,
                    '14' => 0,
                    '15' => 0,
                    '16' => 0,
                    '17' => 0,
                    '18' => 0,
                    '19' => 0,
                    '20' => 0,
                    '21' => 0,
                    '22' => 0,
                    '23' => 0,
                    '24' => 0,
                    '25' => 0,
                    '26' => 0,
                    '27' => 0,
                    '28' => 0,
                    '29' => 0,
                    '30' => 0,
                    '31' => 0,
                    '32' => 0,
                    '33' => 0,
                    '34' => 0,
                    '35' => 0,
                    '36' => 0,
                    '37' => 0,
                    '38' => 0,
                    '39' => 0,
                    '40' => 0,
                    '41' => 0,
                    '42' => 0,
                    '43' => 0,
                    '44' => 0,
                    '45' => 0,
                    '46' => 0,
                    '47' => 0,
                    '48' => 0,
                    '49' => 0,
                    '50' => 0,
                    '51' => 0,
                    '52' => 0,
                ];

                foreach ($programData as $week => $weekData) {
                    $weeklyData[$week] = isset($weekData['total_target']) ? $weekData['total_target'] : 0;
                }
                $grandTotal = $grandTotalByProgram[$siteName][$programName];
                if ($grandTotal != 0) {
                    $mappedGroupedClasses[] = [
                        'Site' => $siteName,
                        'Program' => $programName,
                        'Week1' => $weeklyData['1'] != 0 ? $weeklyData['1'] : '',
                        'Week2' => $weeklyData['2'] != 0 ? $weeklyData['2'] : '',
                        'Week3' => $weeklyData['3'] != 0 ? $weeklyData['3'] : '',
                        'Week4' => $weeklyData['4'] != 0 ? $weeklyData['4'] : '',
                        'Jan' => collect($weeklyData)->only([1, 2, 3, 4])->sum(),
                        'Week5' => $weeklyData['5'] != 0 ? $weeklyData['5'] : '',
                        'Week6' => $weeklyData['6'] != 0 ? $weeklyData['6'] : '',
                        'Week7' => $weeklyData['7'] != 0 ? $weeklyData['7'] : '',
                        'Week8' => $weeklyData['8'] != 0 ? $weeklyData['8'] : '',
                        'Feb' => collect($weeklyData)->only([5, 6, 7, 8])->sum(),
                        'Week9' => $weeklyData['9'] != 0 ? $weeklyData['9'] : '',
                        'Week10' => $weeklyData['10'] != 0 ? $weeklyData['10'] : '',
                        'Week11' => $weeklyData['11'] != 0 ? $weeklyData['11'] : '',
                        'Week12' => $weeklyData['12'] != 0 ? $weeklyData['12'] : '',
                        'Mar' => collect($weeklyData)->only([9, 10, 11, 12])->sum(),
                        'Week13' => $weeklyData['13'] != 0 ? $weeklyData['13'] : '',
                        'Week14' => $weeklyData['14'] != 0 ? $weeklyData['14'] : '',
                        'Week15' => $weeklyData['15'] != 0 ? $weeklyData['15'] : '',
                        'Week16' => $weeklyData['16'] != 0 ? $weeklyData['16'] : '',
                        'Week17' => $weeklyData['17'] != 0 ? $weeklyData['17'] : '',
                        'Apr' => collect($weeklyData)->only([13, 14, 15, 16, 17])->sum(),
                        'Week18' => $weeklyData['18'] != 0 ? $weeklyData['18'] : '',
                        'Week19' => $weeklyData['19'] != 0 ? $weeklyData['19'] : '',
                        'Week20' => $weeklyData['21'] != 0 ? $weeklyData['21'] : '',
                        'Week21' => $weeklyData['21'] != 0 ? $weeklyData['21'] : '',
                        'May' => collect($weeklyData)->only([18, 19, 20, 21])->sum(),
                        'Week22' => $weeklyData['21'] != 0 ? $weeklyData['21'] : '',
                        'Week23' => $weeklyData['23'] != 0 ? $weeklyData['23'] : '',
                        'Week24' => $weeklyData['24'] != 0 ? $weeklyData['24'] : '',
                        'Week25' => $weeklyData['25'] != 0 ? $weeklyData['25'] : '',
                        'Jun' => collect($weeklyData)->only([22, 23, 24, 25])->sum(),
                        'Week26' => $weeklyData['26'] != 0 ? $weeklyData['26'] : '',
                        'Week27' => $weeklyData['27'] != 0 ? $weeklyData['27'] : '',
                        'Week28' => $weeklyData['28'] != 0 ? $weeklyData['28'] : '',
                        'Week29' => $weeklyData['29'] != 0 ? $weeklyData['29'] : '',
                        'Week30' => $weeklyData['30'] != 0 ? $weeklyData['30'] : '',
                        'Jul' => collect($weeklyData)->only([26, 27, 28, 29, 30])->sum(),
                        'Week31' => $weeklyData['31'] != 0 ? $weeklyData['31'] : '',
                        'Week32' => $weeklyData['32'] != 0 ? $weeklyData['32'] : '',
                        'Week33' => $weeklyData['33'] != 0 ? $weeklyData['33'] : '',
                        'Week34' => $weeklyData['34'] != 0 ? $weeklyData['34'] : '',
                        'Aug' => collect($weeklyData)->only([31, 32, 33, 34])->sum(),
                        'Week35' => $weeklyData['35'] != 0 ? $weeklyData['35'] : '',
                        'Week36' => $weeklyData['36'] != 0 ? $weeklyData['36'] : '',
                        'Week37' => $weeklyData['37'] != 0 ? $weeklyData['37'] : '',
                        'Week38' => $weeklyData['38'] != 0 ? $weeklyData['38'] : '',
                        'Week39' => $weeklyData['39'] != 0 ? $weeklyData['39'] : '',
                        'Sep' => collect($weeklyData)->only([35, 36, 37, 38, 39])->sum(),
                        'Week40' => $weeklyData['40'] != 0 ? $weeklyData['40'] : '',
                        'Week41' => $weeklyData['41'] != 0 ? $weeklyData['41'] : '',
                        'Week42' => $weeklyData['42'] != 0 ? $weeklyData['42'] : '',
                        'Week43' => $weeklyData['43'] != 0 ? $weeklyData['43'] : '',
                        'Oct' => collect($weeklyData)->only([40, 41, 42, 43])->sum(),
                        'Week44' => $weeklyData['44'] != 0 ? $weeklyData['44'] : '',
                        'Week45' => $weeklyData['45'] != 0 ? $weeklyData['45'] : '',
                        'Week46' => $weeklyData['46'] != 0 ? $weeklyData['46'] : '',
                        'Week47' => $weeklyData['47'] != 0 ? $weeklyData['47'] : '',
                        'Nov' => collect($weeklyData)->only([44, 45, 46, 47])->sum(),
                        'Week48' => $weeklyData['48'] != 0 ? $weeklyData['48'] : '',
                        'Week49' => $weeklyData['49'] != 0 ? $weeklyData['49'] : '',
                        'Week50' => $weeklyData['50'] != 0 ? $weeklyData['50'] : '',
                        'Week51' => $weeklyData['51'] != 0 ? $weeklyData['51'] : '',
                        'Week52' => $weeklyData['52'] != 0 ? $weeklyData['52'] : '',
                        'Dec' => collect($weeklyData)->only([48, 49, 50, 51, 52])->sum(),

                        'GrandTotalByProgram' => $grandTotal,
                    ];
                }
            }
        }

        $grandTotalForAllPrograms = array_sum(array_map('array_sum', $grandTotalByProgram));

        $mappedGroupedClasses[] = [
            'Site' => 'Grand Total',
            'Program' => '',
            'Week1' => $grandTotalByWeek['1'],
            'Week2' => $grandTotalByWeek['2'],
            'Week3' => $grandTotalByWeek['3'],
            'Week4' => $grandTotalByWeek['4'],
            'Jan' => collect($grandTotalByWeek)->only([1, 2, 3, 4])->sum(),
            'Week5' => $grandTotalByWeek['5'],
            'Week6' => $grandTotalByWeek['6'],
            'Week7' => $grandTotalByWeek['7'],
            'Week8' => $grandTotalByWeek['8'],
            'Feb' => collect($grandTotalByWeek)->only([5, 6, 7, 8])->sum(),
            'Week9' => $grandTotalByWeek['9'],
            'Week10' => $grandTotalByWeek['10'],
            'Week11' => $grandTotalByWeek['11'],
            'Week12' => $grandTotalByWeek['12'],
            'Mar' => collect($grandTotalByWeek)->only([9, 10, 11, 12])->sum(),
            'Week13' => $grandTotalByWeek['13'],
            'Week14' => $grandTotalByWeek['14'],
            'Week15' => $grandTotalByWeek['15'],
            'Week16' => $grandTotalByWeek['16'],
            'Week17' => $grandTotalByWeek['17'],
            'Apr' => collect($grandTotalByWeek)->only([13, 14, 15, 16, 17])->sum(),
            'Week18' => $grandTotalByWeek['18'],
            'Week19' => $grandTotalByWeek['19'],
            'Week20' => $grandTotalByWeek['21'],
            'Week21' => $grandTotalByWeek['21'],
            'May' => collect($grandTotalByWeek)->only([18, 19, 20, 21])->sum(),
            'Week22' => $grandTotalByWeek['21'],
            'Week23' => $grandTotalByWeek['23'],
            'Week24' => $grandTotalByWeek['24'],
            'Week25' => $grandTotalByWeek['25'],
            'Jun' => collect($grandTotalByWeek)->only([22, 23, 24, 25])->sum(),
            'Week26' => $grandTotalByWeek['26'],
            'Week27' => $grandTotalByWeek['27'],
            'Week28' => $grandTotalByWeek['28'],
            'Week29' => $grandTotalByWeek['29'],
            'Week30' => $grandTotalByWeek['30'],
            'Jul' => collect($grandTotalByWeek)->only([26, 27, 28, 29, 30])->sum(),
            'Week31' => $grandTotalByWeek['31'],
            'Week32' => $grandTotalByWeek['32'],
            'Week33' => $grandTotalByWeek['33'],
            'Week34' => $grandTotalByWeek['34'],
            'Aug' => collect($grandTotalByWeek)->only([31, 32, 33, 34])->sum(),
            'Week35' => $grandTotalByWeek['35'],
            'Week36' => $grandTotalByWeek['36'],
            'Week37' => $grandTotalByWeek['37'],
            'Week38' => $grandTotalByWeek['38'],
            'Week39' => $grandTotalByWeek['39'],
            'Sep' => collect($grandTotalByWeek)->only([35, 36, 37, 38, 39])->sum(),
            'Week40' => $grandTotalByWeek['40'],
            'Week41' => $grandTotalByWeek['41'],
            'Week42' => $grandTotalByWeek['42'],
            'Week43' => $grandTotalByWeek['43'],
            'Oct' => collect($grandTotalByWeek)->only([40, 41, 42, 43])->sum(),
            'Week44' => $grandTotalByWeek['44'],
            'Week45' => $grandTotalByWeek['45'],
            'Week46' => $grandTotalByWeek['46'],
            'Week47' => $grandTotalByWeek['47'],
            'Nov' => collect($grandTotalByWeek)->only([44, 45, 46, 47])->sum(),
            'Week48' => $grandTotalByWeek['48'],
            'Week49' => $grandTotalByWeek['49'],
            'Week50' => $grandTotalByWeek['50'],
            'Week51' => $grandTotalByWeek['51'],
            'Week52' => $grandTotalByWeek['52'],
            'Dec' => collect($grandTotalByWeek)->only([48, 49, 50, 51, 52])->sum(),
            'GrandTotalByProgram' => $grandTotalForAllPrograms,
        ];

        $response = [
            'classes' => $mappedGroupedClasses,
        ];

        return response()->json($response);
    }

    public function dashboardClasses4(Request $request)
    {
        $siteId = $request->input('site_id');
        $programId = $request->input('program_id');

        $programs = Program::with('site')
            ->when(!empty($siteId), function ($query) use ($siteId) {
                $query->whereIn('site_id', $siteId);
            })
            ->when(!empty($programId), function ($query) use ($programId) {
                $query->whereIn('program_id', $programId);
            })
            ->get();

        $dateRanges = DateRange::all();

        $groupedClasses = [];
        $grandTotalByWeek = [];
        $grandTotalByMonth = [];
        $grandTotalByProgram = [];

        foreach ($programs as $program) {
            $siteName = $program->site->name;
            $programName = $program->name;

            if (!isset($grandTotalByProgram[$siteName])) {
                $grandTotalByProgram[$siteName] = [];
            }
            $grandTotalByProgram[$siteName][$programName] = 0;

            foreach ($dateRanges as $dateRange) {
                $programId = $program->id;
                $week = $dateRange->id;
                $month = $dateRange->month;

                $classes = Classes::where('site_id', $program->site_id)
                    ->where('program_id', $programId)
                    ->where('date_range_id', $dateRange->id)
                    ->where('status', 'Moved')
                    ->get();

                $totalTarget = $classes->sum('total_target');

                if (!isset($grandTotalByWeek[$week])) {
                    $grandTotalByWeek[$week] = 0;
                }
                if (!isset($grandTotalByMonth[$month])) {
                    $grandTotalByMonth[$month] = 0;
                }

                $grandTotalByWeek[$week] += $totalTarget;
                $grandTotalByMonth[$month] += $totalTarget;

                $grandTotalByProgram[$siteName][$programName] += $totalTarget;

                if (!isset($groupedClasses[$siteName][$programName][$week])) {
                    $groupedClasses[$siteName][$programName][$week] = [
                        'total_target' => 0,
                    ];
                }
                if (!isset($groupedClasses[$siteName][$programName][$month])) {
                    $groupedClasses[$siteName][$programName][$month] = [
                        'total_target' => 0,
                    ];
                }

                $groupedClasses[$siteName][$programName][$week]['total_target'] += $totalTarget;
            }
        }

        $mappedGroupedClasses = [];

        foreach ($groupedClasses as $siteName => $siteData) {
            foreach ($siteData as $programName => $programData) {
                $weeklyData = [
                    '1' => 0,
                    '2' => 0,
                    '3' => 0,
                    '4' => 0,
                    '5' => 0,
                    '6' => 0,
                    '7' => 0,
                    '8' => 0,
                    '9' => 0,
                    '10' => 0,
                    '11' => 0,
                    '12' => 0,
                    '13' => 0,
                    '14' => 0,
                    '15' => 0,
                    '16' => 0,
                    '17' => 0,
                    '18' => 0,
                    '19' => 0,
                    '20' => 0,
                    '21' => 0,
                    '22' => 0,
                    '23' => 0,
                    '24' => 0,
                    '25' => 0,
                    '26' => 0,
                    '27' => 0,
                    '28' => 0,
                    '29' => 0,
                    '30' => 0,
                    '31' => 0,
                    '32' => 0,
                    '33' => 0,
                    '34' => 0,
                    '35' => 0,
                    '36' => 0,
                    '37' => 0,
                    '38' => 0,
                    '39' => 0,
                    '40' => 0,
                    '41' => 0,
                    '42' => 0,
                    '43' => 0,
                    '44' => 0,
                    '45' => 0,
                    '46' => 0,
                    '47' => 0,
                    '48' => 0,
                    '49' => 0,
                    '50' => 0,
                    '51' => 0,
                    '52' => 0,
                ];

                foreach ($programData as $week => $weekData) {
                    $weeklyData[$week] = isset($weekData['total_target']) ? $weekData['total_target'] : 0;
                }
                $grandTotal = $grandTotalByProgram[$siteName][$programName];
                if ($grandTotal != 0) {
                    $mappedGroupedClasses[] = [
                        'Site' => $siteName,
                        'Program' => $programName,
                        'Week1' => $weeklyData['1'] != 0 ? $weeklyData['1'] : '',
                        'Week2' => $weeklyData['2'] != 0 ? $weeklyData['2'] : '',
                        'Week3' => $weeklyData['3'] != 0 ? $weeklyData['3'] : '',
                        'Week4' => $weeklyData['4'] != 0 ? $weeklyData['4'] : '',
                        'Jan' => collect($weeklyData)->only([1, 2, 3, 4])->sum(),
                        'Week5' => $weeklyData['5'] != 0 ? $weeklyData['5'] : '',
                        'Week6' => $weeklyData['6'] != 0 ? $weeklyData['6'] : '',
                        'Week7' => $weeklyData['7'] != 0 ? $weeklyData['7'] : '',
                        'Week8' => $weeklyData['8'] != 0 ? $weeklyData['8'] : '',
                        'Feb' => collect($weeklyData)->only([5, 6, 7, 8])->sum(),
                        'Week9' => $weeklyData['9'] != 0 ? $weeklyData['9'] : '',
                        'Week10' => $weeklyData['10'] != 0 ? $weeklyData['10'] : '',
                        'Week11' => $weeklyData['11'] != 0 ? $weeklyData['11'] : '',
                        'Week12' => $weeklyData['12'] != 0 ? $weeklyData['12'] : '',
                        'Mar' => collect($weeklyData)->only([9, 10, 11, 12])->sum(),
                        'Week13' => $weeklyData['13'] != 0 ? $weeklyData['13'] : '',
                        'Week14' => $weeklyData['14'] != 0 ? $weeklyData['14'] : '',
                        'Week15' => $weeklyData['15'] != 0 ? $weeklyData['15'] : '',
                        'Week16' => $weeklyData['16'] != 0 ? $weeklyData['16'] : '',
                        'Week17' => $weeklyData['17'] != 0 ? $weeklyData['17'] : '',
                        'Apr' => collect($weeklyData)->only([13, 14, 15, 16, 17])->sum(),
                        'Week18' => $weeklyData['18'] != 0 ? $weeklyData['18'] : '',
                        'Week19' => $weeklyData['19'] != 0 ? $weeklyData['19'] : '',
                        'Week20' => $weeklyData['21'] != 0 ? $weeklyData['21'] : '',
                        'Week21' => $weeklyData['21'] != 0 ? $weeklyData['21'] : '',
                        'May' => collect($weeklyData)->only([18, 19, 20, 21])->sum(),
                        'Week22' => $weeklyData['21'] != 0 ? $weeklyData['21'] : '',
                        'Week23' => $weeklyData['23'] != 0 ? $weeklyData['23'] : '',
                        'Week24' => $weeklyData['24'] != 0 ? $weeklyData['24'] : '',
                        'Week25' => $weeklyData['25'] != 0 ? $weeklyData['25'] : '',
                        'Jun' => collect($weeklyData)->only([22, 23, 24, 25])->sum(),
                        'Week26' => $weeklyData['26'] != 0 ? $weeklyData['26'] : '',
                        'Week27' => $weeklyData['27'] != 0 ? $weeklyData['27'] : '',
                        'Week28' => $weeklyData['28'] != 0 ? $weeklyData['28'] : '',
                        'Week29' => $weeklyData['29'] != 0 ? $weeklyData['29'] : '',
                        'Week30' => $weeklyData['30'] != 0 ? $weeklyData['30'] : '',
                        'Jul' => collect($weeklyData)->only([26, 27, 28, 29, 30])->sum(),
                        'Week31' => $weeklyData['31'] != 0 ? $weeklyData['31'] : '',
                        'Week32' => $weeklyData['32'] != 0 ? $weeklyData['32'] : '',
                        'Week33' => $weeklyData['33'] != 0 ? $weeklyData['33'] : '',
                        'Week34' => $weeklyData['34'] != 0 ? $weeklyData['34'] : '',
                        'Aug' => collect($weeklyData)->only([31, 32, 33, 34])->sum(),
                        'Week35' => $weeklyData['35'] != 0 ? $weeklyData['35'] : '',
                        'Week36' => $weeklyData['36'] != 0 ? $weeklyData['36'] : '',
                        'Week37' => $weeklyData['37'] != 0 ? $weeklyData['37'] : '',
                        'Week38' => $weeklyData['38'] != 0 ? $weeklyData['38'] : '',
                        'Week39' => $weeklyData['39'] != 0 ? $weeklyData['39'] : '',
                        'Sep' => collect($weeklyData)->only([35, 36, 37, 38, 39])->sum(),
                        'Week40' => $weeklyData['40'] != 0 ? $weeklyData['40'] : '',
                        'Week41' => $weeklyData['41'] != 0 ? $weeklyData['41'] : '',
                        'Week42' => $weeklyData['42'] != 0 ? $weeklyData['42'] : '',
                        'Week43' => $weeklyData['43'] != 0 ? $weeklyData['43'] : '',
                        'Oct' => collect($weeklyData)->only([40, 41, 42, 43])->sum(),
                        'Week44' => $weeklyData['44'] != 0 ? $weeklyData['44'] : '',
                        'Week45' => $weeklyData['45'] != 0 ? $weeklyData['45'] : '',
                        'Week46' => $weeklyData['46'] != 0 ? $weeklyData['46'] : '',
                        'Week47' => $weeklyData['47'] != 0 ? $weeklyData['47'] : '',
                        'Nov' => collect($weeklyData)->only([44, 45, 46, 47])->sum(),
                        'Week48' => $weeklyData['48'] != 0 ? $weeklyData['48'] : '',
                        'Week49' => $weeklyData['49'] != 0 ? $weeklyData['49'] : '',
                        'Week50' => $weeklyData['50'] != 0 ? $weeklyData['50'] : '',
                        'Week51' => $weeklyData['51'] != 0 ? $weeklyData['51'] : '',
                        'Week52' => $weeklyData['52'] != 0 ? $weeklyData['52'] : '',
                        'Dec' => collect($weeklyData)->only([48, 49, 50, 51, 52])->sum(),

                        'GrandTotalByProgram' => $grandTotal,
                    ];
                }
            }
        }

        $grandTotalForAllPrograms = array_sum(array_map('array_sum', $grandTotalByProgram));

        $mappedGroupedClasses[] = [
            'Site' => 'Grand Total',
            'Program' => '',
            'Week1' => $grandTotalByWeek['1'],
            'Week2' => $grandTotalByWeek['2'],
            'Week3' => $grandTotalByWeek['3'],
            'Week4' => $grandTotalByWeek['4'],
            'Jan' => collect($grandTotalByWeek)->only([1, 2, 3, 4])->sum(),
            'Week5' => $grandTotalByWeek['5'],
            'Week6' => $grandTotalByWeek['6'],
            'Week7' => $grandTotalByWeek['7'],
            'Week8' => $grandTotalByWeek['8'],
            'Feb' => collect($grandTotalByWeek)->only([5, 6, 7, 8])->sum(),
            'Week9' => $grandTotalByWeek['9'],
            'Week10' => $grandTotalByWeek['10'],
            'Week11' => $grandTotalByWeek['11'],
            'Week12' => $grandTotalByWeek['12'],
            'Mar' => collect($grandTotalByWeek)->only([9, 10, 11, 12])->sum(),
            'Week13' => $grandTotalByWeek['13'],
            'Week14' => $grandTotalByWeek['14'],
            'Week15' => $grandTotalByWeek['15'],
            'Week16' => $grandTotalByWeek['16'],
            'Week17' => $grandTotalByWeek['17'],
            'Apr' => collect($grandTotalByWeek)->only([13, 14, 15, 16, 17])->sum(),
            'Week18' => $grandTotalByWeek['18'],
            'Week19' => $grandTotalByWeek['19'],
            'Week20' => $grandTotalByWeek['21'],
            'Week21' => $grandTotalByWeek['21'],
            'May' => collect($grandTotalByWeek)->only([18, 19, 20, 21])->sum(),
            'Week22' => $grandTotalByWeek['21'],
            'Week23' => $grandTotalByWeek['23'],
            'Week24' => $grandTotalByWeek['24'],
            'Week25' => $grandTotalByWeek['25'],
            'Jun' => collect($grandTotalByWeek)->only([22, 23, 24, 25])->sum(),
            'Week26' => $grandTotalByWeek['26'],
            'Week27' => $grandTotalByWeek['27'],
            'Week28' => $grandTotalByWeek['28'],
            'Week29' => $grandTotalByWeek['29'],
            'Week30' => $grandTotalByWeek['30'],
            'Jul' => collect($grandTotalByWeek)->only([26, 27, 28, 29, 30])->sum(),
            'Week31' => $grandTotalByWeek['31'],
            'Week32' => $grandTotalByWeek['32'],
            'Week33' => $grandTotalByWeek['33'],
            'Week34' => $grandTotalByWeek['34'],
            'Aug' => collect($grandTotalByWeek)->only([31, 32, 33, 34])->sum(),
            'Week35' => $grandTotalByWeek['35'],
            'Week36' => $grandTotalByWeek['36'],
            'Week37' => $grandTotalByWeek['37'],
            'Week38' => $grandTotalByWeek['38'],
            'Week39' => $grandTotalByWeek['39'],
            'Sep' => collect($grandTotalByWeek)->only([35, 36, 37, 38, 39])->sum(),
            'Week40' => $grandTotalByWeek['40'],
            'Week41' => $grandTotalByWeek['41'],
            'Week42' => $grandTotalByWeek['42'],
            'Week43' => $grandTotalByWeek['43'],
            'Oct' => collect($grandTotalByWeek)->only([40, 41, 42, 43])->sum(),
            'Week44' => $grandTotalByWeek['44'],
            'Week45' => $grandTotalByWeek['45'],
            'Week46' => $grandTotalByWeek['46'],
            'Week47' => $grandTotalByWeek['47'],
            'Nov' => collect($grandTotalByWeek)->only([44, 45, 46, 47])->sum(),
            'Week48' => $grandTotalByWeek['48'],
            'Week49' => $grandTotalByWeek['49'],
            'Week50' => $grandTotalByWeek['50'],
            'Week51' => $grandTotalByWeek['51'],
            'Week52' => $grandTotalByWeek['52'],
            'Dec' => collect($grandTotalByWeek)->only([48, 49, 50, 51, 52])->sum(),
            'GrandTotalByProgram' => $grandTotalForAllPrograms,
        ];

        $response = [
            'classes' => $mappedGroupedClasses,
        ];

        return response()->json($response);
    }

    public function classesallGua()
    {
        $classes = Classes::whereHas('site', function ($query) {
            $query->where('country', '=', 'Guatemala');
        })
            ->with('site', 'program', 'dateRange', 'createdByUser', 'updatedByUser')
            ->where('status', 'Active')
            ->get();

        return response()->json([
            'classes' => $classes,
        ]);
    }

    public function pushedback(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'notice_weeks' => 'required',
            'notice_days' => 'required',
            'external_target' => 'required',
            'internal_target' => 'required',
            'total_target' => 'required',
            'type_of_hiring' => 'required',
            'within_sla' => 'required',
            'with_erf' => 'required',
            'erf_number' => 'nullable',
            'remarks' => 'required',
            'status' => 'required',
            'approved_status' => 'required',
            'original_start_date' => 'required',
            'wfm_date_requested' => 'nullable',
            'program_id' => 'required',
            'site_id' => 'required',
            'date_range_id' => 'required',
            'category' => 'required',
            'requested_by' => 'required',
            'agreed_start_date' => 'required',
            'approved_by' => 'required',
            'updated_by' => 'required',
            'changes' => 'required',
            'wf' => 'nullable',
            'tr' => 'nullable',
            'op' => 'nullable',
            'ta' => 'nullable',
            'cl' => 'nullable',
        ]);
        $requested_by = [$request->requested_by];
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $class = Classes::find($id);
        $class->status = 'Moved';
        $class->save();
        $newClass = $class->replicate();
        $newClass->update_status = $class->update_status + 1;
        $newClass->changes = 'Pushedback';
        $newClass->requested_by = json_encode($requested_by);
        $newClass->fill($request->all());
        $newClass->save();

        return new ClassesResource($newClass);
    }

    public function edit(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'notice_weeks' => 'required',
            'notice_days' => 'required',
            'external_target' => 'required',
            'internal_target' => 'required',
            'total_target' => 'required',
            'type_of_hiring' => 'required',
            'within_sla' => 'required',
            'with_erf' => 'required',
            'erf_number' => 'nullable',
            'remarks' => 'required',
            'status' => 'required',
            'approved_status' => 'required',
            'original_start_date' => 'required',
            'wfm_date_requested' => 'nullable',
            'program_id' => 'required',
            'site_id' => 'required',
            'date_range_id' => 'required',
            'category' => 'required',
            'requested_by' => 'required',
            'agreed_start_date' => 'required',
            'approved_by' => 'required',
            'updated_by' => 'required',
            'changes' => 'required',
            'wf' => 'nullable',
            'tr' => 'nullable',
            'op' => 'nullable',
            'ta' => 'nullable',
            'cl' => 'nullable',
            'wave_no' => 'nullable',
        ]);
        $requested_by = [$request->requested_by];
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $class = Classes::find($id);
        $class->fill($request->all());
        $class->requested_by = json_encode($requested_by);
        $class->save();

        // @ts-ignore
        return new ClassesResource($class);
    }

    public function cancel(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'site_id' => 'required',
            'program_id' => 'required',
            'date_range_id' => 'required',
            'cancelled_by' => 'required',
            'wf' => 'nullable',
            'tr' => 'nullable',
            'op' => 'nullable',
            'ta' => 'nullable',
            'cl' => 'nullable',
            'cancelled_date' => 'required',
        ]);
        $cancelled_by = [$request->cancelled_by];

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $class = Classes::find($id);
        $class->status = 'Cancelled Class';
        $class->save();
        $newClass = $class->replicate();
        $newClass->cancelled_by = json_encode($cancelled_by);
        $newClass->changes = 'Cancellation';
        $newClass->cancelled_date = $request->input('cancelled_date');
        $newClass->status = 'Cancelled';
        $newClass->save();

        // @ts-ignore
        return new ClassesResource($class);
    }
}
