<?php

namespace App\Http\Controllers;

use App\Exports\DashboardClassesExport;
use App\Exports\MyExport;
use App\Http\Resources\ClassesResource;
use App\Models\Classes;
use App\Models\ClassStaffing;
use App\Models\DateRange;
use App\Models\Program;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class ClassesController extends Controller
{
    public function perxFilter(Request $request)
    {
        $query = DB::connection('secondary_sqlsrv')
            ->table('PERX_DATA');

        if ($request->has('filter_lastname')) {
            $filterLastName = $request->input('filter_lastname');

            if (!empty($filterLastName)) {
                $query->where('LastName', 'LIKE', '%' . $filterLastName . '%');
            }
        }

        if ($request->has('filter_firstname')) {
            $filterFirstName = $request->input('filter_firstname');

            if (!empty($filterFirstName)) {
                $query->where('FirstName', 'LIKE', '%' . $filterFirstName . '%');
            }
        }

        if ($request->has('filter_site')) {
            $filterSite = $request->input('filter_site');

            if (!empty($filterSite)) {
                $query->where('Site', 'LIKE', '%' . $filterSite . '%');
            }
        }
        if ($request->has('filter_date_start') && $request->has('filter_date_end')) {
            $filterDateStart = $request->input('filter_date_start');
            $filterDateEnd = $request->input('filter_date_end');

            if (!empty($filterDateStart) && !empty($filterDateEnd)) {
                $startDate = date('Y-m-d', strtotime($filterDateStart));
                $endDate = date('Y-m-d', strtotime($filterDateEnd));

                $endDate = date('Y-m-d', strtotime($endDate . ' +1 day'));

                $query->where('DateOfApplication', '>=', $startDate)
                    ->where('DateOfApplication', '<', $endDate);
            }
        }

        if ($request->has('filter_contact')) {
            $filterContact = $request->input('filter_contact');

            if (!empty($filterContact)) {
                $query->where('MobileNo', 'LIKE', '%' . $filterContact . '%');
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
                $query->where('LastName', 'LIKE', '%' . $filterLastName . '%');
            }
        }

        if ($request->has('filter_firstname')) {
            $filterFirstName = $request->input('filter_firstname');

            if (!empty($filterFirstName)) {
                $query->where('FirstName', 'LIKE', '%' . $filterFirstName . '%');
            }
        }

        if ($request->has('filter_site')) {
            $filterSite = $request->input('filter_site');

            if (!empty($filterSite)) {
                $query->where('Site', 'LIKE', '%' . $filterSite . '%');
            }
        }
        if ($request->has('filter_date_start') && $request->has('filter_date_end')) {
            $filterDateStart = $request->input('filter_date_start');
            $filterDateEnd = $request->input('filter_date_end');

            if (!empty($filterDateStart) && !empty($filterDateEnd)) {

                $startDate = date('Y-m-d', strtotime($filterDateStart));
                $endDate = date('Y-m-d', strtotime($filterDateEnd));

                $endDate = date('Y-m-d', strtotime($endDate . ' +1 day'));

                $query->where('DateOfApplication', '>=', $startDate)
                    ->where('DateOfApplication', '<', $endDate);
            }
        }

        if ($request->has('filter_contact')) {
            $filterContact = $request->input('filter_contact');

            if (!empty($filterContact)) {
                $query->where('MobileNo', 'LIKE', '%' . $filterContact . '%');
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
                $daterangeName = $dateRange->date_range;
                $programId = $program->id;
                $month = $dateRange->month;

                $classes = Classes::where('site_id', $program->site_id)
                    ->where('program_id', $programId)
                    ->where('date_range_id', $dateRange->id)
                    ->where('status', 'Active')
                    ->get();

                $totalTarget = $classes->sum('total_target');

                if (!isset($grandTotalByMonth[$month])) {
                    $grandTotalByMonth[$month] = 0;
                }
                $grandTotalByMonth[$month] += $totalTarget;

                $grandTotalByProgram[$siteName][$programName] += $totalTarget;

                if (!isset($groupedClasses[$siteName][$programName][$month])) {
                    $groupedClasses[$siteName][$programName][$month] = [
                        'total_target' => 0,
                    ];
                }

                $groupedClasses[$siteName][$programName][$month]['total_target'] += $totalTarget;
            }
        }

        $mappedGroupedClasses = [];

        foreach ($groupedClasses as $siteName => $siteData) {
            foreach ($siteData as $programName => $programData) {
                $monthlyData = [
                    'January' => 0,
                    'February' => 0,
                    'March' => 0,
                    'April' => 0,
                    'May' => 0,
                    'June' => 0,
                    'July' => 0,
                    'August' => 0,
                    'September' => 0,
                    'October' => 0,
                    'November' => 0,
                    'December' => 0,
                ];

                foreach ($programData as $month => $monthData) {
                    $monthlyData[$month] = isset($monthData['total_target']) ? $monthData['total_target'] : 0;
                }

                $mappedGroupedClasses[] = [
                    'Site' => $siteName,
                    'Program' => $programName,
                    'January' => $monthlyData['January'],
                    'February' => $monthlyData['February'],
                    'March' => $monthlyData['March'],
                    'April' => $monthlyData['April'],
                    'May' => $monthlyData['May'],
                    'June' => $monthlyData['June'],
                    'July' => $monthlyData['July'],
                    'August' => $monthlyData['August'],
                    'September' => $monthlyData['September'],
                    'October' => $monthlyData['October'],
                    'November' => $monthlyData['November'],
                    'December' => $monthlyData['December'],
                    'GrandTotalByProgram' => isset($grandTotalByProgram[$siteName][$programName]) ? $grandTotalByProgram[$siteName][$programName] : 0,
                ];
            }
        }
        $mappedGroupedClasses[] = [
            'Site' => 'Grand Total',
            'Program' => '',
            'January' => $grandTotalByMonth['January'],
            'February' => $grandTotalByMonth['February'],
            'March' => $grandTotalByMonth['March'],
            'April' => $grandTotalByMonth['April'],
            'May' => $grandTotalByMonth['May'],
            'June' => $grandTotalByMonth['June'],
            'July' => $grandTotalByMonth['July'],
            'August' => $grandTotalByMonth['August'],
            'September' => $grandTotalByMonth['September'],
            'October' => $grandTotalByMonth['October'],
            'November' => $grandTotalByMonth['November'],
            'December' => $grandTotalByMonth['December'],
            'GrandTotalByProgram' => isset($grandTotalByProgram[$siteName][$programName]) ? $grandTotalByProgram[$siteName][$programName] : 0,
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
                $daterangeName = $dateRange->date_range;
                $programId = $program->id;
                $month = $dateRange->month;

                $classes = Classes::where('site_id', $program->site_id)
                    ->where('program_id', $programId)
                    ->where('date_range_id', $dateRange->id)
                    ->where('status', 'Cancelled')
                    ->get();

                $totalTarget = $classes->sum('total_target');

                if (!isset($grandTotalByMonth[$month])) {
                    $grandTotalByMonth[$month] = 0;
                }
                $grandTotalByMonth[$month] += $totalTarget;

                $grandTotalByProgram[$siteName][$programName] += $totalTarget;

                if (!isset($groupedClasses[$siteName][$programName][$month])) {
                    $groupedClasses[$siteName][$programName][$month] = [
                        'total_target' => 0,
                    ];
                }

                $groupedClasses[$siteName][$programName][$month]['total_target'] += $totalTarget;
            }
        }

        $mappedGroupedClasses = [];

        foreach ($groupedClasses as $siteName => $siteData) {
            foreach ($siteData as $programName => $programData) {
                $monthlyData = [
                    'January' => 0,
                    'February' => 0,
                    'March' => 0,
                    'April' => 0,
                    'May' => 0,
                    'June' => 0,
                    'July' => 0,
                    'August' => 0,
                    'September' => 0,
                    'October' => 0,
                    'November' => 0,
                    'December' => 0,
                ];

                foreach ($programData as $month => $monthData) {
                    $monthlyData[$month] = isset($monthData['total_target']) ? $monthData['total_target'] : 0;
                }

                $mappedGroupedClasses[] = [
                    'Site' => $siteName,
                    'Program' => $programName,
                    'January' => $monthlyData['January'],
                    'February' => $monthlyData['February'],
                    'March' => $monthlyData['March'],
                    'April' => $monthlyData['April'],
                    'May' => $monthlyData['May'],
                    'June' => $monthlyData['June'],
                    'July' => $monthlyData['July'],
                    'August' => $monthlyData['August'],
                    'September' => $monthlyData['September'],
                    'October' => $monthlyData['October'],
                    'November' => $monthlyData['November'],
                    'December' => $monthlyData['December'],
                    'GrandTotalByProgram' => isset($grandTotalByProgram[$siteName][$programName]) ? $grandTotalByProgram[$siteName][$programName] : 0,
                ];
            }
        }
        $mappedGroupedClasses[] = [
            'Site' => 'Grand Total',
            'Program' => '',
            'January' => $grandTotalByMonth['January'],
            'February' => $grandTotalByMonth['February'],
            'March' => $grandTotalByMonth['March'],
            'April' => $grandTotalByMonth['April'],
            'May' => $grandTotalByMonth['May'],
            'June' => $grandTotalByMonth['June'],
            'July' => $grandTotalByMonth['July'],
            'August' => $grandTotalByMonth['August'],
            'September' => $grandTotalByMonth['September'],
            'October' => $grandTotalByMonth['October'],
            'November' => $grandTotalByMonth['November'],
            'December' => $grandTotalByMonth['December'],
            'GrandTotalByProgram' => isset($grandTotalByProgram[$siteName][$programName]) ? $grandTotalByProgram[$siteName][$programName] : 0,
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
                $daterangeName = $dateRange->date_range;
                $programId = $program->id;
                $month = $dateRange->month;

                $classes = Classes::where('site_id', $program->site_id)
                    ->where('program_id', $programId)
                    ->where('date_range_id', $dateRange->id)
                    ->where('status', 'Moved')
                    ->get();

                $totalTarget = $classes->sum('total_target');

                if (!isset($grandTotalByMonth[$month])) {
                    $grandTotalByMonth[$month] = 0;
                }
                $grandTotalByMonth[$month] += $totalTarget;

                $grandTotalByProgram[$siteName][$programName] += $totalTarget;

                if (!isset($groupedClasses[$siteName][$programName][$month])) {
                    $groupedClasses[$siteName][$programName][$month] = [
                        'total_target' => 0,
                    ];
                }

                $groupedClasses[$siteName][$programName][$month]['total_target'] += $totalTarget;
            }
        }

        $mappedGroupedClasses = [];

        foreach ($groupedClasses as $siteName => $siteData) {
            foreach ($siteData as $programName => $programData) {
                $monthlyData = [
                    'January' => 0,
                    'February' => 0,
                    'March' => 0,
                    'April' => 0,
                    'May' => 0,
                    'June' => 0,
                    'July' => 0,
                    'August' => 0,
                    'September' => 0,
                    'October' => 0,
                    'November' => 0,
                    'December' => 0,
                ];

                foreach ($programData as $month => $monthData) {
                    $monthlyData[$month] = isset($monthData['total_target']) ? $monthData['total_target'] : 0;
                }

                $mappedGroupedClasses[] = [
                    'Site' => $siteName,
                    'Program' => $programName,
                    'January' => $monthlyData['January'],
                    'February' => $monthlyData['February'],
                    'March' => $monthlyData['March'],
                    'April' => $monthlyData['April'],
                    'May' => $monthlyData['May'],
                    'June' => $monthlyData['June'],
                    'July' => $monthlyData['July'],
                    'August' => $monthlyData['August'],
                    'September' => $monthlyData['September'],
                    'October' => $monthlyData['October'],
                    'November' => $monthlyData['November'],
                    'December' => $monthlyData['December'],
                    'GrandTotalByProgram' => isset($grandTotalByProgram[$siteName][$programName]) ? $grandTotalByProgram[$siteName][$programName] : 0,
                ];
            }
        }
        $mappedGroupedClasses[] = [
            'Site' => 'Grand Total',
            'Program' => '',
            'January' => $grandTotalByMonth['January'],
            'February' => $grandTotalByMonth['February'],
            'March' => $grandTotalByMonth['March'],
            'April' => $grandTotalByMonth['April'],
            'May' => $grandTotalByMonth['May'],
            'June' => $grandTotalByMonth['June'],
            'July' => $grandTotalByMonth['July'],
            'August' => $grandTotalByMonth['August'],
            'September' => $grandTotalByMonth['September'],
            'October' => $grandTotalByMonth['October'],
            'November' => $grandTotalByMonth['November'],
            'December' => $grandTotalByMonth['December'],
            'GrandTotalByProgram' => isset($grandTotalByProgram[$siteName][$programName]) ? $grandTotalByProgram[$siteName][$programName] : 0,
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
                $daterangeName = $dateRange->date_range;
                $programId = $program->id;
                $month = $dateRange->month;

                $classes = Classes::where('site_id', $program->site_id)
                    ->where('program_id', $programId)
                    ->where('date_range_id', $dateRange->id)
                    ->where('status', 'Active')
                    ->get();

                $totalTargetByDate = $classes->sum('total_target');

                if (!isset($grandTotalByMonth[$month])) {
                    $grandTotalByMonth[$month] = 0;
                }
                $grandTotalByMonth[$month] += $totalTargetByDate;

                $grandTotalByProgram[$siteName][$programName] += $totalTargetByDate;

                if (!isset($groupedClasses[$siteName][$programName][$month])) {
                    $groupedClasses[$siteName][$programName][$month] = [
                        'total_target' => 0,
                        'daterange_names' => [], // Added line
                    ];
                }

                $groupedClasses[$siteName][$programName][$month]['total_target'] += $totalTargetByDate;
                $groupedClasses[$siteName][$programName][$month]['daterange_names'][] = [
                    'date_range' => $daterangeName,
                    'total_target' => $totalTargetByDate,
                ]; // Adjusted line
            }
        }

        $mappedGroupedClasses = [];

        foreach ($groupedClasses as $siteName => $siteData) {
            foreach ($siteData as $programName => $programData) {
                $monthlyData = [
                    'January' => 0,
                    'February' => 0,
                    'March' => 0,
                    'April' => 0,
                    'May' => 0,
                    'June' => 0,
                    'July' => 0,
                    'August' => 0,
                    'September' => 0,
                    'October' => 0,
                    'November' => 0,
                    'December' => 0,
                ];

                foreach ($programData as $month => $monthData) {
                    $monthlyData[$month] = isset($monthData['total_target']) ? $monthData['total_target'] : 0;
                }

                $mappedGroupedClasses[] = [
                    'Site' => $siteName,
                    'Program' => $programName,
                    'January' => $monthlyData['January'],
                    'February' => $monthlyData['February'],
                    'March' => $monthlyData['March'],
                    'April' => $monthlyData['April'],
                    'May' => $monthlyData['May'],
                    'June' => $monthlyData['June'],
                    'July' => $monthlyData['July'],
                    'August' => $monthlyData['August'],
                    'September' => $monthlyData['September'],
                    'October' => $monthlyData['October'],
                    'November' => $monthlyData['November'],
                    'December' => $monthlyData['December'],
                    'GrandTotalByProgram' => $grandTotalByProgram[$siteName][$programName],
                    'DateRangeNames' => $groupedClasses[$siteName][$programName][$month]['daterange_names'], // Updated line
                ];
            }
        }

        $grandTotalForAllPrograms = array_sum(array_map('array_sum', $grandTotalByProgram));

        $mappedGroupedClasses[] = [
            'Site' => 'Grand Total',
            'Program' => '',
            'January' => $grandTotalByMonth['January'],
            'February' => $grandTotalByMonth['February'],
            'March' => $grandTotalByMonth['March'],
            'April' => $grandTotalByMonth['April'],
            'May' => $grandTotalByMonth['May'],
            'June' => $grandTotalByMonth['June'],
            'July' => $grandTotalByMonth['July'],
            'August' => $grandTotalByMonth['August'],
            'September' => $grandTotalByMonth['September'],
            'October' => $grandTotalByMonth['October'],
            'November' => $grandTotalByMonth['November'],
            'December' => $grandTotalByMonth['December'],
            'GrandTotalByProgram' => $grandTotalForAllPrograms,
        ];

        $response = [
            'classes' => $mappedGroupedClasses,
            'mapped' => $groupedClasses,
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
                $daterangeName = $dateRange->date_range;
                $programId = $program->id;
                $month = $dateRange->month;

                $classes = Classes::where('site_id', $program->site_id)
                    ->where('program_id', $programId)
                    ->where('date_range_id', $dateRange->id)
                    ->where('status', 'Cancelled')
                    ->get();

                $totalTarget = $classes->sum('total_target');

                if (!isset($grandTotalByMonth[$month])) {
                    $grandTotalByMonth[$month] = 0;
                }
                $grandTotalByMonth[$month] += $totalTarget;

                $grandTotalByProgram[$siteName][$programName] += $totalTarget;

                if (!isset($groupedClasses[$siteName][$programName][$month])) {
                    $groupedClasses[$siteName][$programName][$month] = [
                        'total_target' => 0,
                    ];
                }

                $groupedClasses[$siteName][$programName][$month]['total_target'] += $totalTarget;
            }
        }

        $mappedGroupedClasses = [];

        foreach ($groupedClasses as $siteName => $siteData) {
            foreach ($siteData as $programName => $programData) {
                $monthlyData = [
                    'January' => 0,
                    'February' => 0,
                    'March' => 0,
                    'April' => 0,
                    'May' => 0,
                    'June' => 0,
                    'July' => 0,
                    'August' => 0,
                    'September' => 0,
                    'October' => 0,
                    'November' => 0,
                    'December' => 0,
                ];

                foreach ($programData as $month => $monthData) {
                    $monthlyData[$month] = isset($monthData['total_target']) ? $monthData['total_target'] : 0;
                }

                $mappedGroupedClasses[] = [
                    'Site' => $siteName,
                    'Program' => $programName,
                    'January' => $monthlyData['January'],
                    'February' => $monthlyData['February'],
                    'March' => $monthlyData['March'],
                    'April' => $monthlyData['April'],
                    'May' => $monthlyData['May'],
                    'June' => $monthlyData['June'],
                    'July' => $monthlyData['July'],
                    'August' => $monthlyData['August'],
                    'September' => $monthlyData['September'],
                    'October' => $monthlyData['October'],
                    'November' => $monthlyData['November'],
                    'December' => $monthlyData['December'],
                    'GrandTotalByProgram' => $grandTotalByProgram[$siteName][$programName],
                ];
            }
        }

        $grandTotalForAllPrograms = array_sum(array_map('array_sum', $grandTotalByProgram));

        $mappedGroupedClasses[] = [
            'Site' => 'Grand Total',
            'Program' => '',
            'January' => $grandTotalByMonth['January'],
            'February' => $grandTotalByMonth['February'],
            'March' => $grandTotalByMonth['March'],
            'April' => $grandTotalByMonth['April'],
            'May' => $grandTotalByMonth['May'],
            'June' => $grandTotalByMonth['June'],
            'July' => $grandTotalByMonth['July'],
            'August' => $grandTotalByMonth['August'],
            'September' => $grandTotalByMonth['September'],
            'October' => $grandTotalByMonth['October'],
            'November' => $grandTotalByMonth['November'],
            'December' => $grandTotalByMonth['December'],
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
                $daterangeName = $dateRange->date_range;
                $programId = $program->id;
                $month = $dateRange->month;

                $classes = Classes::where('site_id', $program->site_id)
                    ->where('program_id', $programId)
                    ->where('date_range_id', $dateRange->id)
                    ->where('status', 'Moved')
                    ->get();

                $totalTarget = $classes->sum('total_target');

                if (!isset($grandTotalByMonth[$month])) {
                    $grandTotalByMonth[$month] = 0;
                }
                $grandTotalByMonth[$month] += $totalTarget;

                $grandTotalByProgram[$siteName][$programName] += $totalTarget;

                if (!isset($groupedClasses[$siteName][$programName][$month])) {
                    $groupedClasses[$siteName][$programName][$month] = [
                        'total_target' => 0,
                    ];
                }

                $groupedClasses[$siteName][$programName][$month]['total_target'] += $totalTarget;
            }
        }

        $mappedGroupedClasses = [];

        foreach ($groupedClasses as $siteName => $siteData) {
            foreach ($siteData as $programName => $programData) {
                $monthlyData = [
                    'January' => 0,
                    'February' => 0,
                    'March' => 0,
                    'April' => 0,
                    'May' => 0,
                    'June' => 0,
                    'July' => 0,
                    'August' => 0,
                    'September' => 0,
                    'October' => 0,
                    'November' => 0,
                    'December' => 0,
                ];

                foreach ($programData as $month => $monthData) {
                    $monthlyData[$month] = isset($monthData['total_target']) ? $monthData['total_target'] : 0;
                }

                $mappedGroupedClasses[] = [
                    'Site' => $siteName,
                    'Program' => $programName,
                    'January' => $monthlyData['January'],
                    'February' => $monthlyData['February'],
                    'March' => $monthlyData['March'],
                    'April' => $monthlyData['April'],
                    'May' => $monthlyData['May'],
                    'June' => $monthlyData['June'],
                    'July' => $monthlyData['July'],
                    'August' => $monthlyData['August'],
                    'September' => $monthlyData['September'],
                    'October' => $monthlyData['October'],
                    'November' => $monthlyData['November'],
                    'December' => $monthlyData['December'],
                    'GrandTotalByProgram' => $grandTotalByProgram[$siteName][$programName],
                ];
            }
        }

        $grandTotalForAllPrograms = array_sum(array_map('array_sum', $grandTotalByProgram));

        $mappedGroupedClasses[] = [
            'Site' => 'Grand Total',
            'Program' => '',
            'January' => $grandTotalByMonth['January'],
            'February' => $grandTotalByMonth['February'],
            'March' => $grandTotalByMonth['March'],
            'April' => $grandTotalByMonth['April'],
            'May' => $grandTotalByMonth['May'],
            'June' => $grandTotalByMonth['June'],
            'July' => $grandTotalByMonth['July'],
            'August' => $grandTotalByMonth['August'],
            'September' => $grandTotalByMonth['September'],
            'October' => $grandTotalByMonth['October'],
            'November' => $grandTotalByMonth['November'],
            'December' => $grandTotalByMonth['December'],
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
