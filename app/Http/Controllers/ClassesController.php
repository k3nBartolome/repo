<?php

namespace App\Http\Controllers;

use App\Exports\ClassHistoryExport;
use App\Exports\DashboardClassesExport;
use App\Exports\MyExport;
use App\Exports\SrExport;
use App\Http\Resources\ClassesResource;
use App\Models\Classes;
use App\Models\ClassStaffing;
use App\Models\DateRange;
use App\Models\Program;
use App\Models\Site;
use App\Models\SmartRecruitData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class ClassesController extends Controller
{
    public function srSite()
    {
        $query = SmartRecruitData::on('secondary_sqlsrv')->select('Site')->distinct()->get();

        return response()->json([
            'sites' => $query,
        ]);
    }

    public function srDate()
    {
        $minDate = SmartRecruitData::on('secondary_sqlsrv')->min('QueueDate');
        $maxDate = SmartRecruitData::on('secondary_sqlsrv')->max('QueueDate');

        $minDate = \Carbon\Carbon::parse($minDate)->format('Y-m-d');
        $maxDate = \Carbon\Carbon::parse($maxDate)->format('Y-m-d');

        return response()->json([
            'minDate' => $minDate,
            'maxDate' => $maxDate,
        ]);
    }

    public function srFilter(Request $request)
    {
        $appstepIDs = [1, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 17, 18, 19, 20, 32, 33, 34, 36, 40, 41, 42, 43, 44, 45, 46, 50, 53, 54, 55, 56, 59, 60, 69, 70, 73, 74, 78, 80, 81, 87, 88];
        $query = SmartRecruitData::on('secondary_sqlsrv')
            ->select(
                'ApplicationInfoId',
                'QueueDate',
                'LastName',
                'FirstName',
                'MiddleName',
                'Site',
                'MobileNo',
                'GenSource',
                'SpecSource',
                'Step',
                'AppStep'
            )
            ->whereNotNull('Site')
            ->whereIn('ApplicationStepStatusId', $appstepIDs);

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

                $query->where('QueueDate', '>=', $startDate)
                    ->where('QueueDate', '<', $endDate);
            }
        }

        $filteredData = $query->get();

        return response()->json([
            'sr' => $filteredData,
        ]);
    }

    public function srExport(Request $request)
    {
        $appstepIDs = [1, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 17, 18, 19, 20, 32, 33, 34, 36, 40, 41, 42, 43, 44, 45, 46, 50, 53, 54, 55, 56, 59, 60, 69, 70, 73, 74, 78, 80, 81, 87, 88];
        $query = SmartRecruitData::on('secondary_sqlsrv')
            ->select(
                'ApplicationInfoId',
                'QueueDate',
                'LastName',
                'FirstName',
                'MiddleName',
                'Site',
                'MobileNo',
                'GenSource',
                'SpecSource',
                'Step',
                'AppStep'
            )
            ->whereNotNull('Site')
            ->whereIn('ApplicationStepStatusId', $appstepIDs);

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

                $endDate = date('Y-m-d', strtotime($endDate));

                $query->where('QueueDate', '>=', $startDate)
                    ->where('QueueDate', '<=', $endDate);
            }
        }

        $filteredData = $query->get();

        $filteredDataArray = $filteredData->toArray();

        return Excel::download(new SrExport($filteredDataArray), 'filtered_sr_data.xlsx');
    }

    public function srCompliance(Request $request)
    {
        $appstepIDs = [1, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 17, 18, 19, 20, 32, 33, 34, 36, 40, 41, 42, 43, 44, 45, 46, 50, 53, 54, 55, 56, 59, 60, 69, 70, 73, 74, 78, 80, 81, 87, 88];

        $query = SmartRecruitData::on('secondary_sqlsrv')
            ->select('Step', 'AppStep', 'Site', DB::raw('COUNT(*) as Count'))
            ->groupBy('Step', 'AppStep', 'Site')
            ->orderBy('Step')
            ->orderBy('AppStep')
            ->whereIn('ApplicationStepStatusId', $appstepIDs)
            ->orderBy('Site');

        if ($request->has('filter_date_start') && $request->has('filter_date_end')) {
            $filterDateStart = $request->input('filter_date_start');
            $filterDateEnd = $request->input('filter_date_end');

            if (!empty($filterDateStart) && !empty($filterDateEnd)) {
                $startDate = date('Y-m-d', strtotime($filterDateStart));
                $endDate = date('Y-m-d', strtotime($filterDateEnd));

                $endDate = date('Y-m-d', strtotime($endDate));

                $query->where('QueueDate', '>=', $startDate)
                    ->where('QueueDate', '<=', $endDate);
            }
        }

        $result = $query->get();

        $groupedData = [];
        $totalStepCounts = [];
        $totalAppStepCounts = [];

        $totalSiteCounts = [];

        foreach ($result as $item) {
            $step = $item->Step;
            $appStep = $item->AppStep;
            $site = $item->Site;

            if (!isset($groupedData[$step][$appStep])) {
                $groupedData[$step][$appStep] = [
                    'Bridgetowne' => 0,
                    'Clark' => 0,
                    'Davao' => 0,
                    'Makati' => 0,
                    'MOA' => 0,
                    'QC North EDSA' => 0,
                ];
            }

            $groupedData[$step][$appStep][$site] += $item->Count;
            $totalStepCounts[$step] = isset($totalStepCounts[$step]) ? $totalStepCounts[$step] + $item->Count : $item->Count;
            $totalAppStepCounts[$step][$appStep] = isset($totalAppStepCounts[$step][$appStep]) ? $totalAppStepCounts[$step][$appStep] + $item->Count : $item->Count;
            $totalSiteCounts[$step][$site] = (isset($item->Count) && $item->Count > 0) ? (isset($totalSiteCounts[$step][$site]) ? $totalSiteCounts[$step][$site] + $item->Count : $item->Count) : 0;
        }

        foreach ($groupedData as $step => $appSteps) {
            $formattedTotalSiteCounts = [];
            foreach ($totalSiteCounts[$step] as $site => $count) {
                $formattedTotalSiteCounts[$site] = number_format($count);
            }

            $formattedResult[] = array_merge(
                ['Step' => $step, 'TotalCount' => number_format($totalStepCounts[$step])],
                $formattedTotalSiteCounts
            );

            foreach ($appSteps as $appStep => $siteCounts) {
                $formattedSiteCounts = [];
                foreach ($siteCounts as $site => $count) {
                    $formattedSiteCounts[$site] = number_format($count);
                }

                $formattedResult[] = array_merge(
                    ['AppStep' => $appStep, 'TotalCount' => number_format($totalAppStepCounts[$step][$appStep]), 'StepName' => $step],
                    $formattedSiteCounts
                );
            }
        }

        return response()->json(['sr' => $formattedResult]);
    }

    public function perxSite()
    {
        $query = DB::connection('secondary_sqlsrv')
            ->table('PERX_DATA')->select('Site')->whereNotNull('Site')->distinct()->get();

        return response()->json([
            'sites' => $query,
        ]);
    }

    public function perxDate()
    {
        $minDate = DB::connection('secondary_sqlsrv')
            ->table('PERX_DATA')->min('DateOfApplication');
        $maxDate = DB::connection('secondary_sqlsrv')
            ->table('PERX_DATA')->max('DateOfApplication');

        $minDate = \Carbon\Carbon::parse($minDate)->format('Y-m-d');
        $maxDate = \Carbon\Carbon::parse($maxDate)->format('Y-m-d');

        return response()->json([
            'minDate' => $minDate,
            'maxDate' => $maxDate,
        ]);
    }

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
    public function classExists(Request $request)
    {
        $query = Classes::query()
            ->where('status', 'Active')
            ->when($request->has('sites_selected'), function ($query) use ($request) {
                $query->where('site_id', $request->sites_selected);
            })
            ->when($request->has('programs_selected'), function ($query) use ($request) {
                $query->where('program_id', $request->programs_selected);
            })
            ->when($request->has('month_selected'), function ($query) use ($request) {
                $query->whereHas('dateRange', function ($dateRangeQuery) use ($request) {
                    $dateRangeQuery->where('month_num', $request->month_selected);
                });
            })
            ->when($request->has('week_selected'), function ($query) use ($request) {
                $query->whereHas('dateRange', function ($dateRangeQuery) use ($request) {
                    $dateRangeQuery->where('date_Range_id', $request->week_selected);
                });
            });

        $classExists = $query->exists();

        return response()->json(['classExists' => $classExists]);
    }

    public function classesall(Request $request)
    {
        $classes = Classes::whereHas('site', function ($query) {
            $query->where('country', '=', 'Philippines');
        })
            ->whereHas('dateRange', function ($query) {
                $query->where('year', '=', '2024');
            })
            ->with('site', 'program', 'dateRange', 'createdByUser', 'updatedByUser')
            ->where('status', 'Active')
            ->when($request->has('sites_selected') && $request->sites_selected !== null, function ($query) use ($request) {
                $query->where('site_id', $request->sites_selected);
            })
            ->when($request->has('programs_selected') && $request->programs_selected !== null, function ($query) use ($request) {
                $query->where('program_id', $request->programs_selected);
            })
            ->when($request->has('month_selected') && $request->month_selected !== null, function ($query) use ($request) {
                $query->whereHas('dateRange', function ($query) use ($request) {
                    $query->where('month_num', $request->month_selected);
                });
            })
            ->when($request->has('week_selected') && $request->week_selected !== null, function ($query) use ($request) {
                $query->whereHas('dateRange', function ($query) use ($request) {
                    $query->where('date_Range_id', $request->week_selected);
                });
            })
            ->get();

        $formattedClasses = $classes->map(function ($class) {
            return [
                'id' => $class->id,
                'country' => $class->site->country,
                'region' => $class->site->region,
                'site_name' => $class->site->name,
                'site_id' => $class->site->id,
                'program_name' => $class->program->name,
                'program_id' => $class->program->id,
                'date_range' => $class->dateRange->date_range,
                'date_range_id' => $class->dateRange->id,
                'month' => $class->dateRange->month,
                'external_target' => $class->external_target,
                'internal_target' => $class->internal_target,
                'total_target' => $class->total_target,
                'within_sla' => $class->within_sla,
                'condition' => $class->condition,
                'original_start_date' => $class->original_start_date,
                'changes' => $class->changes,
                'agreed_start_date' => $class->agreed_start_date,
                'wfm_date_requested' => $class->wfm_date_requested,
                'notice_weeks' => number_format($class->notice_weeks, 2),
                'notice_days' => $class->notice_days,
                'pipeline_utilized' => $class->pipeline_utilized,
                'remarks' => $class->remarks,
                'status' => $class->status,
                'category' => $class->category,
                'type_of_hiring' => $class->type_of_hiring,
                'with_erf' => $class->with_erf,
                'erf_number' => $class->erf_number,
                'wave_no' => $class->wave_no,
                'ta' => $class->ta,
                'wf' => $class->wf,
                'tr' => $class->tr,
                'cl' => $class->cl,
                'op' => $class->op,
                'approved_by' => $class->approved_by,
                'cancelled_by' => $class->cancelledByUser ? $class->cancelledByUser->name : null,
                'requested_by' => $class->requested_by,
                'created_by' => $class->createdByUser->name,
                'updated_by' => $class->updatedByUser ? $class->updatedByUser->name : null,
                'approved_date' => $class->approved_date,
                'cancelled_date' => $class->cancelled_date,
                'created_at' => $class->created_at ? $class->created_at->format('m-d-Y H:i') : null,
                'updated_at' => $class->updated_at ? $class->updated_at->format('m-d-Y H:i') : null,
            ];
        });

        return response()->json([
            'classes' => $formattedClasses,
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
            ->when(true, function ($query) {
                $query->whereHas('site', function ($subquery) {
                    $subquery->where('is_active', 1);
                });

                $query->whereHas('site', function ($subquery) {
                    $subquery->where('country', 'Philippines');
                });
            })

            ->where('is_active', 1) // You can keep or remove this line based on your requirements
            ->get();

        $year = 2024;
        $dateRanges = DateRange::where('year', $year)
            ->get();

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
                    '53' => 0,
                    '54' => 0,
                    '55' => 0,
                    '56' => 0,
                    '57' => 0,
                    '58' => 0,
                    '59' => 0,
                    '60' => 0,
                    '61' => 0,
                    '62' => 0,
                    '63' => 0,
                    '64' => 0,
                    '65' => 0,
                    '66' => 0,
                    '67' => 0,
                    '68' => 0,
                    '69' => 0,
                    '70' => 0,
                    '71' => 0,
                    '72' => 0,
                    '73' => 0,
                    '74' => 0,
                    '75' => 0,
                    '76' => 0,
                    '77' => 0,
                    '78' => 0,
                    '79' => 0,
                    '80' => 0,
                    '81' => 0,
                    '82' => 0,
                    '83' => 0,
                    '84' => 0,
                    '85' => 0,
                    '86' => 0,
                    '87' => 0,
                    '88' => 0,
                    '89' => 0,
                    '90' => 0,
                    '91' => 0,
                    '92' => 0,
                    '93' => 0,
                    '94' => 0,
                    '95' => 0,
                    '96' => 0,
                    '97' => 0,
                    '98' => 0,
                    '99' => 0,
                    '100' => 0,
                    '101' => 0,
                    '102' => 0,
                    '103' => 0,
                    '104' => 0,
                ];

                foreach ($programData as $week => $weekData) {
                    $weeklyData[$week] = isset($weekData['total_target']) ? $weekData['total_target'] : 0;
                }
                $grandTotal = $grandTotalByProgram[$siteName][$programName];
                if ($grandTotal != 0) {
                    $mappedGroupedClasses[] = [
                        'Site' => $siteName,
                        'Program' => $programName,
                        'Week1' => $weeklyData['53'] != 0 ? $weeklyData['53'] : '',
                        'Week2' => $weeklyData['54'] != 0 ? $weeklyData['54'] : '',
                        'Week3' => $weeklyData['55'] != 0 ? $weeklyData['55'] : '',
                        'Week4' => $weeklyData['56'] != 0 ? $weeklyData['56'] : '',
                        'Jan' => collect($weeklyData)->only([53, 54, 55, 56])->sum() != 0 ? collect($weeklyData)->only([53, 54, 55, 56])->sum() : '',
                        'Week5' => $weeklyData['57'] != 0 ? $weeklyData['57'] : '',
                        'Week6' => $weeklyData['58'] != 0 ? $weeklyData['58'] : '',
                        'Week7' => $weeklyData['59'] != 0 ? $weeklyData['59'] : '',
                        'Week8' => $weeklyData['60'] != 0 ? $weeklyData['60'] : '',
                        'Feb' => collect($weeklyData)->only([57, 58, 59, 60])->sum() != 0 ? collect($weeklyData)->only([57, 58, 59, 60])->sum() : '',
                        'Week9' => $weeklyData['61'] != 0 ? $weeklyData['61'] : '',
                        'Week10' => $weeklyData['62'] != 0 ? $weeklyData['62'] : '',
                        'Week11' => $weeklyData['63'] != 0 ? $weeklyData['63'] : '',
                        'Week12' => $weeklyData['64'] != 0 ? $weeklyData['64'] : '',
                        'Week13' => $weeklyData['65'] != 0 ? $weeklyData['65'] : '',
                        'Mar' => collect($weeklyData)->only([61, 62, 63, 64, 65])->sum() != 0 ? collect($weeklyData)->only([61, 62, 63, 64, 65])->sum() : '',
                        'Week14' => $weeklyData['66'] != 0 ? $weeklyData['66'] : '',
                        'Week15' => $weeklyData['67'] != 0 ? $weeklyData['67'] : '',
                        'Week16' => $weeklyData['68'] != 0 ? $weeklyData['68'] : '',
                        'Week17' => $weeklyData['69'] != 0 ? $weeklyData['69'] : '',
                        'Apr' => collect($weeklyData)->only([66, 67, 68, 69])->sum() != 0 ? collect($weeklyData)->only([66, 67, 68, 69])->sum() : '',
                        'Week18' => $weeklyData['70'] != 0 ? $weeklyData['70'] : '',
                        'Week19' => $weeklyData['71'] != 0 ? $weeklyData['71'] : '',
                        'Week20' => $weeklyData['72'] != 0 ? $weeklyData['72'] : '',
                        'Week21' => $weeklyData['73'] != 0 ? $weeklyData['73'] : '',
                        'May' => collect($weeklyData)->only([70, 71, 72, 73])->sum() != 0 ? collect($weeklyData)->only([70, 71, 72, 73])->sum() : '',
                        'Week22' => $weeklyData['74'] != 0 ? $weeklyData['74'] : '',
                        'Week23' => $weeklyData['75'] != 0 ? $weeklyData['75'] : '',
                        'Week24' => $weeklyData['76'] != 0 ? $weeklyData['76'] : '',
                        'Week25' => $weeklyData['77'] != 0 ? $weeklyData['77'] : '',
                        'Week26' => $weeklyData['78'] != 0 ? $weeklyData['78'] : '',
                        'Jun' => collect($weeklyData)->only([74, 75, 76, 77, 78])->sum() != 0 ? collect($weeklyData)->only([74, 75, 76, 77, 78])->sum() : '',
                        'Week27' => $weeklyData['79'] != 0 ? $weeklyData['79'] : '',
                        'Week28' => $weeklyData['80'] != 0 ? $weeklyData['80'] : '',
                        'Week29' => $weeklyData['81'] != 0 ? $weeklyData['81'] : '',
                        'Week30' => $weeklyData['82'] != 0 ? $weeklyData['82'] : '',
                        'Jul' => collect($weeklyData)->only([79, 80, 81, 82])->sum() != 0 ? collect($weeklyData)->only([79, 80, 81, 82])->sum() : '',
                        'Week31' => $weeklyData['83'] != 0 ? $weeklyData['83'] : '',
                        'Week32' => $weeklyData['84'] != 0 ? $weeklyData['84'] : '',
                        'Week33' => $weeklyData['85'] != 0 ? $weeklyData['85'] : '',
                        'Week34' => $weeklyData['86'] != 0 ? $weeklyData['86'] : '',
                        'Week35' => $weeklyData['87'] != 0 ? $weeklyData['87'] : '',
                        'Aug' => collect($weeklyData)->only([83, 84, 85, 86, 87])->sum() != 0 ? collect($weeklyData)->only([83, 84, 85, 86, 87])->sum() : '',
                        'Week36' => $weeklyData['88'] != 0 ? $weeklyData['88'] : '',
                        'Week37' => $weeklyData['89'] != 0 ? $weeklyData['89'] : '',
                        'Week38' => $weeklyData['90'] != 0 ? $weeklyData['90'] : '',
                        'Week39' => $weeklyData['91'] != 0 ? $weeklyData['91'] : '',
                        'Sep' => collect($weeklyData)->only([88, 89, 90, 91])->sum() != 0 ? collect($weeklyData)->only([88, 89, 90, 91])->sum() : '',
                        'Week40' => $weeklyData['92'] != 0 ? $weeklyData['92'] : '',
                        'Week41' => $weeklyData['93'] != 0 ? $weeklyData['93'] : '',
                        'Week42' => $weeklyData['94'] != 0 ? $weeklyData['94'] : '',
                        'Week43' => $weeklyData['95'] != 0 ? $weeklyData['95'] : '',
                        'Oct' => collect($weeklyData)->only([92, 93, 94, 95])->sum() != 0 ? collect($weeklyData)->only([92, 93, 94, 95])->sum() : '',
                        'Week44' => $weeklyData['96'] != 0 ? $weeklyData['96'] : '',
                        'Week45' => $weeklyData['97'] != 0 ? $weeklyData['97'] : '',
                        'Week46' => $weeklyData['98'] != 0 ? $weeklyData['98'] : '',
                        'Week47' => $weeklyData['99'] != 0 ? $weeklyData['99'] : '',
                        'Week48' => $weeklyData['100'] != 0 ? $weeklyData['100'] : '',
                        'Nov' => collect($weeklyData)->only([96, 97, 98, 99, 100])->sum() != 0 ? collect($weeklyData)->only([96, 97, 98, 99, 100])->sum() : '',
                        'Week49' => $weeklyData['101'] != 0 ? $weeklyData['101'] : '',
                        'Week50' => $weeklyData['102'] != 0 ? $weeklyData['102'] : '',
                        'Week51' => $weeklyData['103'] != 0 ? $weeklyData['103'] : '',
                        'Week52' => $weeklyData['104'] != 0 ? $weeklyData['104'] : '',
                        'Dec' => collect($weeklyData)->only([101, 102, 103, 104])->sum() != 0 ? collect($weeklyData)->only([101, 102, 103, 104])->sum() : '',

                        'GrandTotalByProgram' => $grandTotal,
                    ];
                }
            }
        }

        $grandTotalForAllPrograms = array_sum(array_map('array_sum', $grandTotalByProgram));

        $mappedGroupedClasses[] = [
            'Site' => 'Grand Total',
            'Program' => '',
            'Week1' => $grandTotalByWeek['53'] != 0 ? $grandTotalByWeek['53'] : '',
            'Week2' => $grandTotalByWeek['54'] != 0 ? $grandTotalByWeek['54'] : '',
            'Week3' => $grandTotalByWeek['55'] != 0 ? $grandTotalByWeek['55'] : '',
            'Week4' => $grandTotalByWeek['56'] != 0 ? $grandTotalByWeek['56'] : '',
            'Jan' => collect($grandTotalByWeek)->only([53, 54, 55, 56])->sum() != 0 ? collect($grandTotalByWeek)->only([53, 54, 55, 56])->sum() : '',
            'Week5' => $grandTotalByWeek['57'] != 0 ? $grandTotalByWeek['57'] : '',
            'Week6' => $grandTotalByWeek['58'] != 0 ? $grandTotalByWeek['58'] : '',
            'Week7' => $grandTotalByWeek['59'] != 0 ? $grandTotalByWeek['59'] : '',
            'Week8' => $grandTotalByWeek['60'] != 0 ? $grandTotalByWeek['60'] : '',
            'Feb' => collect($grandTotalByWeek)->only([57, 58, 59, 60])->sum() != 0 ? collect($grandTotalByWeek)->only([57, 58, 59, 60])->sum() : '',
            'Week9' => $grandTotalByWeek['61'] != 0 ? $grandTotalByWeek['61'] : '',
            'Week10' => $grandTotalByWeek['62'] != 0 ? $grandTotalByWeek['62'] : '',
            'Week11' => $grandTotalByWeek['63'] != 0 ? $grandTotalByWeek['63'] : '',
            'Week12' => $grandTotalByWeek['64'] != 0 ? $grandTotalByWeek['64'] : '',
            'Week13' => $grandTotalByWeek['65'] != 0 ? $grandTotalByWeek['65'] : '',
            'Mar' => collect($grandTotalByWeek)->only([61, 62, 63, 64, 65])->sum() != 0 ? collect($grandTotalByWeek)->only([61, 62, 63, 64, 65])->sum() : '',
            'Week14' => $grandTotalByWeek['66'] != 0 ? $grandTotalByWeek['66'] : '',
            'Week15' => $grandTotalByWeek['67'] != 0 ? $grandTotalByWeek['67'] : '',
            'Week16' => $grandTotalByWeek['68'] != 0 ? $grandTotalByWeek['68'] : '',
            'Week17' => $grandTotalByWeek['69'] != 0 ? $grandTotalByWeek['69'] : '',
            'Apr' => collect($grandTotalByWeek)->only([66, 67, 68, 69])->sum() != 0 ? collect($grandTotalByWeek)->only([66, 67, 68, 69])->sum() : '',
            'Week18' => $grandTotalByWeek['70'] != 0 ? $grandTotalByWeek['70'] : '',
            'Week19' => $grandTotalByWeek['71'] != 0 ? $grandTotalByWeek['71'] : '',
            'Week20' => $grandTotalByWeek['72'] != 0 ? $grandTotalByWeek['72'] : '',
            'Week21' => $grandTotalByWeek['73'] != 0 ? $grandTotalByWeek['73'] : '',
            'May' => collect($grandTotalByWeek)->only([70, 71, 72, 73])->sum() != 0 ? collect($grandTotalByWeek)->only([70, 71, 72, 73])->sum() : '',
            'Week22' => $grandTotalByWeek['74'] != 0 ? $grandTotalByWeek['74'] : '',
            'Week23' => $grandTotalByWeek['75'] != 0 ? $grandTotalByWeek['75'] : '',
            'Week24' => $grandTotalByWeek['76'] != 0 ? $grandTotalByWeek['76'] : '',
            'Week25' => $grandTotalByWeek['77'] != 0 ? $grandTotalByWeek['77'] : '',
            'Week26' => $grandTotalByWeek['78'] != 0 ? $grandTotalByWeek['78'] : '',
            'Jun' => collect($grandTotalByWeek)->only([74, 75, 76, 77, 78])->sum() != 0 ? collect($grandTotalByWeek)->only([74, 75, 76, 77, 78])->sum() : '',
            'Week27' => $grandTotalByWeek['79'] != 0 ? $grandTotalByWeek['79'] : '',
            'Week28' => $grandTotalByWeek['80'] != 0 ? $grandTotalByWeek['80'] : '',
            'Week29' => $grandTotalByWeek['81'] != 0 ? $grandTotalByWeek['81'] : '',
            'Week30' => $grandTotalByWeek['82'] != 0 ? $grandTotalByWeek['82'] : '',
            'Jul' => collect($grandTotalByWeek)->only([79, 80, 81, 82])->sum() != 0 ? collect($grandTotalByWeek)->only([79, 80, 81, 82])->sum() : '',
            'Week31' => $grandTotalByWeek['83'] != 0 ? $grandTotalByWeek['83'] : '',
            'Week32' => $grandTotalByWeek['84'] != 0 ? $grandTotalByWeek['84'] : '',
            'Week33' => $grandTotalByWeek['85'] != 0 ? $grandTotalByWeek['85'] : '',
            'Week34' => $grandTotalByWeek['86'] != 0 ? $grandTotalByWeek['86'] : '',
            'Week35' => $grandTotalByWeek['87'] != 0 ? $grandTotalByWeek['87'] : '',
            'Aug' => collect($grandTotalByWeek)->only([83, 84, 85, 86, 87])->sum() != 0 ? collect($grandTotalByWeek)->only([83, 84, 85, 86, 87])->sum() : '',
            'Week36' => $grandTotalByWeek['88'] != 0 ? $grandTotalByWeek['88'] : '',
            'Week37' => $grandTotalByWeek['89'] != 0 ? $grandTotalByWeek['89'] : '',
            'Week38' => $grandTotalByWeek['90'] != 0 ? $grandTotalByWeek['90'] : '',
            'Week39' => $grandTotalByWeek['91'] != 0 ? $grandTotalByWeek['91'] : '',
            'Sep' => collect($grandTotalByWeek)->only([88, 89, 90, 91])->sum() != 0 ? collect($grandTotalByWeek)->only([88, 89, 90, 91])->sum() : '',
            'Week40' => $grandTotalByWeek['92'] != 0 ? $grandTotalByWeek['92'] : '',
            'Week41' => $grandTotalByWeek['93'] != 0 ? $grandTotalByWeek['93'] : '',
            'Week42' => $grandTotalByWeek['94'] != 0 ? $grandTotalByWeek['94'] : '',
            'Week43' => $grandTotalByWeek['95'] != 0 ? $grandTotalByWeek['95'] : '',
            'Oct' => collect($grandTotalByWeek)->only([92, 93, 94, 95])->sum() != 0 ? collect($grandTotalByWeek)->only([92, 93, 94, 95])->sum() : '',
            'Week44' => $grandTotalByWeek['96'] != 0 ? $grandTotalByWeek['96'] : '',
            'Week45' => $grandTotalByWeek['97'] != 0 ? $grandTotalByWeek['97'] : '',
            'Week46' => $grandTotalByWeek['98'] != 0 ? $grandTotalByWeek['98'] : '',
            'Week47' => $grandTotalByWeek['99'] != 0 ? $grandTotalByWeek['99'] : '',
            'Week48' => $grandTotalByWeek['100'] != 0 ? $grandTotalByWeek['100'] : '',
            'Nov' => collect($grandTotalByWeek)->only([96, 97, 98, 99, 100])->sum() != 0 ? collect($grandTotalByWeek)->only([96, 97, 98, 99, 100])->sum() : '',
            'Week49' => $grandTotalByWeek['101'] != 0 ? $grandTotalByWeek['101'] : '',
            'Week50' => $grandTotalByWeek['102'] != 0 ? $grandTotalByWeek['102'] : '',
            'Week51' => $grandTotalByWeek['103'] != 0 ? $grandTotalByWeek['103'] : '',
            'Week52' => $grandTotalByWeek['104'] != 0 ? $grandTotalByWeek['104'] : '',
            'Dec' => collect($grandTotalByWeek)->only([101, 102, 103, 104])->sum() != 0 ? collect($grandTotalByWeek)->only([101, 102, 103, 104])->sum() : '',
            'GrandTotalByProgram' => $grandTotalForAllPrograms,
        ];

        $response = [
            'classes' => $mappedGroupedClasses,
        ];

        return Excel::download(new DashboardClassesExport($response), 'filtered_data.xlsx');
    }
    public function dashboardClassesExport2(Request $request)
    {
        $query = Classes::with(['site', 'program', 'dateRange', 'createdByUser', 'updatedByUser', 'cancelledByUser'])
            ->whereHas('site', function ($query) use ($request) {
                $query->where('country', '=', 'Philippines')
                    ->where('is_active', '=', 1);
            })
            ->whereHas('program', function ($query) use ($request) {
                $query->where('is_active', '=', 1);
            })
            ->whereHas('dateRange', function ($query) use ($request) {
                $query->where('year', '=', '2024');
            });

        if ($request->has('sites_selected')) {
            $sitesSelected = $request->input('sites_selected');

            if (!empty($sitesSelected)) {
                $query->where('site_id', $sitesSelected);
            }
        }

        if ($request->has('programs_selected')) {
            $programsSelected = $request->input('programs_selected');

            if (!empty($programsSelected)) {
                $query->where('program_id', $programsSelected);
            }
        }

        if ($request->has('month_selected')) {
            $monthSelected = $request->input('month_selected');

            if (!empty($monthSelected)) {
                $query->whereHas('dateRange', function ($query) use ($monthSelected) {
                    $query->where('month_num', $monthSelected);
                });
            }
        }

        if ($request->has('week_selected')) {
            $weekSelected = $request->input('week_selected');

            if (!empty($weekSelected)) {
                $query->whereHas('dateRange', function ($query) use ($weekSelected) {
                    $query->where('date_Range_id', $weekSelected);
                });
            }
        }

        $classes = $query->select([
            'id',
            'pushedback_id',
            'within_sla',
            'condition',
            'requested_by',
            'original_start_date',
            'changes',
            'agreed_start_date',
            'approved_date',
            'cancelled_date',
            'wfm_date_requested',
            'notice_weeks',
            'external_target',
            'internal_target',
            'notice_days',
            'pipeline_utilized',
            'total_target',
            'remarks',
            'status',
            'category',
            'type_of_hiring',
            'update_status',
            'approved_status',
            'with_erf',
            'erf_number',
            'approved_by',
            'cancelled_by',
            'ta',
            'wave_no',
            'wf',
            'tr',
            'cl',
            'op',
            'created_by',
            'site_id',
            'program_id',
            'updated_by',
            'date_range_id',
            'created_at',
            'updated_at',
        ])
            ->get();

        $formattedClasses = $classes->map(function ($class) {
            return [
                'id' => $class->id,
                'country' => $class->site->country,
                'region' => $class->site->region,
                'site_name' => $class->site->name,
                'program_name' => $class->program->name,
                'date_range' => $class->dateRange->date_range,
                'external_target' => $class->external_target,
                'internal_target' => $class->internal_target,
                'total_target' => $class->total_target,
                'within_sla' => $class->within_sla,
                'condition' => $class->condition,
                'original_start_date' => $class->original_start_date,
                'changes' => $class->changes,
                'agreed_start_date' => $class->agreed_start_date,
                'wfm_date_requested' => $class->wfm_date_requested,
                'notice_weeks' => $class->notice_weeks,
                'notice_days' => $class->notice_days,
                'pipeline_utilized' => $class->pipeline_utilized,
                'remarks' => $class->remarks,
                'status' => $class->status,
                'category' => $class->category,
                'type_of_hiring' => $class->type_of_hiring,
                'with_erf' => $class->with_erf,
                'erf_number' => $class->erf_number,
                'wave_no' => $class->wave_no,
                'ta' => $class->ta,
                'wf' => $class->wf,
                'tr' => $class->tr,
                'cl' => $class->cl,
                'op' => $class->op,
                'approved_by' => $class->approved_by,
                'cancelled_by' => $class->cancelledByUser ? $class->cancelledByUser->name : null,
                'requested_by' => $class->requested_by,
                'created_by' => $class->createdByUser->name,
                'updated_by' => $class->updatedByUser ? $class->updatedByUser->name : null,
                'approved_date' => $class->approved_date,
                'cancelled_date' => $class->cancelled_date,
                'created_at' => $class->created_at->format('m-d-Y H:i'),
                'updated_at' => $class->updated_at->format('m-d-Y H:i'),
            ];
        })->toArray();

        return Excel::download(new ClassHistoryExport($formattedClasses), 'Class_history_data.xlsx');
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
            ->when(true, function ($query) {
                $query->whereHas('site', function ($subquery) {
                    $subquery->where('is_active', 1);
                });

                $query->whereHas('site', function ($subquery) {
                    $subquery->where('country', 'Philippines');
                });
            })

            ->where('is_active', 1) // You can keep or remove this line based on your requirements
            ->get();

        $year = 2024;
        $dateRanges = DateRange::where('year', $year)
            ->get();

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
                    '53' => 0,
                    '54' => 0,
                    '55' => 0,
                    '56' => 0,
                    '57' => 0,
                    '58' => 0,
                    '59' => 0,
                    '60' => 0,
                    '61' => 0,
                    '62' => 0,
                    '63' => 0,
                    '64' => 0,
                    '65' => 0,
                    '66' => 0,
                    '67' => 0,
                    '68' => 0,
                    '69' => 0,
                    '70' => 0,
                    '71' => 0,
                    '72' => 0,
                    '73' => 0,
                    '74' => 0,
                    '75' => 0,
                    '76' => 0,
                    '77' => 0,
                    '78' => 0,
                    '79' => 0,
                    '80' => 0,
                    '81' => 0,
                    '82' => 0,
                    '83' => 0,
                    '84' => 0,
                    '85' => 0,
                    '86' => 0,
                    '87' => 0,
                    '88' => 0,
                    '89' => 0,
                    '90' => 0,
                    '91' => 0,
                    '92' => 0,
                    '93' => 0,
                    '94' => 0,
                    '95' => 0,
                    '96' => 0,
                    '97' => 0,
                    '98' => 0,
                    '99' => 0,
                    '100' => 0,
                    '101' => 0,
                    '102' => 0,
                    '103' => 0,
                    '104' => 0,
                ];

                foreach ($programData as $week => $weekData) {
                    $weeklyData[$week] = isset($weekData['total_target']) ? $weekData['total_target'] : 0;
                }
                $grandTotal = $grandTotalByProgram[$siteName][$programName];
                if ($grandTotal != 0) {
                    $mappedGroupedClasses[] = [
                        'Site' => $siteName,
                        'Program' => $programName,
                        'Week1' => $weeklyData['53'] != 0 ? $weeklyData['53'] : '',

                        'Week2' => $weeklyData['54'] != 0 ? $weeklyData['54'] : '',
                        'Week3' => $weeklyData['55'] != 0 ? $weeklyData['55'] : '',
                        'Week4' => $weeklyData['56'] != 0 ? $weeklyData['56'] : '',
                        'Jan' => collect($weeklyData)->only([53, 54, 55, 56])->sum() != 0 ? collect($weeklyData)->only([53, 54, 55, 56])->sum() : '',
                        'Week5' => $weeklyData['57'] != 0 ? $weeklyData['57'] : '',
                        'Week6' => $weeklyData['58'] != 0 ? $weeklyData['58'] : '',
                        'Week7' => $weeklyData['59'] != 0 ? $weeklyData['59'] : '',
                        'Week8' => $weeklyData['60'] != 0 ? $weeklyData['60'] : '',
                        'Feb' => collect($weeklyData)->only([57, 58, 59, 60])->sum() != 0 ? collect($weeklyData)->only([57, 58, 59, 60])->sum() : '',
                        'Week9' => $weeklyData['61'] != 0 ? $weeklyData['61'] : '',
                        'Week10' => $weeklyData['62'] != 0 ? $weeklyData['62'] : '',
                        'Week11' => $weeklyData['63'] != 0 ? $weeklyData['63'] : '',
                        'Week12' => $weeklyData['64'] != 0 ? $weeklyData['64'] : '',
                        'Week13' => $weeklyData['65'] != 0 ? $weeklyData['65'] : '',
                        'Mar' => collect($weeklyData)->only([61, 62, 63, 64, 65])->sum() != 0 ? collect($weeklyData)->only([61, 62, 63, 64, 65])->sum() : '',
                        'Week14' => $weeklyData['66'] != 0 ? $weeklyData['66'] : '',
                        'Week15' => $weeklyData['67'] != 0 ? $weeklyData['67'] : '',
                        'Week16' => $weeklyData['68'] != 0 ? $weeklyData['68'] : '',
                        'Week17' => $weeklyData['69'] != 0 ? $weeklyData['69'] : '',
                        'Apr' => collect($weeklyData)->only([66, 67, 68, 69])->sum() != 0 ? collect($weeklyData)->only([66, 67, 68, 69])->sum() : '',
                        'Week18' => $weeklyData['70'] != 0 ? $weeklyData['70'] : '',
                        'Week19' => $weeklyData['71'] != 0 ? $weeklyData['71'] : '',
                        'Week20' => $weeklyData['72'] != 0 ? $weeklyData['72'] : '',
                        'Week21' => $weeklyData['73'] != 0 ? $weeklyData['73'] : '',
                        'May' => collect($weeklyData)->only([70, 71, 72, 73])->sum() != 0 ? collect($weeklyData)->only([70, 71, 72, 73])->sum() : '',
                        'Week22' => $weeklyData['74'] != 0 ? $weeklyData['74'] : '',
                        'Week23' => $weeklyData['75'] != 0 ? $weeklyData['75'] : '',
                        'Week24' => $weeklyData['76'] != 0 ? $weeklyData['76'] : '',
                        'Week25' => $weeklyData['77'] != 0 ? $weeklyData['77'] : '',
                        'Week26' => $weeklyData['78'] != 0 ? $weeklyData['78'] : '',
                        'Jun' => collect($weeklyData)->only([74, 75, 76, 77, 78])->sum() != 0 ? collect($weeklyData)->only([74, 75, 76, 77, 78])->sum() : '',
                        'Week27' => $weeklyData['79'] != 0 ? $weeklyData['79'] : '',
                        'Week28' => $weeklyData['80'] != 0 ? $weeklyData['80'] : '',
                        'Week29' => $weeklyData['81'] != 0 ? $weeklyData['81'] : '',
                        'Week30' => $weeklyData['82'] != 0 ? $weeklyData['82'] : '',
                        'Jul' => collect($weeklyData)->only([79, 80, 81, 82])->sum() != 0 ? collect($weeklyData)->only([79, 80, 81, 82])->sum() : '',
                        'Week31' => $weeklyData['83'] != 0 ? $weeklyData['83'] : '',
                        'Week32' => $weeklyData['84'] != 0 ? $weeklyData['84'] : '',
                        'Week33' => $weeklyData['85'] != 0 ? $weeklyData['85'] : '',
                        'Week34' => $weeklyData['86'] != 0 ? $weeklyData['86'] : '',
                        'Week35' => $weeklyData['87'] != 0 ? $weeklyData['87'] : '',
                        'Aug' => collect($weeklyData)->only([83, 84, 85, 86, 87])->sum() != 0 ? collect($weeklyData)->only([83, 84, 85, 86, 87])->sum() : '',
                        'Week36' => $weeklyData['88'] != 0 ? $weeklyData['88'] : '',
                        'Week37' => $weeklyData['89'] != 0 ? $weeklyData['89'] : '',
                        'Week38' => $weeklyData['90'] != 0 ? $weeklyData['90'] : '',
                        'Week39' => $weeklyData['91'] != 0 ? $weeklyData['91'] : '',
                        'Sep' => collect($weeklyData)->only([88, 89, 90, 91])->sum() != 0 ? collect($weeklyData)->only([88, 89, 90, 91])->sum() : '',
                        'Week40' => $weeklyData['92'] != 0 ? $weeklyData['92'] : '',
                        'Week41' => $weeklyData['93'] != 0 ? $weeklyData['93'] : '',
                        'Week42' => $weeklyData['94'] != 0 ? $weeklyData['94'] : '',
                        'Week43' => $weeklyData['95'] != 0 ? $weeklyData['95'] : '',
                        'Oct' => collect($weeklyData)->only([92, 93, 94, 95])->sum() != 0 ? collect($weeklyData)->only([92, 93, 94, 95])->sum() : '',
                        'Week44' => $weeklyData['96'] != 0 ? $weeklyData['96'] : '',
                        'Week45' => $weeklyData['97'] != 0 ? $weeklyData['97'] : '',
                        'Week46' => $weeklyData['98'] != 0 ? $weeklyData['98'] : '',
                        'Week47' => $weeklyData['99'] != 0 ? $weeklyData['99'] : '',
                        'Week48' => $weeklyData['100'] != 0 ? $weeklyData['100'] : '',
                        'Nov' => collect($weeklyData)->only([96, 97, 98, 99, 100])->sum() != 0 ? collect($weeklyData)->only([96, 97, 98, 99, 100])->sum() : '',
                        'Week49' => $weeklyData['101'] != 0 ? $weeklyData['101'] : '',
                        'Week50' => $weeklyData['102'] != 0 ? $weeklyData['102'] : '',
                        'Week51' => $weeklyData['103'] != 0 ? $weeklyData['103'] : '',
                        'Week52' => $weeklyData['104'] != 0 ? $weeklyData['104'] : '',
                        'Dec' => collect($weeklyData)->only([101, 102, 103, 104])->sum() != 0 ? collect($weeklyData)->only([101, 102, 103, 104])->sum() : '',

                        'GrandTotalByProgram' => $grandTotal,
                    ];
                }
            }
        }

        $grandTotalForAllPrograms = array_sum(array_map('array_sum', $grandTotalByProgram));

        $mappedGroupedClasses[] = [
            'Site' => 'Grand Total',
            'Program' => '',
            'Week1' => $grandTotalByWeek['53'] != 0 ? $grandTotalByWeek['53'] : '',
            'Week2' => $grandTotalByWeek['54'] != 0 ? $grandTotalByWeek['54'] : '',
            'Week3' => $grandTotalByWeek['55'] != 0 ? $grandTotalByWeek['55'] : '',
            'Week4' => $grandTotalByWeek['56'] != 0 ? $grandTotalByWeek['56'] : '',
            'Jan' => collect($grandTotalByWeek)->only([53, 54, 55, 56])->sum() != 0 ? collect($grandTotalByWeek)->only([53, 54, 55, 56])->sum() : '',
            'Week5' => $grandTotalByWeek['57'] != 0 ? $grandTotalByWeek['57'] : '',
            'Week6' => $grandTotalByWeek['58'] != 0 ? $grandTotalByWeek['58'] : '',
            'Week7' => $grandTotalByWeek['59'] != 0 ? $grandTotalByWeek['59'] : '',
            'Week8' => $grandTotalByWeek['60'] != 0 ? $grandTotalByWeek['60'] : '',
            'Feb' => collect($grandTotalByWeek)->only([57, 58, 59, 60])->sum() != 0 ? collect($grandTotalByWeek)->only([57, 58, 59, 60])->sum() : '',
            'Week9' => $grandTotalByWeek['61'] != 0 ? $grandTotalByWeek['61'] : '',
            'Week10' => $grandTotalByWeek['62'] != 0 ? $grandTotalByWeek['62'] : '',
            'Week11' => $grandTotalByWeek['63'] != 0 ? $grandTotalByWeek['63'] : '',
            'Week12' => $grandTotalByWeek['64'] != 0 ? $grandTotalByWeek['64'] : '',
            'Week13' => $grandTotalByWeek['65'] != 0 ? $grandTotalByWeek['65'] : '',
            'Mar' => collect($grandTotalByWeek)->only([61, 62, 63, 64, 65])->sum() != 0 ? collect($grandTotalByWeek)->only([61, 62, 63, 64, 65])->sum() : '',
            'Week14' => $grandTotalByWeek['66'] != 0 ? $grandTotalByWeek['66'] : '',
            'Week15' => $grandTotalByWeek['67'] != 0 ? $grandTotalByWeek['67'] : '',
            'Week16' => $grandTotalByWeek['68'] != 0 ? $grandTotalByWeek['68'] : '',
            'Week17' => $grandTotalByWeek['69'] != 0 ? $grandTotalByWeek['69'] : '',
            'Apr' => collect($grandTotalByWeek)->only([66, 67, 68, 69])->sum() != 0 ? collect($grandTotalByWeek)->only([66, 67, 68, 69])->sum() : '',
            'Week18' => $grandTotalByWeek['70'] != 0 ? $grandTotalByWeek['70'] : '',
            'Week19' => $grandTotalByWeek['71'] != 0 ? $grandTotalByWeek['71'] : '',
            'Week20' => $grandTotalByWeek['72'] != 0 ? $grandTotalByWeek['72'] : '',
            'Week21' => $grandTotalByWeek['73'] != 0 ? $grandTotalByWeek['73'] : '',
            'May' => collect($grandTotalByWeek)->only([70, 71, 72, 73])->sum() != 0 ? collect($grandTotalByWeek)->only([70, 71, 72, 73])->sum() : '',
            'Week22' => $grandTotalByWeek['74'] != 0 ? $grandTotalByWeek['74'] : '',
            'Week23' => $grandTotalByWeek['75'] != 0 ? $grandTotalByWeek['75'] : '',
            'Week24' => $grandTotalByWeek['76'] != 0 ? $grandTotalByWeek['76'] : '',
            'Week25' => $grandTotalByWeek['77'] != 0 ? $grandTotalByWeek['77'] : '',
            'Week26' => $grandTotalByWeek['78'] != 0 ? $grandTotalByWeek['78'] : '',
            'Jun' => collect($grandTotalByWeek)->only([74, 75, 76, 77, 78])->sum() != 0 ? collect($grandTotalByWeek)->only([74, 75, 76, 77, 78])->sum() : '',
            'Week27' => $grandTotalByWeek['79'] != 0 ? $grandTotalByWeek['79'] : '',
            'Week28' => $grandTotalByWeek['80'] != 0 ? $grandTotalByWeek['80'] : '',
            'Week29' => $grandTotalByWeek['81'] != 0 ? $grandTotalByWeek['81'] : '',
            'Week30' => $grandTotalByWeek['82'] != 0 ? $grandTotalByWeek['82'] : '',
            'Jul' => collect($grandTotalByWeek)->only([79, 80, 81, 82])->sum() != 0 ? collect($grandTotalByWeek)->only([79, 80, 81, 82])->sum() : '',
            'Week31' => $grandTotalByWeek['83'] != 0 ? $grandTotalByWeek['83'] : '',
            'Week32' => $grandTotalByWeek['84'] != 0 ? $grandTotalByWeek['84'] : '',
            'Week33' => $grandTotalByWeek['85'] != 0 ? $grandTotalByWeek['85'] : '',
            'Week34' => $grandTotalByWeek['86'] != 0 ? $grandTotalByWeek['86'] : '',
            'Week35' => $grandTotalByWeek['87'] != 0 ? $grandTotalByWeek['87'] : '',
            'Aug' => collect($grandTotalByWeek)->only([83, 84, 85, 86, 87])->sum() != 0 ? collect($grandTotalByWeek)->only([83, 84, 85, 86, 87])->sum() : '',
            'Week36' => $grandTotalByWeek['88'] != 0 ? $grandTotalByWeek['88'] : '',
            'Week37' => $grandTotalByWeek['89'] != 0 ? $grandTotalByWeek['89'] : '',
            'Week38' => $grandTotalByWeek['90'] != 0 ? $grandTotalByWeek['90'] : '',
            'Week39' => $grandTotalByWeek['91'] != 0 ? $grandTotalByWeek['91'] : '',
            'Sep' => collect($grandTotalByWeek)->only([88, 89, 90, 91])->sum() != 0 ? collect($grandTotalByWeek)->only([88, 89, 90, 91])->sum() : '',
            'Week40' => $grandTotalByWeek['92'] != 0 ? $grandTotalByWeek['92'] : '',
            'Week41' => $grandTotalByWeek['93'] != 0 ? $grandTotalByWeek['93'] : '',
            'Week42' => $grandTotalByWeek['94'] != 0 ? $grandTotalByWeek['94'] : '',
            'Week43' => $grandTotalByWeek['95'] != 0 ? $grandTotalByWeek['95'] : '',
            'Oct' => collect($grandTotalByWeek)->only([92, 93, 94, 95])->sum() != 0 ? collect($grandTotalByWeek)->only([92, 93, 94, 95])->sum() : '',
            'Week44' => $grandTotalByWeek['96'] != 0 ? $grandTotalByWeek['96'] : '',
            'Week45' => $grandTotalByWeek['97'] != 0 ? $grandTotalByWeek['97'] : '',
            'Week46' => $grandTotalByWeek['98'] != 0 ? $grandTotalByWeek['98'] : '',
            'Week47' => $grandTotalByWeek['99'] != 0 ? $grandTotalByWeek['99'] : '',
            'Week48' => $grandTotalByWeek['100'] != 0 ? $grandTotalByWeek['100'] : '',
            'Nov' => collect($grandTotalByWeek)->only([96, 97, 98, 99, 100])->sum() != 0 ? collect($grandTotalByWeek)->only([96, 97, 98, 99, 100])->sum() : '',
            'Week49' => $grandTotalByWeek['101'] != 0 ? $grandTotalByWeek['101'] : '',
            'Week50' => $grandTotalByWeek['102'] != 0 ? $grandTotalByWeek['102'] : '',
            'Week51' => $grandTotalByWeek['103'] != 0 ? $grandTotalByWeek['103'] : '',
            'Week52' => $grandTotalByWeek['104'] != 0 ? $grandTotalByWeek['104'] : '',
            'Dec' => collect($grandTotalByWeek)->only([101, 102, 103, 104])->sum() != 0 ? collect($grandTotalByWeek)->only([101, 102, 103, 104])->sum() : '',
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
            ->when(true, function ($query) {
                $query->whereHas('site', function ($subquery) {
                    $subquery->where('is_active', 1);
                });

                $query->whereHas('site', function ($subquery) {
                    $subquery->where('country', 'Philippines');
                });
            })

            ->where('is_active', 1) // You can keep or remove this line based on your requirements
            ->get();

        $year = 2024;
        $dateRanges = DateRange::where('year', $year)
            ->get();

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
                    '53' => 0,
                    '54' => 0,
                    '55' => 0,
                    '56' => 0,
                    '57' => 0,
                    '58' => 0,
                    '59' => 0,
                    '60' => 0,
                    '61' => 0,
                    '62' => 0,
                    '63' => 0,
                    '64' => 0,
                    '65' => 0,
                    '66' => 0,
                    '67' => 0,
                    '68' => 0,
                    '69' => 0,
                    '70' => 0,
                    '71' => 0,
                    '72' => 0,
                    '73' => 0,
                    '74' => 0,
                    '75' => 0,
                    '76' => 0,
                    '77' => 0,
                    '78' => 0,
                    '79' => 0,
                    '80' => 0,
                    '81' => 0,
                    '82' => 0,
                    '83' => 0,
                    '84' => 0,
                    '85' => 0,
                    '86' => 0,
                    '87' => 0,
                    '88' => 0,
                    '89' => 0,
                    '90' => 0,
                    '91' => 0,
                    '92' => 0,
                    '93' => 0,
                    '94' => 0,
                    '95' => 0,
                    '96' => 0,
                    '97' => 0,
                    '98' => 0,
                    '99' => 0,
                    '100' => 0,
                    '101' => 0,
                    '102' => 0,
                    '103' => 0,
                    '104' => 0,
                ];

                foreach ($programData as $week => $weekData) {
                    $weeklyData[$week] = isset($weekData['total_target']) ? $weekData['total_target'] : 0;
                }
                $grandTotal = $grandTotalByProgram[$siteName][$programName];
                if ($grandTotal != 0) {
                    $mappedGroupedClasses[] = [
                        'Site' => $siteName,
                        'Program' => $programName,
                        'Week1' => $weeklyData['53'] != 0 ? $weeklyData['53'] : '',
                        'Week2' => $weeklyData['54'] != 0 ? $weeklyData['54'] : '',
                        'Week3' => $weeklyData['55'] != 0 ? $weeklyData['55'] : '',
                        'Week4' => $weeklyData['56'] != 0 ? $weeklyData['56'] : '',
                        'Jan' => collect($weeklyData)->only([53, 54, 55, 56])->sum() != 0 ? collect($weeklyData)->only([53, 54, 55, 56])->sum() : '',
                        'Week5' => $weeklyData['57'] != 0 ? $weeklyData['57'] : '',
                        'Week6' => $weeklyData['58'] != 0 ? $weeklyData['58'] : '',
                        'Week7' => $weeklyData['59'] != 0 ? $weeklyData['59'] : '',
                        'Week8' => $weeklyData['60'] != 0 ? $weeklyData['60'] : '',
                        'Feb' => collect($weeklyData)->only([57, 58, 59, 60])->sum() != 0 ? collect($weeklyData)->only([57, 58, 59, 60])->sum() : '',
                        'Week9' => $weeklyData['61'] != 0 ? $weeklyData['61'] : '',
                        'Week10' => $weeklyData['62'] != 0 ? $weeklyData['62'] : '',
                        'Week11' => $weeklyData['63'] != 0 ? $weeklyData['63'] : '',
                        'Week12' => $weeklyData['64'] != 0 ? $weeklyData['64'] : '',
                        'Week13' => $weeklyData['65'] != 0 ? $weeklyData['65'] : '',
                        'Mar' => collect($weeklyData)->only([61, 62, 63, 64, 65])->sum() != 0 ? collect($weeklyData)->only([61, 62, 63, 64, 65])->sum() : '',
                        'Week14' => $weeklyData['66'] != 0 ? $weeklyData['66'] : '',
                        'Week15' => $weeklyData['67'] != 0 ? $weeklyData['67'] : '',
                        'Week16' => $weeklyData['68'] != 0 ? $weeklyData['68'] : '',
                        'Week17' => $weeklyData['69'] != 0 ? $weeklyData['69'] : '',
                        'Apr' => collect($weeklyData)->only([66, 67, 68, 69])->sum() != 0 ? collect($weeklyData)->only([66, 67, 68, 69])->sum() : '',
                        'Week18' => $weeklyData['70'] != 0 ? $weeklyData['70'] : '',
                        'Week19' => $weeklyData['71'] != 0 ? $weeklyData['71'] : '',
                        'Week20' => $weeklyData['72'] != 0 ? $weeklyData['72'] : '',
                        'Week21' => $weeklyData['73'] != 0 ? $weeklyData['73'] : '',
                        'May' => collect($weeklyData)->only([70, 71, 72, 73])->sum() != 0 ? collect($weeklyData)->only([70, 71, 72, 73])->sum() : '',
                        'Week22' => $weeklyData['74'] != 0 ? $weeklyData['74'] : '',
                        'Week23' => $weeklyData['75'] != 0 ? $weeklyData['75'] : '',
                        'Week24' => $weeklyData['76'] != 0 ? $weeklyData['76'] : '',
                        'Week25' => $weeklyData['77'] != 0 ? $weeklyData['77'] : '',
                        'Week26' => $weeklyData['78'] != 0 ? $weeklyData['78'] : '',
                        'Jun' => collect($weeklyData)->only([74, 75, 76, 77, 78])->sum() != 0 ? collect($weeklyData)->only([74, 75, 76, 77, 78])->sum() : '',
                        'Week27' => $weeklyData['79'] != 0 ? $weeklyData['79'] : '',
                        'Week28' => $weeklyData['80'] != 0 ? $weeklyData['80'] : '',
                        'Week29' => $weeklyData['81'] != 0 ? $weeklyData['81'] : '',
                        'Week30' => $weeklyData['82'] != 0 ? $weeklyData['82'] : '',
                        'Jul' => collect($weeklyData)->only([79, 80, 81, 82])->sum() != 0 ? collect($weeklyData)->only([79, 80, 81, 82])->sum() : '',
                        'Week31' => $weeklyData['83'] != 0 ? $weeklyData['83'] : '',
                        'Week32' => $weeklyData['84'] != 0 ? $weeklyData['84'] : '',
                        'Week33' => $weeklyData['85'] != 0 ? $weeklyData['85'] : '',
                        'Week34' => $weeklyData['86'] != 0 ? $weeklyData['86'] : '',
                        'Week35' => $weeklyData['87'] != 0 ? $weeklyData['87'] : '',
                        'Aug' => collect($weeklyData)->only([83, 84, 85, 86, 87])->sum() != 0 ? collect($weeklyData)->only([83, 84, 85, 86, 87])->sum() : '',
                        'Week36' => $weeklyData['88'] != 0 ? $weeklyData['88'] : '',
                        'Week37' => $weeklyData['89'] != 0 ? $weeklyData['89'] : '',
                        'Week38' => $weeklyData['90'] != 0 ? $weeklyData['90'] : '',
                        'Week39' => $weeklyData['91'] != 0 ? $weeklyData['91'] : '',
                        'Sep' => collect($weeklyData)->only([88, 89, 90, 91])->sum() != 0 ? collect($weeklyData)->only([88, 89, 90, 91])->sum() : '',
                        'Week40' => $weeklyData['92'] != 0 ? $weeklyData['92'] : '',
                        'Week41' => $weeklyData['93'] != 0 ? $weeklyData['93'] : '',
                        'Week42' => $weeklyData['94'] != 0 ? $weeklyData['94'] : '',
                        'Week43' => $weeklyData['95'] != 0 ? $weeklyData['95'] : '',
                        'Oct' => collect($weeklyData)->only([92, 93, 94, 95])->sum() != 0 ? collect($weeklyData)->only([92, 93, 94, 95])->sum() : '',
                        'Week44' => $weeklyData['96'] != 0 ? $weeklyData['96'] : '',
                        'Week45' => $weeklyData['97'] != 0 ? $weeklyData['97'] : '',
                        'Week46' => $weeklyData['98'] != 0 ? $weeklyData['98'] : '',
                        'Week47' => $weeklyData['99'] != 0 ? $weeklyData['99'] : '',
                        'Week48' => $weeklyData['100'] != 0 ? $weeklyData['100'] : '',
                        'Nov' => collect($weeklyData)->only([96, 97, 98, 99, 100])->sum() != 0 ? collect($weeklyData)->only([96, 97, 98, 99, 100])->sum() : '',
                        'Week49' => $weeklyData['101'] != 0 ? $weeklyData['101'] : '',
                        'Week50' => $weeklyData['102'] != 0 ? $weeklyData['102'] : '',
                        'Week51' => $weeklyData['103'] != 0 ? $weeklyData['103'] : '',
                        'Week52' => $weeklyData['104'] != 0 ? $weeklyData['104'] : '',
                        'Dec' => collect($weeklyData)->only([101, 102, 103, 104])->sum() != 0 ? collect($weeklyData)->only([101, 102, 103, 104])->sum() : '',

                        'GrandTotalByProgram' => $grandTotal,
                    ];
                }
            }
        }

        $grandTotalForAllPrograms = array_sum(array_map('array_sum', $grandTotalByProgram));

        $mappedGroupedClasses[] = [
            'Site' => 'Grand Total',
            'Program' => '',
            'Week1' => $grandTotalByWeek['53'] != 0 ? $grandTotalByWeek['53'] : '',
            'Week2' => $grandTotalByWeek['54'] != 0 ? $grandTotalByWeek['54'] : '',
            'Week3' => $grandTotalByWeek['55'] != 0 ? $grandTotalByWeek['55'] : '',
            'Week4' => $grandTotalByWeek['56'] != 0 ? $grandTotalByWeek['56'] : '',
            'Jan' => collect($grandTotalByWeek)->only([53, 54, 55, 56])->sum() != 0 ? collect($grandTotalByWeek)->only([53, 54, 55, 56])->sum() : '',
            'Week5' => $grandTotalByWeek['57'] != 0 ? $grandTotalByWeek['57'] : '',
            'Week6' => $grandTotalByWeek['58'] != 0 ? $grandTotalByWeek['58'] : '',
            'Week7' => $grandTotalByWeek['59'] != 0 ? $grandTotalByWeek['59'] : '',
            'Week8' => $grandTotalByWeek['60'] != 0 ? $grandTotalByWeek['60'] : '',
            'Feb' => collect($grandTotalByWeek)->only([57, 58, 59, 60])->sum() != 0 ? collect($grandTotalByWeek)->only([57, 58, 59, 60])->sum() : '',
            'Week9' => $grandTotalByWeek['61'] != 0 ? $grandTotalByWeek['61'] : '',
            'Week10' => $grandTotalByWeek['62'] != 0 ? $grandTotalByWeek['62'] : '',
            'Week11' => $grandTotalByWeek['63'] != 0 ? $grandTotalByWeek['63'] : '',
            'Week12' => $grandTotalByWeek['64'] != 0 ? $grandTotalByWeek['64'] : '',
            'Week13' => $grandTotalByWeek['65'] != 0 ? $grandTotalByWeek['65'] : '',
            'Mar' => collect($grandTotalByWeek)->only([61, 62, 63, 64, 65])->sum() != 0 ? collect($grandTotalByWeek)->only([61, 62, 63, 64, 65])->sum() : '',
            'Week14' => $grandTotalByWeek['66'] != 0 ? $grandTotalByWeek['66'] : '',
            'Week15' => $grandTotalByWeek['67'] != 0 ? $grandTotalByWeek['67'] : '',
            'Week16' => $grandTotalByWeek['68'] != 0 ? $grandTotalByWeek['68'] : '',
            'Week17' => $grandTotalByWeek['69'] != 0 ? $grandTotalByWeek['69'] : '',
            'Apr' => collect($grandTotalByWeek)->only([66, 67, 68, 69])->sum() != 0 ? collect($grandTotalByWeek)->only([66, 67, 68, 69])->sum() : '',
            'Week18' => $grandTotalByWeek['70'] != 0 ? $grandTotalByWeek['70'] : '',
            'Week19' => $grandTotalByWeek['71'] != 0 ? $grandTotalByWeek['71'] : '',
            'Week20' => $grandTotalByWeek['72'] != 0 ? $grandTotalByWeek['72'] : '',
            'Week21' => $grandTotalByWeek['73'] != 0 ? $grandTotalByWeek['73'] : '',
            'May' => collect($grandTotalByWeek)->only([70, 71, 72, 73])->sum() != 0 ? collect($grandTotalByWeek)->only([70, 71, 72, 73])->sum() : '',
            'Week22' => $grandTotalByWeek['74'] != 0 ? $grandTotalByWeek['74'] : '',
            'Week23' => $grandTotalByWeek['75'] != 0 ? $grandTotalByWeek['75'] : '',
            'Week24' => $grandTotalByWeek['76'] != 0 ? $grandTotalByWeek['76'] : '',
            'Week25' => $grandTotalByWeek['77'] != 0 ? $grandTotalByWeek['77'] : '',
            'Week26' => $grandTotalByWeek['78'] != 0 ? $grandTotalByWeek['78'] : '',
            'Jun' => collect($grandTotalByWeek)->only([74, 75, 76, 77, 78])->sum() != 0 ? collect($grandTotalByWeek)->only([74, 75, 76, 77, 78])->sum() : '',
            'Week27' => $grandTotalByWeek['79'] != 0 ? $grandTotalByWeek['79'] : '',
            'Week28' => $grandTotalByWeek['80'] != 0 ? $grandTotalByWeek['80'] : '',
            'Week29' => $grandTotalByWeek['81'] != 0 ? $grandTotalByWeek['81'] : '',
            'Week30' => $grandTotalByWeek['82'] != 0 ? $grandTotalByWeek['82'] : '',
            'Jul' => collect($grandTotalByWeek)->only([79, 80, 81, 82])->sum() != 0 ? collect($grandTotalByWeek)->only([79, 80, 81, 82])->sum() : '',
            'Week31' => $grandTotalByWeek['83'] != 0 ? $grandTotalByWeek['83'] : '',
            'Week32' => $grandTotalByWeek['84'] != 0 ? $grandTotalByWeek['84'] : '',
            'Week33' => $grandTotalByWeek['85'] != 0 ? $grandTotalByWeek['85'] : '',
            'Week34' => $grandTotalByWeek['86'] != 0 ? $grandTotalByWeek['86'] : '',
            'Week35' => $grandTotalByWeek['87'] != 0 ? $grandTotalByWeek['87'] : '',
            'Aug' => collect($grandTotalByWeek)->only([83, 84, 85, 86, 87])->sum() != 0 ? collect($grandTotalByWeek)->only([83, 84, 85, 86, 87])->sum() : '',
            'Week36' => $grandTotalByWeek['88'] != 0 ? $grandTotalByWeek['88'] : '',
            'Week37' => $grandTotalByWeek['89'] != 0 ? $grandTotalByWeek['89'] : '',
            'Week38' => $grandTotalByWeek['90'] != 0 ? $grandTotalByWeek['90'] : '',
            'Week39' => $grandTotalByWeek['91'] != 0 ? $grandTotalByWeek['91'] : '',
            'Sep' => collect($grandTotalByWeek)->only([88, 89, 90, 91])->sum() != 0 ? collect($grandTotalByWeek)->only([88, 89, 90, 91])->sum() : '',
            'Week40' => $grandTotalByWeek['92'] != 0 ? $grandTotalByWeek['92'] : '',
            'Week41' => $grandTotalByWeek['93'] != 0 ? $grandTotalByWeek['93'] : '',
            'Week42' => $grandTotalByWeek['94'] != 0 ? $grandTotalByWeek['94'] : '',
            'Week43' => $grandTotalByWeek['95'] != 0 ? $grandTotalByWeek['95'] : '',
            'Oct' => collect($grandTotalByWeek)->only([92, 93, 94, 95])->sum() != 0 ? collect($grandTotalByWeek)->only([92, 93, 94, 95])->sum() : '',
            'Week44' => $grandTotalByWeek['96'] != 0 ? $grandTotalByWeek['96'] : '',
            'Week45' => $grandTotalByWeek['97'] != 0 ? $grandTotalByWeek['97'] : '',
            'Week46' => $grandTotalByWeek['98'] != 0 ? $grandTotalByWeek['98'] : '',
            'Week47' => $grandTotalByWeek['99'] != 0 ? $grandTotalByWeek['99'] : '',
            'Week48' => $grandTotalByWeek['100'] != 0 ? $grandTotalByWeek['100'] : '',
            'Nov' => collect($grandTotalByWeek)->only([96, 97, 98, 99, 100])->sum() != 0 ? collect($grandTotalByWeek)->only([96, 97, 98, 99, 100])->sum() : '',
            'Week49' => $grandTotalByWeek['101'] != 0 ? $grandTotalByWeek['101'] : '',
            'Week50' => $grandTotalByWeek['102'] != 0 ? $grandTotalByWeek['102'] : '',
            'Week51' => $grandTotalByWeek['103'] != 0 ? $grandTotalByWeek['103'] : '',
            'Week52' => $grandTotalByWeek['104'] != 0 ? $grandTotalByWeek['104'] : '',
            'Dec' => collect($grandTotalByWeek)->only([101, 102, 103, 104])->sum() != 0 ? collect($grandTotalByWeek)->only([101, 102, 103, 104])->sum() : '',
            'GrandTotalByProgram' => $grandTotalForAllPrograms,
        ];

        $response = [
            'classes' => $mappedGroupedClasses,
        ];

        return Excel::download(new DashboardClassesExport($response), 'filtered_data.xlsx');
    }
    public function dashboardClassesExportJamaica(Request $request)
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
            ->when(true, function ($query) {
                $query->whereHas('site', function ($subquery) {
                    $subquery->where('is_active', 1);
                });

                $query->whereHas('site', function ($subquery) {
                    $subquery->where('country', 'Jamaica');
                });
            })

            ->where('is_active', 1) // You can keep or remove this line based on your requirements
            ->get();

        $year = 2024;
        $dateRanges = DateRange::where('year', $year)
            ->get();

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
                    '53' => 0,
                    '54' => 0,
                    '55' => 0,
                    '56' => 0,
                    '57' => 0,
                    '58' => 0,
                    '59' => 0,
                    '60' => 0,
                    '61' => 0,
                    '62' => 0,
                    '63' => 0,
                    '64' => 0,
                    '65' => 0,
                    '66' => 0,
                    '67' => 0,
                    '68' => 0,
                    '69' => 0,
                    '70' => 0,
                    '71' => 0,
                    '72' => 0,
                    '73' => 0,
                    '74' => 0,
                    '75' => 0,
                    '76' => 0,
                    '77' => 0,
                    '78' => 0,
                    '79' => 0,
                    '80' => 0,
                    '81' => 0,
                    '82' => 0,
                    '83' => 0,
                    '84' => 0,
                    '85' => 0,
                    '86' => 0,
                    '87' => 0,
                    '88' => 0,
                    '89' => 0,
                    '90' => 0,
                    '91' => 0,
                    '92' => 0,
                    '93' => 0,
                    '94' => 0,
                    '95' => 0,
                    '96' => 0,
                    '97' => 0,
                    '98' => 0,
                    '99' => 0,
                    '100' => 0,
                    '101' => 0,
                    '102' => 0,
                    '103' => 0,
                    '104' => 0,
                ];

                foreach ($programData as $week => $weekData) {
                    $weeklyData[$week] = isset($weekData['total_target']) ? $weekData['total_target'] : 0;
                }
                $grandTotal = $grandTotalByProgram[$siteName][$programName];
                if ($grandTotal != 0) {
                    $mappedGroupedClasses[] = [
                        'Site' => $siteName,
                        'Program' => $programName,
                        'Week1' => $weeklyData['53'] != 0 ? $weeklyData['53'] : '',
                        'Week2' => $weeklyData['54'] != 0 ? $weeklyData['54'] : '',
                        'Week3' => $weeklyData['55'] != 0 ? $weeklyData['55'] : '',
                        'Week4' => $weeklyData['56'] != 0 ? $weeklyData['56'] : '',
                        'Jan' => collect($weeklyData)->only([53, 54, 55, 56])->sum() != 0 ? collect($weeklyData)->only([53, 54, 55, 56])->sum() : '',
                        'Week5' => $weeklyData['57'] != 0 ? $weeklyData['57'] : '',
                        'Week6' => $weeklyData['58'] != 0 ? $weeklyData['58'] : '',
                        'Week7' => $weeklyData['59'] != 0 ? $weeklyData['59'] : '',
                        'Week8' => $weeklyData['60'] != 0 ? $weeklyData['60'] : '',
                        'Feb' => collect($weeklyData)->only([57, 58, 59, 60])->sum() != 0 ? collect($weeklyData)->only([57, 58, 59, 60])->sum() : '',
                        'Week9' => $weeklyData['61'] != 0 ? $weeklyData['61'] : '',
                        'Week10' => $weeklyData['62'] != 0 ? $weeklyData['62'] : '',
                        'Week11' => $weeklyData['63'] != 0 ? $weeklyData['63'] : '',
                        'Week12' => $weeklyData['64'] != 0 ? $weeklyData['64'] : '',
                        'Week13' => $weeklyData['65'] != 0 ? $weeklyData['65'] : '',
                        'Mar' => collect($weeklyData)->only([61, 62, 63, 64, 65])->sum() != 0 ? collect($weeklyData)->only([61, 62, 63, 64, 65])->sum() : '',
                        'Week14' => $weeklyData['66'] != 0 ? $weeklyData['66'] : '',
                        'Week15' => $weeklyData['67'] != 0 ? $weeklyData['67'] : '',
                        'Week16' => $weeklyData['68'] != 0 ? $weeklyData['68'] : '',
                        'Week17' => $weeklyData['69'] != 0 ? $weeklyData['69'] : '',
                        'Apr' => collect($weeklyData)->only([66, 67, 68, 69])->sum() != 0 ? collect($weeklyData)->only([66, 67, 68, 69])->sum() : '',
                        'Week18' => $weeklyData['70'] != 0 ? $weeklyData['70'] : '',
                        'Week19' => $weeklyData['71'] != 0 ? $weeklyData['71'] : '',
                        'Week20' => $weeklyData['72'] != 0 ? $weeklyData['72'] : '',
                        'Week21' => $weeklyData['73'] != 0 ? $weeklyData['73'] : '',
                        'May' => collect($weeklyData)->only([70, 71, 72, 73])->sum() != 0 ? collect($weeklyData)->only([70, 71, 72, 73])->sum() : '',
                        'Week22' => $weeklyData['74'] != 0 ? $weeklyData['74'] : '',
                        'Week23' => $weeklyData['75'] != 0 ? $weeklyData['75'] : '',
                        'Week24' => $weeklyData['76'] != 0 ? $weeklyData['76'] : '',
                        'Week25' => $weeklyData['77'] != 0 ? $weeklyData['77'] : '',
                        'Week26' => $weeklyData['78'] != 0 ? $weeklyData['78'] : '',
                        'Jun' => collect($weeklyData)->only([74, 75, 76, 77, 78])->sum() != 0 ? collect($weeklyData)->only([74, 75, 76, 77, 78])->sum() : '',
                        'Week27' => $weeklyData['79'] != 0 ? $weeklyData['79'] : '',
                        'Week28' => $weeklyData['80'] != 0 ? $weeklyData['80'] : '',
                        'Week29' => $weeklyData['81'] != 0 ? $weeklyData['81'] : '',
                        'Week30' => $weeklyData['82'] != 0 ? $weeklyData['82'] : '',
                        'Jul' => collect($weeklyData)->only([79, 80, 81, 82])->sum() != 0 ? collect($weeklyData)->only([79, 80, 81, 82])->sum() : '',
                        'Week31' => $weeklyData['83'] != 0 ? $weeklyData['83'] : '',
                        'Week32' => $weeklyData['84'] != 0 ? $weeklyData['84'] : '',
                        'Week33' => $weeklyData['85'] != 0 ? $weeklyData['85'] : '',
                        'Week34' => $weeklyData['86'] != 0 ? $weeklyData['86'] : '',
                        'Week35' => $weeklyData['87'] != 0 ? $weeklyData['87'] : '',
                        'Aug' => collect($weeklyData)->only([83, 84, 85, 86, 87])->sum() != 0 ? collect($weeklyData)->only([83, 84, 85, 86, 87])->sum() : '',
                        'Week36' => $weeklyData['88'] != 0 ? $weeklyData['88'] : '',
                        'Week37' => $weeklyData['89'] != 0 ? $weeklyData['89'] : '',
                        'Week38' => $weeklyData['90'] != 0 ? $weeklyData['90'] : '',
                        'Week39' => $weeklyData['91'] != 0 ? $weeklyData['91'] : '',
                        'Sep' => collect($weeklyData)->only([88, 89, 90, 91])->sum() != 0 ? collect($weeklyData)->only([88, 89, 90, 91])->sum() : '',
                        'Week40' => $weeklyData['92'] != 0 ? $weeklyData['92'] : '',
                        'Week41' => $weeklyData['93'] != 0 ? $weeklyData['93'] : '',
                        'Week42' => $weeklyData['94'] != 0 ? $weeklyData['94'] : '',
                        'Week43' => $weeklyData['95'] != 0 ? $weeklyData['95'] : '',
                        'Oct' => collect($weeklyData)->only([92, 93, 94, 95])->sum() != 0 ? collect($weeklyData)->only([92, 93, 94, 95])->sum() : '',
                        'Week44' => $weeklyData['96'] != 0 ? $weeklyData['96'] : '',
                        'Week45' => $weeklyData['97'] != 0 ? $weeklyData['97'] : '',
                        'Week46' => $weeklyData['98'] != 0 ? $weeklyData['98'] : '',
                        'Week47' => $weeklyData['99'] != 0 ? $weeklyData['99'] : '',
                        'Week48' => $weeklyData['100'] != 0 ? $weeklyData['100'] : '',
                        'Nov' => collect($weeklyData)->only([96, 97, 98, 99, 100])->sum() != 0 ? collect($weeklyData)->only([96, 97, 98, 99, 100])->sum() : '',
                        'Week49' => $weeklyData['101'] != 0 ? $weeklyData['101'] : '',
                        'Week50' => $weeklyData['102'] != 0 ? $weeklyData['102'] : '',
                        'Week51' => $weeklyData['103'] != 0 ? $weeklyData['103'] : '',
                        'Week52' => $weeklyData['104'] != 0 ? $weeklyData['104'] : '',
                        'Dec' => collect($weeklyData)->only([101, 102, 103, 104])->sum() != 0 ? collect($weeklyData)->only([101, 102, 103, 104])->sum() : '',

                        'GrandTotalByProgram' => $grandTotal,
                    ];
                }
            }
        }

        $grandTotalForAllPrograms = array_sum(array_map('array_sum', $grandTotalByProgram));

        $mappedGroupedClasses[] = [
            'Site' => 'Grand Total',
            'Program' => '',
            'Week1' => $grandTotalByWeek['53'] != 0 ? $grandTotalByWeek['53'] : '',
            'Week2' => $grandTotalByWeek['54'] != 0 ? $grandTotalByWeek['54'] : '',
            'Week3' => $grandTotalByWeek['55'] != 0 ? $grandTotalByWeek['55'] : '',
            'Week4' => $grandTotalByWeek['56'] != 0 ? $grandTotalByWeek['56'] : '',
            'Jan' => collect($grandTotalByWeek)->only([53, 54, 55, 56])->sum() != 0 ? collect($grandTotalByWeek)->only([53, 54, 55, 56])->sum() : '',
            'Week5' => $grandTotalByWeek['57'] != 0 ? $grandTotalByWeek['57'] : '',
            'Week6' => $grandTotalByWeek['58'] != 0 ? $grandTotalByWeek['58'] : '',
            'Week7' => $grandTotalByWeek['59'] != 0 ? $grandTotalByWeek['59'] : '',
            'Week8' => $grandTotalByWeek['60'] != 0 ? $grandTotalByWeek['60'] : '',
            'Feb' => collect($grandTotalByWeek)->only([57, 58, 59, 60])->sum() != 0 ? collect($grandTotalByWeek)->only([57, 58, 59, 60])->sum() : '',
            'Week9' => $grandTotalByWeek['61'] != 0 ? $grandTotalByWeek['61'] : '',
            'Week10' => $grandTotalByWeek['62'] != 0 ? $grandTotalByWeek['62'] : '',
            'Week11' => $grandTotalByWeek['63'] != 0 ? $grandTotalByWeek['63'] : '',
            'Week12' => $grandTotalByWeek['64'] != 0 ? $grandTotalByWeek['64'] : '',
            'Week13' => $grandTotalByWeek['65'] != 0 ? $grandTotalByWeek['65'] : '',
            'Mar' => collect($grandTotalByWeek)->only([61, 62, 63, 64, 65])->sum() != 0 ? collect($grandTotalByWeek)->only([61, 62, 63, 64, 65])->sum() : '',
            'Week14' => $grandTotalByWeek['66'] != 0 ? $grandTotalByWeek['66'] : '',
            'Week15' => $grandTotalByWeek['67'] != 0 ? $grandTotalByWeek['67'] : '',
            'Week16' => $grandTotalByWeek['68'] != 0 ? $grandTotalByWeek['68'] : '',
            'Week17' => $grandTotalByWeek['69'] != 0 ? $grandTotalByWeek['69'] : '',
            'Apr' => collect($grandTotalByWeek)->only([66, 67, 68, 69])->sum() != 0 ? collect($grandTotalByWeek)->only([66, 67, 68, 69])->sum() : '',
            'Week18' => $grandTotalByWeek['70'] != 0 ? $grandTotalByWeek['70'] : '',
            'Week19' => $grandTotalByWeek['71'] != 0 ? $grandTotalByWeek['71'] : '',
            'Week20' => $grandTotalByWeek['72'] != 0 ? $grandTotalByWeek['72'] : '',
            'Week21' => $grandTotalByWeek['73'] != 0 ? $grandTotalByWeek['73'] : '',
            'May' => collect($grandTotalByWeek)->only([70, 71, 72, 73])->sum() != 0 ? collect($grandTotalByWeek)->only([70, 71, 72, 73])->sum() : '',
            'Week22' => $grandTotalByWeek['74'] != 0 ? $grandTotalByWeek['74'] : '',
            'Week23' => $grandTotalByWeek['75'] != 0 ? $grandTotalByWeek['75'] : '',
            'Week24' => $grandTotalByWeek['76'] != 0 ? $grandTotalByWeek['76'] : '',
            'Week25' => $grandTotalByWeek['77'] != 0 ? $grandTotalByWeek['77'] : '',
            'Week26' => $grandTotalByWeek['78'] != 0 ? $grandTotalByWeek['78'] : '',
            'Jun' => collect($grandTotalByWeek)->only([74, 75, 76, 77, 78])->sum() != 0 ? collect($grandTotalByWeek)->only([74, 75, 76, 77, 78])->sum() : '',
            'Week27' => $grandTotalByWeek['79'] != 0 ? $grandTotalByWeek['79'] : '',
            'Week28' => $grandTotalByWeek['80'] != 0 ? $grandTotalByWeek['80'] : '',
            'Week29' => $grandTotalByWeek['81'] != 0 ? $grandTotalByWeek['81'] : '',
            'Week30' => $grandTotalByWeek['82'] != 0 ? $grandTotalByWeek['82'] : '',
            'Jul' => collect($grandTotalByWeek)->only([79, 80, 81, 82])->sum() != 0 ? collect($grandTotalByWeek)->only([79, 80, 81, 82])->sum() : '',
            'Week31' => $grandTotalByWeek['83'] != 0 ? $grandTotalByWeek['83'] : '',
            'Week32' => $grandTotalByWeek['84'] != 0 ? $grandTotalByWeek['84'] : '',
            'Week33' => $grandTotalByWeek['85'] != 0 ? $grandTotalByWeek['85'] : '',
            'Week34' => $grandTotalByWeek['86'] != 0 ? $grandTotalByWeek['86'] : '',
            'Week35' => $grandTotalByWeek['87'] != 0 ? $grandTotalByWeek['87'] : '',
            'Aug' => collect($grandTotalByWeek)->only([83, 84, 85, 86, 87])->sum() != 0 ? collect($grandTotalByWeek)->only([83, 84, 85, 86, 87])->sum() : '',
            'Week36' => $grandTotalByWeek['88'] != 0 ? $grandTotalByWeek['88'] : '',
            'Week37' => $grandTotalByWeek['89'] != 0 ? $grandTotalByWeek['89'] : '',
            'Week38' => $grandTotalByWeek['90'] != 0 ? $grandTotalByWeek['90'] : '',
            'Week39' => $grandTotalByWeek['91'] != 0 ? $grandTotalByWeek['91'] : '',
            'Sep' => collect($grandTotalByWeek)->only([88, 89, 90, 91])->sum() != 0 ? collect($grandTotalByWeek)->only([88, 89, 90, 91])->sum() : '',
            'Week40' => $grandTotalByWeek['92'] != 0 ? $grandTotalByWeek['92'] : '',
            'Week41' => $grandTotalByWeek['93'] != 0 ? $grandTotalByWeek['93'] : '',
            'Week42' => $grandTotalByWeek['94'] != 0 ? $grandTotalByWeek['94'] : '',
            'Week43' => $grandTotalByWeek['95'] != 0 ? $grandTotalByWeek['95'] : '',
            'Oct' => collect($grandTotalByWeek)->only([92, 93, 94, 95])->sum() != 0 ? collect($grandTotalByWeek)->only([92, 93, 94, 95])->sum() : '',
            'Week44' => $grandTotalByWeek['96'] != 0 ? $grandTotalByWeek['96'] : '',
            'Week45' => $grandTotalByWeek['97'] != 0 ? $grandTotalByWeek['97'] : '',
            'Week46' => $grandTotalByWeek['98'] != 0 ? $grandTotalByWeek['98'] : '',
            'Week47' => $grandTotalByWeek['99'] != 0 ? $grandTotalByWeek['99'] : '',
            'Week48' => $grandTotalByWeek['100'] != 0 ? $grandTotalByWeek['100'] : '',
            'Nov' => collect($grandTotalByWeek)->only([96, 97, 98, 99, 100])->sum() != 0 ? collect($grandTotalByWeek)->only([96, 97, 98, 99, 100])->sum() : '',
            'Week49' => $grandTotalByWeek['101'] != 0 ? $grandTotalByWeek['101'] : '',
            'Week50' => $grandTotalByWeek['102'] != 0 ? $grandTotalByWeek['102'] : '',
            'Week51' => $grandTotalByWeek['103'] != 0 ? $grandTotalByWeek['103'] : '',
            'Week52' => $grandTotalByWeek['104'] != 0 ? $grandTotalByWeek['104'] : '',
            'Dec' => collect($grandTotalByWeek)->only([101, 102, 103, 104])->sum() != 0 ? collect($grandTotalByWeek)->only([101, 102, 103, 104])->sum() : '',
            'GrandTotalByProgram' => $grandTotalForAllPrograms,
        ];

        $response = [
            'classes' => $mappedGroupedClasses,
        ];

        return Excel::download(new dashboardClassesExport($response), 'filtered_data.xlsx');
    }
    public function dashboardClassesExportJamaica2(Request $request)
    {
        $query = Classes::with(['site', 'program', 'dateRange', 'createdByUser', 'updatedByUser', 'cancelledByUser'])
            ->whereHas('site', function ($query) use ($request) {
                $query->where('country', '=', 'Jamaica')
                    ->where('is_active', '=', 1);
            })
            ->whereHas('program', function ($query) use ($request) {
                $query->where('is_active', '=', 1);
            })
            ->whereHas('dateRange', function ($query) use ($request) {
                $query->where('year', '=', '2024');
            });

        if ($request->has('sites_selected')) {
            $sitesSelected = $request->input('sites_selected');

            if (!empty($sitesSelected)) {
                $query->where('site_id', $sitesSelected);
            }
        }

        if ($request->has('programs_selected')) {
            $programsSelected = $request->input('programs_selected');

            if (!empty($programsSelected)) {
                $query->where('program_id', $programsSelected);
            }
        }

        if ($request->has('month_selected')) {
            $monthSelected = $request->input('month_selected');

            if (!empty($monthSelected)) {
                $query->whereHas('dateRange', function ($query) use ($monthSelected) {
                    $query->where('month_num', $monthSelected);
                });
            }
        }

        if ($request->has('week_selected')) {
            $weekSelected = $request->input('week_selected');

            if (!empty($weekSelected)) {
                $query->whereHas('dateRange', function ($query) use ($weekSelected) {
                    $query->where('date_Range_id', $weekSelected);
                });
            }
        }

        $classes = $query->select([
            'id',
            'pushedback_id',
            'within_sla',
            'condition',
            'requested_by',
            'original_start_date',
            'changes',
            'agreed_start_date',
            'approved_date',
            'cancelled_date',
            'wfm_date_requested',
            'notice_weeks',
            'external_target',
            'internal_target',
            'notice_days',
            'pipeline_utilized',
            'total_target',
            'remarks',
            'status',
            'category',
            'type_of_hiring',
            'update_status',
            'approved_status',
            'with_erf',
            'erf_number',
            'approved_by',
            'cancelled_by',
            'ta',
            'wave_no',
            'wf',
            'tr',
            'cl',
            'op',
            'created_by',
            'site_id',
            'program_id',
            'updated_by',
            'date_range_id',
            'created_at',
            'updated_at',
        ])
            ->get();

        $formattedClasses = $classes->map(function ($class) {
            return [
                'id' => $class->id,
                'country' => $class->site->country,
                'region' => $class->site->region,
                'site_name' => $class->site->name,
                'program_name' => $class->program->name,
                'date_range' => $class->dateRange->date_range,
                'external_target' => $class->external_target,
                'internal_target' => $class->internal_target,
                'total_target' => $class->total_target,
                'within_sla' => $class->within_sla,
                'condition' => $class->condition,
                'original_start_date' => $class->original_start_date,
                'changes' => $class->changes,
                'agreed_start_date' => $class->agreed_start_date,
                'wfm_date_requested' => $class->wfm_date_requested,
                'notice_weeks' => $class->notice_weeks,
                'notice_days' => $class->notice_days,
                'pipeline_utilized' => $class->pipeline_utilized,
                'remarks' => $class->remarks,
                'status' => $class->status,
                'category' => $class->category,
                'type_of_hiring' => $class->type_of_hiring,
                'with_erf' => $class->with_erf,
                'erf_number' => $class->erf_number,
                'wave_no' => $class->wave_no,
                'ta' => $class->ta,
                'wf' => $class->wf,
                'tr' => $class->tr,
                'cl' => $class->cl,
                'op' => $class->op,
                'approved_by' => $class->approved_by,
                'cancelled_by' => $class->cancelledByUser ? $class->cancelledByUser->name : null,
                'requested_by' => $class->requested_by,
                'created_by' => $class->createdByUser->name,
                'updated_by' => $class->updatedByUser ? $class->updatedByUser->name : null,
                'approved_date' => $class->approved_date,
                'cancelled_date' => $class->cancelled_date,
                'created_at' => $class->created_at->format('m-d-Y H:i'),
                'updated_at' => $class->updated_at->format('m-d-Y H:i'),
            ];
        })->toArray();

        return Excel::download(new ClassHistoryExport($formattedClasses), 'Class_history_data.xlsx');
    }

    public function dashboardClassesExportJamaica3(Request $request)
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
            ->when(true, function ($query) {
                $query->whereHas('site', function ($subquery) {
                    $subquery->where('is_active', 1);
                });

                $query->whereHas('site', function ($subquery) {
                    $subquery->where('country', 'Jamaica');
                });
            })

            ->where('is_active', 1) // You can keep or remove this line based on your requirements
            ->get();

        $year = 2024;
        $dateRanges = DateRange::where('year', $year)
            ->get();

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
                    '53' => 0,
                    '54' => 0,
                    '55' => 0,
                    '56' => 0,
                    '57' => 0,
                    '58' => 0,
                    '59' => 0,
                    '60' => 0,
                    '61' => 0,
                    '62' => 0,
                    '63' => 0,
                    '64' => 0,
                    '65' => 0,
                    '66' => 0,
                    '67' => 0,
                    '68' => 0,
                    '69' => 0,
                    '70' => 0,
                    '71' => 0,
                    '72' => 0,
                    '73' => 0,
                    '74' => 0,
                    '75' => 0,
                    '76' => 0,
                    '77' => 0,
                    '78' => 0,
                    '79' => 0,
                    '80' => 0,
                    '81' => 0,
                    '82' => 0,
                    '83' => 0,
                    '84' => 0,
                    '85' => 0,
                    '86' => 0,
                    '87' => 0,
                    '88' => 0,
                    '89' => 0,
                    '90' => 0,
                    '91' => 0,
                    '92' => 0,
                    '93' => 0,
                    '94' => 0,
                    '95' => 0,
                    '96' => 0,
                    '97' => 0,
                    '98' => 0,
                    '99' => 0,
                    '100' => 0,
                    '101' => 0,
                    '102' => 0,
                    '103' => 0,
                    '104' => 0,
                ];

                foreach ($programData as $week => $weekData) {
                    $weeklyData[$week] = isset($weekData['total_target']) ? $weekData['total_target'] : 0;
                }
                $grandTotal = $grandTotalByProgram[$siteName][$programName];
                if ($grandTotal != 0) {
                    $mappedGroupedClasses[] = [
                        'Site' => $siteName,
                        'Program' => $programName,
                        'Week1' => $weeklyData['53'] != 0 ? $weeklyData['53'] : '',

                        'Week2' => $weeklyData['54'] != 0 ? $weeklyData['54'] : '',
                        'Week3' => $weeklyData['55'] != 0 ? $weeklyData['55'] : '',
                        'Week4' => $weeklyData['56'] != 0 ? $weeklyData['56'] : '',
                        'Jan' => collect($weeklyData)->only([53, 54, 55, 56])->sum() != 0 ? collect($weeklyData)->only([53, 54, 55, 56])->sum() : '',
                        'Week5' => $weeklyData['57'] != 0 ? $weeklyData['57'] : '',
                        'Week6' => $weeklyData['58'] != 0 ? $weeklyData['58'] : '',
                        'Week7' => $weeklyData['59'] != 0 ? $weeklyData['59'] : '',
                        'Week8' => $weeklyData['60'] != 0 ? $weeklyData['60'] : '',
                        'Feb' => collect($weeklyData)->only([57, 58, 59, 60])->sum() != 0 ? collect($weeklyData)->only([57, 58, 59, 60])->sum() : '',
                        'Week9' => $weeklyData['61'] != 0 ? $weeklyData['61'] : '',
                        'Week10' => $weeklyData['62'] != 0 ? $weeklyData['62'] : '',
                        'Week11' => $weeklyData['63'] != 0 ? $weeklyData['63'] : '',
                        'Week12' => $weeklyData['64'] != 0 ? $weeklyData['64'] : '',
                        'Week13' => $weeklyData['65'] != 0 ? $weeklyData['65'] : '',
                        'Mar' => collect($weeklyData)->only([61, 62, 63, 64, 65])->sum() != 0 ? collect($weeklyData)->only([61, 62, 63, 64, 65])->sum() : '',
                        'Week14' => $weeklyData['66'] != 0 ? $weeklyData['66'] : '',
                        'Week15' => $weeklyData['67'] != 0 ? $weeklyData['67'] : '',
                        'Week16' => $weeklyData['68'] != 0 ? $weeklyData['68'] : '',
                        'Week17' => $weeklyData['69'] != 0 ? $weeklyData['69'] : '',
                        'Apr' => collect($weeklyData)->only([66, 67, 68, 69])->sum() != 0 ? collect($weeklyData)->only([66, 67, 68, 69])->sum() : '',
                        'Week18' => $weeklyData['70'] != 0 ? $weeklyData['70'] : '',
                        'Week19' => $weeklyData['71'] != 0 ? $weeklyData['71'] : '',
                        'Week20' => $weeklyData['72'] != 0 ? $weeklyData['72'] : '',
                        'Week21' => $weeklyData['73'] != 0 ? $weeklyData['73'] : '',
                        'May' => collect($weeklyData)->only([70, 71, 72, 73])->sum() != 0 ? collect($weeklyData)->only([70, 71, 72, 73])->sum() : '',
                        'Week22' => $weeklyData['74'] != 0 ? $weeklyData['74'] : '',
                        'Week23' => $weeklyData['75'] != 0 ? $weeklyData['75'] : '',
                        'Week24' => $weeklyData['76'] != 0 ? $weeklyData['76'] : '',
                        'Week25' => $weeklyData['77'] != 0 ? $weeklyData['77'] : '',
                        'Week26' => $weeklyData['78'] != 0 ? $weeklyData['78'] : '',
                        'Jun' => collect($weeklyData)->only([74, 75, 76, 77, 78])->sum() != 0 ? collect($weeklyData)->only([74, 75, 76, 77, 78])->sum() : '',
                        'Week27' => $weeklyData['79'] != 0 ? $weeklyData['79'] : '',
                        'Week28' => $weeklyData['80'] != 0 ? $weeklyData['80'] : '',
                        'Week29' => $weeklyData['81'] != 0 ? $weeklyData['81'] : '',
                        'Week30' => $weeklyData['82'] != 0 ? $weeklyData['82'] : '',
                        'Jul' => collect($weeklyData)->only([79, 80, 81, 82])->sum() != 0 ? collect($weeklyData)->only([79, 80, 81, 82])->sum() : '',
                        'Week31' => $weeklyData['83'] != 0 ? $weeklyData['83'] : '',
                        'Week32' => $weeklyData['84'] != 0 ? $weeklyData['84'] : '',
                        'Week33' => $weeklyData['85'] != 0 ? $weeklyData['85'] : '',
                        'Week34' => $weeklyData['86'] != 0 ? $weeklyData['86'] : '',
                        'Week35' => $weeklyData['87'] != 0 ? $weeklyData['87'] : '',
                        'Aug' => collect($weeklyData)->only([83, 84, 85, 86, 87])->sum() != 0 ? collect($weeklyData)->only([83, 84, 85, 86, 87])->sum() : '',
                        'Week36' => $weeklyData['88'] != 0 ? $weeklyData['88'] : '',
                        'Week37' => $weeklyData['89'] != 0 ? $weeklyData['89'] : '',
                        'Week38' => $weeklyData['90'] != 0 ? $weeklyData['90'] : '',
                        'Week39' => $weeklyData['91'] != 0 ? $weeklyData['91'] : '',
                        'Sep' => collect($weeklyData)->only([88, 89, 90, 91])->sum() != 0 ? collect($weeklyData)->only([88, 89, 90, 91])->sum() : '',
                        'Week40' => $weeklyData['92'] != 0 ? $weeklyData['92'] : '',
                        'Week41' => $weeklyData['93'] != 0 ? $weeklyData['93'] : '',
                        'Week42' => $weeklyData['94'] != 0 ? $weeklyData['94'] : '',
                        'Week43' => $weeklyData['95'] != 0 ? $weeklyData['95'] : '',
                        'Oct' => collect($weeklyData)->only([92, 93, 94, 95])->sum() != 0 ? collect($weeklyData)->only([92, 93, 94, 95])->sum() : '',
                        'Week44' => $weeklyData['96'] != 0 ? $weeklyData['96'] : '',
                        'Week45' => $weeklyData['97'] != 0 ? $weeklyData['97'] : '',
                        'Week46' => $weeklyData['98'] != 0 ? $weeklyData['98'] : '',
                        'Week47' => $weeklyData['99'] != 0 ? $weeklyData['99'] : '',
                        'Week48' => $weeklyData['100'] != 0 ? $weeklyData['100'] : '',
                        'Nov' => collect($weeklyData)->only([96, 97, 98, 99, 100])->sum() != 0 ? collect($weeklyData)->only([96, 97, 98, 99, 100])->sum() : '',
                        'Week49' => $weeklyData['101'] != 0 ? $weeklyData['101'] : '',
                        'Week50' => $weeklyData['102'] != 0 ? $weeklyData['102'] : '',
                        'Week51' => $weeklyData['103'] != 0 ? $weeklyData['103'] : '',
                        'Week52' => $weeklyData['104'] != 0 ? $weeklyData['104'] : '',
                        'Dec' => collect($weeklyData)->only([101, 102, 103, 104])->sum() != 0 ? collect($weeklyData)->only([101, 102, 103, 104])->sum() : '',

                        'GrandTotalByProgram' => $grandTotal,
                    ];
                }
            }
        }

        $grandTotalForAllPrograms = array_sum(array_map('array_sum', $grandTotalByProgram));

        $mappedGroupedClasses[] = [
            'Site' => 'Grand Total',
            'Program' => '',
            'Week1' => $grandTotalByWeek['53'] != 0 ? $grandTotalByWeek['53'] : '',
            'Week2' => $grandTotalByWeek['54'] != 0 ? $grandTotalByWeek['54'] : '',
            'Week3' => $grandTotalByWeek['55'] != 0 ? $grandTotalByWeek['55'] : '',
            'Week4' => $grandTotalByWeek['56'] != 0 ? $grandTotalByWeek['56'] : '',
            'Jan' => collect($grandTotalByWeek)->only([53, 54, 55, 56])->sum() != 0 ? collect($grandTotalByWeek)->only([53, 54, 55, 56])->sum() : '',
            'Week5' => $grandTotalByWeek['57'] != 0 ? $grandTotalByWeek['57'] : '',
            'Week6' => $grandTotalByWeek['58'] != 0 ? $grandTotalByWeek['58'] : '',
            'Week7' => $grandTotalByWeek['59'] != 0 ? $grandTotalByWeek['59'] : '',
            'Week8' => $grandTotalByWeek['60'] != 0 ? $grandTotalByWeek['60'] : '',
            'Feb' => collect($grandTotalByWeek)->only([57, 58, 59, 60])->sum() != 0 ? collect($grandTotalByWeek)->only([57, 58, 59, 60])->sum() : '',
            'Week9' => $grandTotalByWeek['61'] != 0 ? $grandTotalByWeek['61'] : '',
            'Week10' => $grandTotalByWeek['62'] != 0 ? $grandTotalByWeek['62'] : '',
            'Week11' => $grandTotalByWeek['63'] != 0 ? $grandTotalByWeek['63'] : '',
            'Week12' => $grandTotalByWeek['64'] != 0 ? $grandTotalByWeek['64'] : '',
            'Week13' => $grandTotalByWeek['65'] != 0 ? $grandTotalByWeek['65'] : '',
            'Mar' => collect($grandTotalByWeek)->only([61, 62, 63, 64, 65])->sum() != 0 ? collect($grandTotalByWeek)->only([61, 62, 63, 64, 65])->sum() : '',
            'Week14' => $grandTotalByWeek['66'] != 0 ? $grandTotalByWeek['66'] : '',
            'Week15' => $grandTotalByWeek['67'] != 0 ? $grandTotalByWeek['67'] : '',
            'Week16' => $grandTotalByWeek['68'] != 0 ? $grandTotalByWeek['68'] : '',
            'Week17' => $grandTotalByWeek['69'] != 0 ? $grandTotalByWeek['69'] : '',
            'Apr' => collect($grandTotalByWeek)->only([66, 67, 68, 69])->sum() != 0 ? collect($grandTotalByWeek)->only([66, 67, 68, 69])->sum() : '',
            'Week18' => $grandTotalByWeek['70'] != 0 ? $grandTotalByWeek['70'] : '',
            'Week19' => $grandTotalByWeek['71'] != 0 ? $grandTotalByWeek['71'] : '',
            'Week20' => $grandTotalByWeek['72'] != 0 ? $grandTotalByWeek['72'] : '',
            'Week21' => $grandTotalByWeek['73'] != 0 ? $grandTotalByWeek['73'] : '',
            'May' => collect($grandTotalByWeek)->only([70, 71, 72, 73])->sum() != 0 ? collect($grandTotalByWeek)->only([70, 71, 72, 73])->sum() : '',
            'Week22' => $grandTotalByWeek['74'] != 0 ? $grandTotalByWeek['74'] : '',
            'Week23' => $grandTotalByWeek['75'] != 0 ? $grandTotalByWeek['75'] : '',
            'Week24' => $grandTotalByWeek['76'] != 0 ? $grandTotalByWeek['76'] : '',
            'Week25' => $grandTotalByWeek['77'] != 0 ? $grandTotalByWeek['77'] : '',
            'Week26' => $grandTotalByWeek['78'] != 0 ? $grandTotalByWeek['78'] : '',
            'Jun' => collect($grandTotalByWeek)->only([74, 75, 76, 77, 78])->sum() != 0 ? collect($grandTotalByWeek)->only([74, 75, 76, 77, 78])->sum() : '',
            'Week27' => $grandTotalByWeek['79'] != 0 ? $grandTotalByWeek['79'] : '',
            'Week28' => $grandTotalByWeek['80'] != 0 ? $grandTotalByWeek['80'] : '',
            'Week29' => $grandTotalByWeek['81'] != 0 ? $grandTotalByWeek['81'] : '',
            'Week30' => $grandTotalByWeek['82'] != 0 ? $grandTotalByWeek['82'] : '',
            'Jul' => collect($grandTotalByWeek)->only([79, 80, 81, 82])->sum() != 0 ? collect($grandTotalByWeek)->only([79, 80, 81, 82])->sum() : '',
            'Week31' => $grandTotalByWeek['83'] != 0 ? $grandTotalByWeek['83'] : '',
            'Week32' => $grandTotalByWeek['84'] != 0 ? $grandTotalByWeek['84'] : '',
            'Week33' => $grandTotalByWeek['85'] != 0 ? $grandTotalByWeek['85'] : '',
            'Week34' => $grandTotalByWeek['86'] != 0 ? $grandTotalByWeek['86'] : '',
            'Week35' => $grandTotalByWeek['87'] != 0 ? $grandTotalByWeek['87'] : '',
            'Aug' => collect($grandTotalByWeek)->only([83, 84, 85, 86, 87])->sum() != 0 ? collect($grandTotalByWeek)->only([83, 84, 85, 86, 87])->sum() : '',
            'Week36' => $grandTotalByWeek['88'] != 0 ? $grandTotalByWeek['88'] : '',
            'Week37' => $grandTotalByWeek['89'] != 0 ? $grandTotalByWeek['89'] : '',
            'Week38' => $grandTotalByWeek['90'] != 0 ? $grandTotalByWeek['90'] : '',
            'Week39' => $grandTotalByWeek['91'] != 0 ? $grandTotalByWeek['91'] : '',
            'Sep' => collect($grandTotalByWeek)->only([88, 89, 90, 91])->sum() != 0 ? collect($grandTotalByWeek)->only([88, 89, 90, 91])->sum() : '',
            'Week40' => $grandTotalByWeek['92'] != 0 ? $grandTotalByWeek['92'] : '',
            'Week41' => $grandTotalByWeek['93'] != 0 ? $grandTotalByWeek['93'] : '',
            'Week42' => $grandTotalByWeek['94'] != 0 ? $grandTotalByWeek['94'] : '',
            'Week43' => $grandTotalByWeek['95'] != 0 ? $grandTotalByWeek['95'] : '',
            'Oct' => collect($grandTotalByWeek)->only([92, 93, 94, 95])->sum() != 0 ? collect($grandTotalByWeek)->only([92, 93, 94, 95])->sum() : '',
            'Week44' => $grandTotalByWeek['96'] != 0 ? $grandTotalByWeek['96'] : '',
            'Week45' => $grandTotalByWeek['97'] != 0 ? $grandTotalByWeek['97'] : '',
            'Week46' => $grandTotalByWeek['98'] != 0 ? $grandTotalByWeek['98'] : '',
            'Week47' => $grandTotalByWeek['99'] != 0 ? $grandTotalByWeek['99'] : '',
            'Week48' => $grandTotalByWeek['100'] != 0 ? $grandTotalByWeek['100'] : '',
            'Nov' => collect($grandTotalByWeek)->only([96, 97, 98, 99, 100])->sum() != 0 ? collect($grandTotalByWeek)->only([96, 97, 98, 99, 100])->sum() : '',
            'Week49' => $grandTotalByWeek['101'] != 0 ? $grandTotalByWeek['101'] : '',
            'Week50' => $grandTotalByWeek['102'] != 0 ? $grandTotalByWeek['102'] : '',
            'Week51' => $grandTotalByWeek['103'] != 0 ? $grandTotalByWeek['103'] : '',
            'Week52' => $grandTotalByWeek['104'] != 0 ? $grandTotalByWeek['104'] : '',
            'Dec' => collect($grandTotalByWeek)->only([101, 102, 103, 104])->sum() != 0 ? collect($grandTotalByWeek)->only([101, 102, 103, 104])->sum() : '',
            'GrandTotalByProgram' => $grandTotalForAllPrograms,
        ];

        $response = [
            'classes' => $mappedGroupedClasses,
        ];

        return Excel::download(new dashboardClassesExport($response), 'filtered_data.xlsx');
    }

    public function dashboardClassesExportJamaica4(Request $request)
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
            ->when(true, function ($query) {
                $query->whereHas('site', function ($subquery) {
                    $subquery->where('is_active', 1);
                });

                $query->whereHas('site', function ($subquery) {
                    $subquery->where('country', 'Jamaica');
                });
            })

            ->where('is_active', 1) // You can keep or remove this line based on your requirements
            ->get();

        $year = 2024;
        $dateRanges = DateRange::where('year', $year)
            ->get();

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
                    '53' => 0,
                    '54' => 0,
                    '55' => 0,
                    '56' => 0,
                    '57' => 0,
                    '58' => 0,
                    '59' => 0,
                    '60' => 0,
                    '61' => 0,
                    '62' => 0,
                    '63' => 0,
                    '64' => 0,
                    '65' => 0,
                    '66' => 0,
                    '67' => 0,
                    '68' => 0,
                    '69' => 0,
                    '70' => 0,
                    '71' => 0,
                    '72' => 0,
                    '73' => 0,
                    '74' => 0,
                    '75' => 0,
                    '76' => 0,
                    '77' => 0,
                    '78' => 0,
                    '79' => 0,
                    '80' => 0,
                    '81' => 0,
                    '82' => 0,
                    '83' => 0,
                    '84' => 0,
                    '85' => 0,
                    '86' => 0,
                    '87' => 0,
                    '88' => 0,
                    '89' => 0,
                    '90' => 0,
                    '91' => 0,
                    '92' => 0,
                    '93' => 0,
                    '94' => 0,
                    '95' => 0,
                    '96' => 0,
                    '97' => 0,
                    '98' => 0,
                    '99' => 0,
                    '100' => 0,
                    '101' => 0,
                    '102' => 0,
                    '103' => 0,
                    '104' => 0,
                ];

                foreach ($programData as $week => $weekData) {
                    $weeklyData[$week] = isset($weekData['total_target']) ? $weekData['total_target'] : 0;
                }
                $grandTotal = $grandTotalByProgram[$siteName][$programName];
                if ($grandTotal != 0) {
                    $mappedGroupedClasses[] = [
                        'Site' => $siteName,
                        'Program' => $programName,
                        'Week1' => $weeklyData['53'] != 0 ? $weeklyData['53'] : '',
                        'Week2' => $weeklyData['54'] != 0 ? $weeklyData['54'] : '',
                        'Week3' => $weeklyData['55'] != 0 ? $weeklyData['55'] : '',
                        'Week4' => $weeklyData['56'] != 0 ? $weeklyData['56'] : '',
                        'Jan' => collect($weeklyData)->only([53, 54, 55, 56])->sum() != 0 ? collect($weeklyData)->only([53, 54, 55, 56])->sum() : '',
                        'Week5' => $weeklyData['57'] != 0 ? $weeklyData['57'] : '',
                        'Week6' => $weeklyData['58'] != 0 ? $weeklyData['58'] : '',
                        'Week7' => $weeklyData['59'] != 0 ? $weeklyData['59'] : '',
                        'Week8' => $weeklyData['60'] != 0 ? $weeklyData['60'] : '',
                        'Feb' => collect($weeklyData)->only([57, 58, 59, 60])->sum() != 0 ? collect($weeklyData)->only([57, 58, 59, 60])->sum() : '',
                        'Week9' => $weeklyData['61'] != 0 ? $weeklyData['61'] : '',
                        'Week10' => $weeklyData['62'] != 0 ? $weeklyData['62'] : '',
                        'Week11' => $weeklyData['63'] != 0 ? $weeklyData['63'] : '',
                        'Week12' => $weeklyData['64'] != 0 ? $weeklyData['64'] : '',
                        'Week13' => $weeklyData['65'] != 0 ? $weeklyData['65'] : '',
                        'Mar' => collect($weeklyData)->only([61, 62, 63, 64, 65])->sum() != 0 ? collect($weeklyData)->only([61, 62, 63, 64, 65])->sum() : '',
                        'Week14' => $weeklyData['66'] != 0 ? $weeklyData['66'] : '',
                        'Week15' => $weeklyData['67'] != 0 ? $weeklyData['67'] : '',
                        'Week16' => $weeklyData['68'] != 0 ? $weeklyData['68'] : '',
                        'Week17' => $weeklyData['69'] != 0 ? $weeklyData['69'] : '',
                        'Apr' => collect($weeklyData)->only([66, 67, 68, 69])->sum() != 0 ? collect($weeklyData)->only([66, 67, 68, 69])->sum() : '',
                        'Week18' => $weeklyData['70'] != 0 ? $weeklyData['70'] : '',
                        'Week19' => $weeklyData['71'] != 0 ? $weeklyData['71'] : '',
                        'Week20' => $weeklyData['72'] != 0 ? $weeklyData['72'] : '',
                        'Week21' => $weeklyData['73'] != 0 ? $weeklyData['73'] : '',
                        'May' => collect($weeklyData)->only([70, 71, 72, 73])->sum() != 0 ? collect($weeklyData)->only([70, 71, 72, 73])->sum() : '',
                        'Week22' => $weeklyData['74'] != 0 ? $weeklyData['74'] : '',
                        'Week23' => $weeklyData['75'] != 0 ? $weeklyData['75'] : '',
                        'Week24' => $weeklyData['76'] != 0 ? $weeklyData['76'] : '',
                        'Week25' => $weeklyData['77'] != 0 ? $weeklyData['77'] : '',
                        'Week26' => $weeklyData['78'] != 0 ? $weeklyData['78'] : '',
                        'Jun' => collect($weeklyData)->only([74, 75, 76, 77, 78])->sum() != 0 ? collect($weeklyData)->only([74, 75, 76, 77, 78])->sum() : '',
                        'Week27' => $weeklyData['79'] != 0 ? $weeklyData['79'] : '',
                        'Week28' => $weeklyData['80'] != 0 ? $weeklyData['80'] : '',
                        'Week29' => $weeklyData['81'] != 0 ? $weeklyData['81'] : '',
                        'Week30' => $weeklyData['82'] != 0 ? $weeklyData['82'] : '',
                        'Jul' => collect($weeklyData)->only([79, 80, 81, 82])->sum() != 0 ? collect($weeklyData)->only([79, 80, 81, 82])->sum() : '',
                        'Week31' => $weeklyData['83'] != 0 ? $weeklyData['83'] : '',
                        'Week32' => $weeklyData['84'] != 0 ? $weeklyData['84'] : '',
                        'Week33' => $weeklyData['85'] != 0 ? $weeklyData['85'] : '',
                        'Week34' => $weeklyData['86'] != 0 ? $weeklyData['86'] : '',
                        'Week35' => $weeklyData['87'] != 0 ? $weeklyData['87'] : '',
                        'Aug' => collect($weeklyData)->only([83, 84, 85, 86, 87])->sum() != 0 ? collect($weeklyData)->only([83, 84, 85, 86, 87])->sum() : '',
                        'Week36' => $weeklyData['88'] != 0 ? $weeklyData['88'] : '',
                        'Week37' => $weeklyData['89'] != 0 ? $weeklyData['89'] : '',
                        'Week38' => $weeklyData['90'] != 0 ? $weeklyData['90'] : '',
                        'Week39' => $weeklyData['91'] != 0 ? $weeklyData['91'] : '',
                        'Sep' => collect($weeklyData)->only([88, 89, 90, 91])->sum() != 0 ? collect($weeklyData)->only([88, 89, 90, 91])->sum() : '',
                        'Week40' => $weeklyData['92'] != 0 ? $weeklyData['92'] : '',
                        'Week41' => $weeklyData['93'] != 0 ? $weeklyData['93'] : '',
                        'Week42' => $weeklyData['94'] != 0 ? $weeklyData['94'] : '',
                        'Week43' => $weeklyData['95'] != 0 ? $weeklyData['95'] : '',
                        'Oct' => collect($weeklyData)->only([92, 93, 94, 95])->sum() != 0 ? collect($weeklyData)->only([92, 93, 94, 95])->sum() : '',
                        'Week44' => $weeklyData['96'] != 0 ? $weeklyData['96'] : '',
                        'Week45' => $weeklyData['97'] != 0 ? $weeklyData['97'] : '',
                        'Week46' => $weeklyData['98'] != 0 ? $weeklyData['98'] : '',
                        'Week47' => $weeklyData['99'] != 0 ? $weeklyData['99'] : '',
                        'Week48' => $weeklyData['100'] != 0 ? $weeklyData['100'] : '',
                        'Nov' => collect($weeklyData)->only([96, 97, 98, 99, 100])->sum() != 0 ? collect($weeklyData)->only([96, 97, 98, 99, 100])->sum() : '',
                        'Week49' => $weeklyData['101'] != 0 ? $weeklyData['101'] : '',
                        'Week50' => $weeklyData['102'] != 0 ? $weeklyData['102'] : '',
                        'Week51' => $weeklyData['103'] != 0 ? $weeklyData['103'] : '',
                        'Week52' => $weeklyData['104'] != 0 ? $weeklyData['104'] : '',
                        'Dec' => collect($weeklyData)->only([101, 102, 103, 104])->sum() != 0 ? collect($weeklyData)->only([101, 102, 103, 104])->sum() : '',

                        'GrandTotalByProgram' => $grandTotal,
                    ];
                }
            }
        }

        $grandTotalForAllPrograms = array_sum(array_map('array_sum', $grandTotalByProgram));

        $mappedGroupedClasses[] = [
            'Site' => 'Grand Total',
            'Program' => '',
            'Week1' => $grandTotalByWeek['53'] != 0 ? $grandTotalByWeek['53'] : '',
            'Week2' => $grandTotalByWeek['54'] != 0 ? $grandTotalByWeek['54'] : '',
            'Week3' => $grandTotalByWeek['55'] != 0 ? $grandTotalByWeek['55'] : '',
            'Week4' => $grandTotalByWeek['56'] != 0 ? $grandTotalByWeek['56'] : '',
            'Jan' => collect($grandTotalByWeek)->only([53, 54, 55, 56])->sum() != 0 ? collect($grandTotalByWeek)->only([53, 54, 55, 56])->sum() : '',
            'Week5' => $grandTotalByWeek['57'] != 0 ? $grandTotalByWeek['57'] : '',
            'Week6' => $grandTotalByWeek['58'] != 0 ? $grandTotalByWeek['58'] : '',
            'Week7' => $grandTotalByWeek['59'] != 0 ? $grandTotalByWeek['59'] : '',
            'Week8' => $grandTotalByWeek['60'] != 0 ? $grandTotalByWeek['60'] : '',
            'Feb' => collect($grandTotalByWeek)->only([57, 58, 59, 60])->sum() != 0 ? collect($grandTotalByWeek)->only([57, 58, 59, 60])->sum() : '',
            'Week9' => $grandTotalByWeek['61'] != 0 ? $grandTotalByWeek['61'] : '',
            'Week10' => $grandTotalByWeek['62'] != 0 ? $grandTotalByWeek['62'] : '',
            'Week11' => $grandTotalByWeek['63'] != 0 ? $grandTotalByWeek['63'] : '',
            'Week12' => $grandTotalByWeek['64'] != 0 ? $grandTotalByWeek['64'] : '',
            'Week13' => $grandTotalByWeek['65'] != 0 ? $grandTotalByWeek['65'] : '',
            'Mar' => collect($grandTotalByWeek)->only([61, 62, 63, 64, 65])->sum() != 0 ? collect($grandTotalByWeek)->only([61, 62, 63, 64, 65])->sum() : '',
            'Week14' => $grandTotalByWeek['66'] != 0 ? $grandTotalByWeek['66'] : '',
            'Week15' => $grandTotalByWeek['67'] != 0 ? $grandTotalByWeek['67'] : '',
            'Week16' => $grandTotalByWeek['68'] != 0 ? $grandTotalByWeek['68'] : '',
            'Week17' => $grandTotalByWeek['69'] != 0 ? $grandTotalByWeek['69'] : '',
            'Apr' => collect($grandTotalByWeek)->only([66, 67, 68, 69])->sum() != 0 ? collect($grandTotalByWeek)->only([66, 67, 68, 69])->sum() : '',
            'Week18' => $grandTotalByWeek['70'] != 0 ? $grandTotalByWeek['70'] : '',
            'Week19' => $grandTotalByWeek['71'] != 0 ? $grandTotalByWeek['71'] : '',
            'Week20' => $grandTotalByWeek['72'] != 0 ? $grandTotalByWeek['72'] : '',
            'Week21' => $grandTotalByWeek['73'] != 0 ? $grandTotalByWeek['73'] : '',
            'May' => collect($grandTotalByWeek)->only([70, 71, 72, 73])->sum() != 0 ? collect($grandTotalByWeek)->only([70, 71, 72, 73])->sum() : '',
            'Week22' => $grandTotalByWeek['74'] != 0 ? $grandTotalByWeek['74'] : '',
            'Week23' => $grandTotalByWeek['75'] != 0 ? $grandTotalByWeek['75'] : '',
            'Week24' => $grandTotalByWeek['76'] != 0 ? $grandTotalByWeek['76'] : '',
            'Week25' => $grandTotalByWeek['77'] != 0 ? $grandTotalByWeek['77'] : '',
            'Week26' => $grandTotalByWeek['78'] != 0 ? $grandTotalByWeek['78'] : '',
            'Jun' => collect($grandTotalByWeek)->only([74, 75, 76, 77, 78])->sum() != 0 ? collect($grandTotalByWeek)->only([74, 75, 76, 77, 78])->sum() : '',
            'Week27' => $grandTotalByWeek['79'] != 0 ? $grandTotalByWeek['79'] : '',
            'Week28' => $grandTotalByWeek['80'] != 0 ? $grandTotalByWeek['80'] : '',
            'Week29' => $grandTotalByWeek['81'] != 0 ? $grandTotalByWeek['81'] : '',
            'Week30' => $grandTotalByWeek['82'] != 0 ? $grandTotalByWeek['82'] : '',
            'Jul' => collect($grandTotalByWeek)->only([79, 80, 81, 82])->sum() != 0 ? collect($grandTotalByWeek)->only([79, 80, 81, 82])->sum() : '',
            'Week31' => $grandTotalByWeek['83'] != 0 ? $grandTotalByWeek['83'] : '',
            'Week32' => $grandTotalByWeek['84'] != 0 ? $grandTotalByWeek['84'] : '',
            'Week33' => $grandTotalByWeek['85'] != 0 ? $grandTotalByWeek['85'] : '',
            'Week34' => $grandTotalByWeek['86'] != 0 ? $grandTotalByWeek['86'] : '',
            'Week35' => $grandTotalByWeek['87'] != 0 ? $grandTotalByWeek['87'] : '',
            'Aug' => collect($grandTotalByWeek)->only([83, 84, 85, 86, 87])->sum() != 0 ? collect($grandTotalByWeek)->only([83, 84, 85, 86, 87])->sum() : '',
            'Week36' => $grandTotalByWeek['88'] != 0 ? $grandTotalByWeek['88'] : '',
            'Week37' => $grandTotalByWeek['89'] != 0 ? $grandTotalByWeek['89'] : '',
            'Week38' => $grandTotalByWeek['90'] != 0 ? $grandTotalByWeek['90'] : '',
            'Week39' => $grandTotalByWeek['91'] != 0 ? $grandTotalByWeek['91'] : '',
            'Sep' => collect($grandTotalByWeek)->only([88, 89, 90, 91])->sum() != 0 ? collect($grandTotalByWeek)->only([88, 89, 90, 91])->sum() : '',
            'Week40' => $grandTotalByWeek['92'] != 0 ? $grandTotalByWeek['92'] : '',
            'Week41' => $grandTotalByWeek['93'] != 0 ? $grandTotalByWeek['93'] : '',
            'Week42' => $grandTotalByWeek['94'] != 0 ? $grandTotalByWeek['94'] : '',
            'Week43' => $grandTotalByWeek['95'] != 0 ? $grandTotalByWeek['95'] : '',
            'Oct' => collect($grandTotalByWeek)->only([92, 93, 94, 95])->sum() != 0 ? collect($grandTotalByWeek)->only([92, 93, 94, 95])->sum() : '',
            'Week44' => $grandTotalByWeek['96'] != 0 ? $grandTotalByWeek['96'] : '',
            'Week45' => $grandTotalByWeek['97'] != 0 ? $grandTotalByWeek['97'] : '',
            'Week46' => $grandTotalByWeek['98'] != 0 ? $grandTotalByWeek['98'] : '',
            'Week47' => $grandTotalByWeek['99'] != 0 ? $grandTotalByWeek['99'] : '',
            'Week48' => $grandTotalByWeek['100'] != 0 ? $grandTotalByWeek['100'] : '',
            'Nov' => collect($grandTotalByWeek)->only([96, 97, 98, 99, 100])->sum() != 0 ? collect($grandTotalByWeek)->only([96, 97, 98, 99, 100])->sum() : '',
            'Week49' => $grandTotalByWeek['101'] != 0 ? $grandTotalByWeek['101'] : '',
            'Week50' => $grandTotalByWeek['102'] != 0 ? $grandTotalByWeek['102'] : '',
            'Week51' => $grandTotalByWeek['103'] != 0 ? $grandTotalByWeek['103'] : '',
            'Week52' => $grandTotalByWeek['104'] != 0 ? $grandTotalByWeek['104'] : '',
            'Dec' => collect($grandTotalByWeek)->only([101, 102, 103, 104])->sum() != 0 ? collect($grandTotalByWeek)->only([101, 102, 103, 104])->sum() : '',
            'GrandTotalByProgram' => $grandTotalForAllPrograms,
        ];

        $response = [
            'classes' => $mappedGroupedClasses,
        ];

        return Excel::download(new dashboardClassesExport($response), 'filtered_data.xlsx');
    }
    public function dashboardClassesExportGuatemala(Request $request)
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
            ->when(true, function ($query) {
                $query->whereHas('site', function ($subquery) {
                    $subquery->where('is_active', 1);
                });

                $query->whereHas('site', function ($subquery) {
                    $subquery->where('country', 'Guatemala');
                });
            })

            ->where('is_active', 1) // You can keep or remove this line based on your requirements
            ->get();

        $year = 2024;
        $dateRanges = DateRange::where('year', $year)
            ->get();

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
                    '53' => 0,
                    '54' => 0,
                    '55' => 0,
                    '56' => 0,
                    '57' => 0,
                    '58' => 0,
                    '59' => 0,
                    '60' => 0,
                    '61' => 0,
                    '62' => 0,
                    '63' => 0,
                    '64' => 0,
                    '65' => 0,
                    '66' => 0,
                    '67' => 0,
                    '68' => 0,
                    '69' => 0,
                    '70' => 0,
                    '71' => 0,
                    '72' => 0,
                    '73' => 0,
                    '74' => 0,
                    '75' => 0,
                    '76' => 0,
                    '77' => 0,
                    '78' => 0,
                    '79' => 0,
                    '80' => 0,
                    '81' => 0,
                    '82' => 0,
                    '83' => 0,
                    '84' => 0,
                    '85' => 0,
                    '86' => 0,
                    '87' => 0,
                    '88' => 0,
                    '89' => 0,
                    '90' => 0,
                    '91' => 0,
                    '92' => 0,
                    '93' => 0,
                    '94' => 0,
                    '95' => 0,
                    '96' => 0,
                    '97' => 0,
                    '98' => 0,
                    '99' => 0,
                    '100' => 0,
                    '101' => 0,
                    '102' => 0,
                    '103' => 0,
                    '104' => 0,
                ];

                foreach ($programData as $week => $weekData) {
                    $weeklyData[$week] = isset($weekData['total_target']) ? $weekData['total_target'] : 0;
                }
                $grandTotal = $grandTotalByProgram[$siteName][$programName];
                if ($grandTotal != 0) {
                    $mappedGroupedClasses[] = [
                        'Site' => $siteName,
                        'Program' => $programName,
                        'Week1' => $weeklyData['53'] != 0 ? $weeklyData['53'] : '',
                        'Week2' => $weeklyData['54'] != 0 ? $weeklyData['54'] : '',
                        'Week3' => $weeklyData['55'] != 0 ? $weeklyData['55'] : '',
                        'Week4' => $weeklyData['56'] != 0 ? $weeklyData['56'] : '',
                        'Jan' => collect($weeklyData)->only([53, 54, 55, 56])->sum() != 0 ? collect($weeklyData)->only([53, 54, 55, 56])->sum() : '',
                        'Week5' => $weeklyData['57'] != 0 ? $weeklyData['57'] : '',
                        'Week6' => $weeklyData['58'] != 0 ? $weeklyData['58'] : '',
                        'Week7' => $weeklyData['59'] != 0 ? $weeklyData['59'] : '',
                        'Week8' => $weeklyData['60'] != 0 ? $weeklyData['60'] : '',
                        'Feb' => collect($weeklyData)->only([57, 58, 59, 60])->sum() != 0 ? collect($weeklyData)->only([57, 58, 59, 60])->sum() : '',
                        'Week9' => $weeklyData['61'] != 0 ? $weeklyData['61'] : '',
                        'Week10' => $weeklyData['62'] != 0 ? $weeklyData['62'] : '',
                        'Week11' => $weeklyData['63'] != 0 ? $weeklyData['63'] : '',
                        'Week12' => $weeklyData['64'] != 0 ? $weeklyData['64'] : '',
                        'Week13' => $weeklyData['65'] != 0 ? $weeklyData['65'] : '',
                        'Mar' => collect($weeklyData)->only([61, 62, 63, 64, 65])->sum() != 0 ? collect($weeklyData)->only([61, 62, 63, 64, 65])->sum() : '',
                        'Week14' => $weeklyData['66'] != 0 ? $weeklyData['66'] : '',
                        'Week15' => $weeklyData['67'] != 0 ? $weeklyData['67'] : '',
                        'Week16' => $weeklyData['68'] != 0 ? $weeklyData['68'] : '',
                        'Week17' => $weeklyData['69'] != 0 ? $weeklyData['69'] : '',
                        'Apr' => collect($weeklyData)->only([66, 67, 68, 69])->sum() != 0 ? collect($weeklyData)->only([66, 67, 68, 69])->sum() : '',
                        'Week18' => $weeklyData['70'] != 0 ? $weeklyData['70'] : '',
                        'Week19' => $weeklyData['71'] != 0 ? $weeklyData['71'] : '',
                        'Week20' => $weeklyData['72'] != 0 ? $weeklyData['72'] : '',
                        'Week21' => $weeklyData['73'] != 0 ? $weeklyData['73'] : '',
                        'May' => collect($weeklyData)->only([70, 71, 72, 73])->sum() != 0 ? collect($weeklyData)->only([70, 71, 72, 73])->sum() : '',
                        'Week22' => $weeklyData['74'] != 0 ? $weeklyData['74'] : '',
                        'Week23' => $weeklyData['75'] != 0 ? $weeklyData['75'] : '',
                        'Week24' => $weeklyData['76'] != 0 ? $weeklyData['76'] : '',
                        'Week25' => $weeklyData['77'] != 0 ? $weeklyData['77'] : '',
                        'Week26' => $weeklyData['78'] != 0 ? $weeklyData['78'] : '',
                        'Jun' => collect($weeklyData)->only([74, 75, 76, 77, 78])->sum() != 0 ? collect($weeklyData)->only([74, 75, 76, 77, 78])->sum() : '',
                        'Week27' => $weeklyData['79'] != 0 ? $weeklyData['79'] : '',
                        'Week28' => $weeklyData['80'] != 0 ? $weeklyData['80'] : '',
                        'Week29' => $weeklyData['81'] != 0 ? $weeklyData['81'] : '',
                        'Week30' => $weeklyData['82'] != 0 ? $weeklyData['82'] : '',
                        'Jul' => collect($weeklyData)->only([79, 80, 81, 82])->sum() != 0 ? collect($weeklyData)->only([79, 80, 81, 82])->sum() : '',
                        'Week31' => $weeklyData['83'] != 0 ? $weeklyData['83'] : '',
                        'Week32' => $weeklyData['84'] != 0 ? $weeklyData['84'] : '',
                        'Week33' => $weeklyData['85'] != 0 ? $weeklyData['85'] : '',
                        'Week34' => $weeklyData['86'] != 0 ? $weeklyData['86'] : '',
                        'Week35' => $weeklyData['87'] != 0 ? $weeklyData['87'] : '',
                        'Aug' => collect($weeklyData)->only([83, 84, 85, 86, 87])->sum() != 0 ? collect($weeklyData)->only([83, 84, 85, 86, 87])->sum() : '',
                        'Week36' => $weeklyData['88'] != 0 ? $weeklyData['88'] : '',
                        'Week37' => $weeklyData['89'] != 0 ? $weeklyData['89'] : '',
                        'Week38' => $weeklyData['90'] != 0 ? $weeklyData['90'] : '',
                        'Week39' => $weeklyData['91'] != 0 ? $weeklyData['91'] : '',
                        'Sep' => collect($weeklyData)->only([88, 89, 90, 91])->sum() != 0 ? collect($weeklyData)->only([88, 89, 90, 91])->sum() : '',
                        'Week40' => $weeklyData['92'] != 0 ? $weeklyData['92'] : '',
                        'Week41' => $weeklyData['93'] != 0 ? $weeklyData['93'] : '',
                        'Week42' => $weeklyData['94'] != 0 ? $weeklyData['94'] : '',
                        'Week43' => $weeklyData['95'] != 0 ? $weeklyData['95'] : '',
                        'Oct' => collect($weeklyData)->only([92, 93, 94, 95])->sum() != 0 ? collect($weeklyData)->only([92, 93, 94, 95])->sum() : '',
                        'Week44' => $weeklyData['96'] != 0 ? $weeklyData['96'] : '',
                        'Week45' => $weeklyData['97'] != 0 ? $weeklyData['97'] : '',
                        'Week46' => $weeklyData['98'] != 0 ? $weeklyData['98'] : '',
                        'Week47' => $weeklyData['99'] != 0 ? $weeklyData['99'] : '',
                        'Week48' => $weeklyData['100'] != 0 ? $weeklyData['100'] : '',
                        'Nov' => collect($weeklyData)->only([96, 97, 98, 99, 100])->sum() != 0 ? collect($weeklyData)->only([96, 97, 98, 99, 100])->sum() : '',
                        'Week49' => $weeklyData['101'] != 0 ? $weeklyData['101'] : '',
                        'Week50' => $weeklyData['102'] != 0 ? $weeklyData['102'] : '',
                        'Week51' => $weeklyData['103'] != 0 ? $weeklyData['103'] : '',
                        'Week52' => $weeklyData['104'] != 0 ? $weeklyData['104'] : '',
                        'Dec' => collect($weeklyData)->only([101, 102, 103, 104])->sum() != 0 ? collect($weeklyData)->only([101, 102, 103, 104])->sum() : '',

                        'GrandTotalByProgram' => $grandTotal,
                    ];
                }
            }
        }

        $grandTotalForAllPrograms = array_sum(array_map('array_sum', $grandTotalByProgram));

        $mappedGroupedClasses[] = [
            'Site' => 'Grand Total',
            'Program' => '',
            'Week1' => $grandTotalByWeek['53'] != 0 ? $grandTotalByWeek['53'] : '',
            'Week2' => $grandTotalByWeek['54'] != 0 ? $grandTotalByWeek['54'] : '',
            'Week3' => $grandTotalByWeek['55'] != 0 ? $grandTotalByWeek['55'] : '',
            'Week4' => $grandTotalByWeek['56'] != 0 ? $grandTotalByWeek['56'] : '',
            'Jan' => collect($grandTotalByWeek)->only([53, 54, 55, 56])->sum() != 0 ? collect($grandTotalByWeek)->only([53, 54, 55, 56])->sum() : '',
            'Week5' => $grandTotalByWeek['57'] != 0 ? $grandTotalByWeek['57'] : '',
            'Week6' => $grandTotalByWeek['58'] != 0 ? $grandTotalByWeek['58'] : '',
            'Week7' => $grandTotalByWeek['59'] != 0 ? $grandTotalByWeek['59'] : '',
            'Week8' => $grandTotalByWeek['60'] != 0 ? $grandTotalByWeek['60'] : '',
            'Feb' => collect($grandTotalByWeek)->only([57, 58, 59, 60])->sum() != 0 ? collect($grandTotalByWeek)->only([57, 58, 59, 60])->sum() : '',
            'Week9' => $grandTotalByWeek['61'] != 0 ? $grandTotalByWeek['61'] : '',
            'Week10' => $grandTotalByWeek['62'] != 0 ? $grandTotalByWeek['62'] : '',
            'Week11' => $grandTotalByWeek['63'] != 0 ? $grandTotalByWeek['63'] : '',
            'Week12' => $grandTotalByWeek['64'] != 0 ? $grandTotalByWeek['64'] : '',
            'Week13' => $grandTotalByWeek['65'] != 0 ? $grandTotalByWeek['65'] : '',
            'Mar' => collect($grandTotalByWeek)->only([61, 62, 63, 64, 65])->sum() != 0 ? collect($grandTotalByWeek)->only([61, 62, 63, 64, 65])->sum() : '',
            'Week14' => $grandTotalByWeek['66'] != 0 ? $grandTotalByWeek['66'] : '',
            'Week15' => $grandTotalByWeek['67'] != 0 ? $grandTotalByWeek['67'] : '',
            'Week16' => $grandTotalByWeek['68'] != 0 ? $grandTotalByWeek['68'] : '',
            'Week17' => $grandTotalByWeek['69'] != 0 ? $grandTotalByWeek['69'] : '',
            'Apr' => collect($grandTotalByWeek)->only([66, 67, 68, 69])->sum() != 0 ? collect($grandTotalByWeek)->only([66, 67, 68, 69])->sum() : '',
            'Week18' => $grandTotalByWeek['70'] != 0 ? $grandTotalByWeek['70'] : '',
            'Week19' => $grandTotalByWeek['71'] != 0 ? $grandTotalByWeek['71'] : '',
            'Week20' => $grandTotalByWeek['72'] != 0 ? $grandTotalByWeek['72'] : '',
            'Week21' => $grandTotalByWeek['73'] != 0 ? $grandTotalByWeek['73'] : '',
            'May' => collect($grandTotalByWeek)->only([70, 71, 72, 73])->sum() != 0 ? collect($grandTotalByWeek)->only([70, 71, 72, 73])->sum() : '',
            'Week22' => $grandTotalByWeek['74'] != 0 ? $grandTotalByWeek['74'] : '',
            'Week23' => $grandTotalByWeek['75'] != 0 ? $grandTotalByWeek['75'] : '',
            'Week24' => $grandTotalByWeek['76'] != 0 ? $grandTotalByWeek['76'] : '',
            'Week25' => $grandTotalByWeek['77'] != 0 ? $grandTotalByWeek['77'] : '',
            'Week26' => $grandTotalByWeek['78'] != 0 ? $grandTotalByWeek['78'] : '',
            'Jun' => collect($grandTotalByWeek)->only([74, 75, 76, 77, 78])->sum() != 0 ? collect($grandTotalByWeek)->only([74, 75, 76, 77, 78])->sum() : '',
            'Week27' => $grandTotalByWeek['79'] != 0 ? $grandTotalByWeek['79'] : '',
            'Week28' => $grandTotalByWeek['80'] != 0 ? $grandTotalByWeek['80'] : '',
            'Week29' => $grandTotalByWeek['81'] != 0 ? $grandTotalByWeek['81'] : '',
            'Week30' => $grandTotalByWeek['82'] != 0 ? $grandTotalByWeek['82'] : '',
            'Jul' => collect($grandTotalByWeek)->only([79, 80, 81, 82])->sum() != 0 ? collect($grandTotalByWeek)->only([79, 80, 81, 82])->sum() : '',
            'Week31' => $grandTotalByWeek['83'] != 0 ? $grandTotalByWeek['83'] : '',
            'Week32' => $grandTotalByWeek['84'] != 0 ? $grandTotalByWeek['84'] : '',
            'Week33' => $grandTotalByWeek['85'] != 0 ? $grandTotalByWeek['85'] : '',
            'Week34' => $grandTotalByWeek['86'] != 0 ? $grandTotalByWeek['86'] : '',
            'Week35' => $grandTotalByWeek['87'] != 0 ? $grandTotalByWeek['87'] : '',
            'Aug' => collect($grandTotalByWeek)->only([83, 84, 85, 86, 87])->sum() != 0 ? collect($grandTotalByWeek)->only([83, 84, 85, 86, 87])->sum() : '',
            'Week36' => $grandTotalByWeek['88'] != 0 ? $grandTotalByWeek['88'] : '',
            'Week37' => $grandTotalByWeek['89'] != 0 ? $grandTotalByWeek['89'] : '',
            'Week38' => $grandTotalByWeek['90'] != 0 ? $grandTotalByWeek['90'] : '',
            'Week39' => $grandTotalByWeek['91'] != 0 ? $grandTotalByWeek['91'] : '',
            'Sep' => collect($grandTotalByWeek)->only([88, 89, 90, 91])->sum() != 0 ? collect($grandTotalByWeek)->only([88, 89, 90, 91])->sum() : '',
            'Week40' => $grandTotalByWeek['92'] != 0 ? $grandTotalByWeek['92'] : '',
            'Week41' => $grandTotalByWeek['93'] != 0 ? $grandTotalByWeek['93'] : '',
            'Week42' => $grandTotalByWeek['94'] != 0 ? $grandTotalByWeek['94'] : '',
            'Week43' => $grandTotalByWeek['95'] != 0 ? $grandTotalByWeek['95'] : '',
            'Oct' => collect($grandTotalByWeek)->only([92, 93, 94, 95])->sum() != 0 ? collect($grandTotalByWeek)->only([92, 93, 94, 95])->sum() : '',
            'Week44' => $grandTotalByWeek['96'] != 0 ? $grandTotalByWeek['96'] : '',
            'Week45' => $grandTotalByWeek['97'] != 0 ? $grandTotalByWeek['97'] : '',
            'Week46' => $grandTotalByWeek['98'] != 0 ? $grandTotalByWeek['98'] : '',
            'Week47' => $grandTotalByWeek['99'] != 0 ? $grandTotalByWeek['99'] : '',
            'Week48' => $grandTotalByWeek['100'] != 0 ? $grandTotalByWeek['100'] : '',
            'Nov' => collect($grandTotalByWeek)->only([96, 97, 98, 99, 100])->sum() != 0 ? collect($grandTotalByWeek)->only([96, 97, 98, 99, 100])->sum() : '',
            'Week49' => $grandTotalByWeek['101'] != 0 ? $grandTotalByWeek['101'] : '',
            'Week50' => $grandTotalByWeek['102'] != 0 ? $grandTotalByWeek['102'] : '',
            'Week51' => $grandTotalByWeek['103'] != 0 ? $grandTotalByWeek['103'] : '',
            'Week52' => $grandTotalByWeek['104'] != 0 ? $grandTotalByWeek['104'] : '',
            'Dec' => collect($grandTotalByWeek)->only([101, 102, 103, 104])->sum() != 0 ? collect($grandTotalByWeek)->only([101, 102, 103, 104])->sum() : '',
            'GrandTotalByProgram' => $grandTotalForAllPrograms,
        ];

        $response = [
            'classes' => $mappedGroupedClasses,
        ];

        return Excel::download(new dashboardClassesExport($response), 'filtered_data.xlsx');
    }
    public function dashboardClassesExportGuatemala2(Request $request)
    {
        $query = Classes::with(['site', 'program', 'dateRange', 'createdByUser', 'updatedByUser', 'cancelledByUser'])
            ->whereHas('site', function ($query) use ($request) {
                $query->where('country', '=', 'Guatemala')
                    ->where('is_active', '=', 1);
            })
            ->whereHas('program', function ($query) use ($request) {
                $query->where('is_active', '=', 1);
            })
            ->whereHas('dateRange', function ($query) use ($request) {
                $query->where('year', '=', '2024');
            });

        if ($request->has('sites_selected')) {
            $sitesSelected = $request->input('sites_selected');

            if (!empty($sitesSelected)) {
                $query->where('site_id', $sitesSelected);
            }
        }

        if ($request->has('programs_selected')) {
            $programsSelected = $request->input('programs_selected');

            if (!empty($programsSelected)) {
                $query->where('program_id', $programsSelected);
            }
        }

        if ($request->has('month_selected')) {
            $monthSelected = $request->input('month_selected');

            if (!empty($monthSelected)) {
                $query->whereHas('dateRange', function ($query) use ($monthSelected) {
                    $query->where('month_num', $monthSelected);
                });
            }
        }

        if ($request->has('week_selected')) {
            $weekSelected = $request->input('week_selected');

            if (!empty($weekSelected)) {
                $query->whereHas('dateRange', function ($query) use ($weekSelected) {
                    $query->where('date_Range_id', $weekSelected);
                });
            }
        }

        $classes = $query->select([
            'id',
            'pushedback_id',
            'within_sla',
            'condition',
            'requested_by',
            'original_start_date',
            'changes',
            'agreed_start_date',
            'approved_date',
            'cancelled_date',
            'wfm_date_requested',
            'notice_weeks',
            'external_target',
            'internal_target',
            'notice_days',
            'pipeline_utilized',
            'total_target',
            'remarks',
            'status',
            'category',
            'type_of_hiring',
            'update_status',
            'approved_status',
            'with_erf',
            'erf_number',
            'approved_by',
            'cancelled_by',
            'ta',
            'wave_no',
            'wf',
            'tr',
            'cl',
            'op',
            'created_by',
            'site_id',
            'program_id',
            'updated_by',
            'date_range_id',
            'created_at',
            'updated_at',
        ])
            ->get();

        $formattedClasses = $classes->map(function ($class) {
            return [
                'id' => $class->id,
                'country' => $class->site->country,
                'region' => $class->site->region,
                'site_name' => $class->site->name,
                'program_name' => $class->program->name,
                'date_range' => $class->dateRange->date_range,
                'external_target' => $class->external_target,
                'internal_target' => $class->internal_target,
                'total_target' => $class->total_target,
                'within_sla' => $class->within_sla,
                'condition' => $class->condition,
                'original_start_date' => $class->original_start_date,
                'changes' => $class->changes,
                'agreed_start_date' => $class->agreed_start_date,
                'wfm_date_requested' => $class->wfm_date_requested,
                'notice_weeks' => $class->notice_weeks,
                'notice_days' => $class->notice_days,
                'pipeline_utilized' => $class->pipeline_utilized,
                'remarks' => $class->remarks,
                'status' => $class->status,
                'category' => $class->category,
                'type_of_hiring' => $class->type_of_hiring,
                'with_erf' => $class->with_erf,
                'erf_number' => $class->erf_number,
                'wave_no' => $class->wave_no,
                'ta' => $class->ta,
                'wf' => $class->wf,
                'tr' => $class->tr,
                'cl' => $class->cl,
                'op' => $class->op,
                'approved_by' => $class->approved_by,
                'cancelled_by' => $class->cancelledByUser ? $class->cancelledByUser->name : null,
                'requested_by' => $class->requested_by,
                'created_by' => $class->createdByUser->name,
                'updated_by' => $class->updatedByUser ? $class->updatedByUser->name : null,
                'approved_date' => $class->approved_date,
                'cancelled_date' => $class->cancelled_date,
                'created_at' => $class->created_at->format('m-d-Y H:i'),
                'updated_at' => $class->updated_at->format('m-d-Y H:i'),
            ];
        })->toArray();

        return Excel::download(new ClassHistoryExport($formattedClasses), 'Class_history_data.xlsx');
    }

    public function dashboardClassesExportGuatemala3(Request $request)
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
            ->when(true, function ($query) {
                $query->whereHas('site', function ($subquery) {
                    $subquery->where('is_active', 1);
                });

                $query->whereHas('site', function ($subquery) {
                    $subquery->where('country', 'Guatemala');
                });
            })

            ->where('is_active', 1) // You can keep or remove this line based on your requirements
            ->get();

        $year = 2024;
        $dateRanges = DateRange::where('year', $year)
            ->get();

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
                    '53' => 0,
                    '54' => 0,
                    '55' => 0,
                    '56' => 0,
                    '57' => 0,
                    '58' => 0,
                    '59' => 0,
                    '60' => 0,
                    '61' => 0,
                    '62' => 0,
                    '63' => 0,
                    '64' => 0,
                    '65' => 0,
                    '66' => 0,
                    '67' => 0,
                    '68' => 0,
                    '69' => 0,
                    '70' => 0,
                    '71' => 0,
                    '72' => 0,
                    '73' => 0,
                    '74' => 0,
                    '75' => 0,
                    '76' => 0,
                    '77' => 0,
                    '78' => 0,
                    '79' => 0,
                    '80' => 0,
                    '81' => 0,
                    '82' => 0,
                    '83' => 0,
                    '84' => 0,
                    '85' => 0,
                    '86' => 0,
                    '87' => 0,
                    '88' => 0,
                    '89' => 0,
                    '90' => 0,
                    '91' => 0,
                    '92' => 0,
                    '93' => 0,
                    '94' => 0,
                    '95' => 0,
                    '96' => 0,
                    '97' => 0,
                    '98' => 0,
                    '99' => 0,
                    '100' => 0,
                    '101' => 0,
                    '102' => 0,
                    '103' => 0,
                    '104' => 0,
                ];

                foreach ($programData as $week => $weekData) {
                    $weeklyData[$week] = isset($weekData['total_target']) ? $weekData['total_target'] : 0;
                }
                $grandTotal = $grandTotalByProgram[$siteName][$programName];
                if ($grandTotal != 0) {
                    $mappedGroupedClasses[] = [
                        'Site' => $siteName,
                        'Program' => $programName,
                        'Week1' => $weeklyData['53'] != 0 ? $weeklyData['53'] : '',

                        'Week2' => $weeklyData['54'] != 0 ? $weeklyData['54'] : '',
                        'Week3' => $weeklyData['55'] != 0 ? $weeklyData['55'] : '',
                        'Week4' => $weeklyData['56'] != 0 ? $weeklyData['56'] : '',
                        'Jan' => collect($weeklyData)->only([53, 54, 55, 56])->sum() != 0 ? collect($weeklyData)->only([53, 54, 55, 56])->sum() : '',
                        'Week5' => $weeklyData['57'] != 0 ? $weeklyData['57'] : '',
                        'Week6' => $weeklyData['58'] != 0 ? $weeklyData['58'] : '',
                        'Week7' => $weeklyData['59'] != 0 ? $weeklyData['59'] : '',
                        'Week8' => $weeklyData['60'] != 0 ? $weeklyData['60'] : '',
                        'Feb' => collect($weeklyData)->only([57, 58, 59, 60])->sum() != 0 ? collect($weeklyData)->only([57, 58, 59, 60])->sum() : '',
                        'Week9' => $weeklyData['61'] != 0 ? $weeklyData['61'] : '',
                        'Week10' => $weeklyData['62'] != 0 ? $weeklyData['62'] : '',
                        'Week11' => $weeklyData['63'] != 0 ? $weeklyData['63'] : '',
                        'Week12' => $weeklyData['64'] != 0 ? $weeklyData['64'] : '',
                        'Week13' => $weeklyData['65'] != 0 ? $weeklyData['65'] : '',
                        'Mar' => collect($weeklyData)->only([61, 62, 63, 64, 65])->sum() != 0 ? collect($weeklyData)->only([61, 62, 63, 64, 65])->sum() : '',
                        'Week14' => $weeklyData['66'] != 0 ? $weeklyData['66'] : '',
                        'Week15' => $weeklyData['67'] != 0 ? $weeklyData['67'] : '',
                        'Week16' => $weeklyData['68'] != 0 ? $weeklyData['68'] : '',
                        'Week17' => $weeklyData['69'] != 0 ? $weeklyData['69'] : '',
                        'Apr' => collect($weeklyData)->only([66, 67, 68, 69])->sum() != 0 ? collect($weeklyData)->only([66, 67, 68, 69])->sum() : '',
                        'Week18' => $weeklyData['70'] != 0 ? $weeklyData['70'] : '',
                        'Week19' => $weeklyData['71'] != 0 ? $weeklyData['71'] : '',
                        'Week20' => $weeklyData['72'] != 0 ? $weeklyData['72'] : '',
                        'Week21' => $weeklyData['73'] != 0 ? $weeklyData['73'] : '',
                        'May' => collect($weeklyData)->only([70, 71, 72, 73])->sum() != 0 ? collect($weeklyData)->only([70, 71, 72, 73])->sum() : '',
                        'Week22' => $weeklyData['74'] != 0 ? $weeklyData['74'] : '',
                        'Week23' => $weeklyData['75'] != 0 ? $weeklyData['75'] : '',
                        'Week24' => $weeklyData['76'] != 0 ? $weeklyData['76'] : '',
                        'Week25' => $weeklyData['77'] != 0 ? $weeklyData['77'] : '',
                        'Week26' => $weeklyData['78'] != 0 ? $weeklyData['78'] : '',
                        'Jun' => collect($weeklyData)->only([74, 75, 76, 77, 78])->sum() != 0 ? collect($weeklyData)->only([74, 75, 76, 77, 78])->sum() : '',
                        'Week27' => $weeklyData['79'] != 0 ? $weeklyData['79'] : '',
                        'Week28' => $weeklyData['80'] != 0 ? $weeklyData['80'] : '',
                        'Week29' => $weeklyData['81'] != 0 ? $weeklyData['81'] : '',
                        'Week30' => $weeklyData['82'] != 0 ? $weeklyData['82'] : '',
                        'Jul' => collect($weeklyData)->only([79, 80, 81, 82])->sum() != 0 ? collect($weeklyData)->only([79, 80, 81, 82])->sum() : '',
                        'Week31' => $weeklyData['83'] != 0 ? $weeklyData['83'] : '',
                        'Week32' => $weeklyData['84'] != 0 ? $weeklyData['84'] : '',
                        'Week33' => $weeklyData['85'] != 0 ? $weeklyData['85'] : '',
                        'Week34' => $weeklyData['86'] != 0 ? $weeklyData['86'] : '',
                        'Week35' => $weeklyData['87'] != 0 ? $weeklyData['87'] : '',
                        'Aug' => collect($weeklyData)->only([83, 84, 85, 86, 87])->sum() != 0 ? collect($weeklyData)->only([83, 84, 85, 86, 87])->sum() : '',
                        'Week36' => $weeklyData['88'] != 0 ? $weeklyData['88'] : '',
                        'Week37' => $weeklyData['89'] != 0 ? $weeklyData['89'] : '',
                        'Week38' => $weeklyData['90'] != 0 ? $weeklyData['90'] : '',
                        'Week39' => $weeklyData['91'] != 0 ? $weeklyData['91'] : '',
                        'Sep' => collect($weeklyData)->only([88, 89, 90, 91])->sum() != 0 ? collect($weeklyData)->only([88, 89, 90, 91])->sum() : '',
                        'Week40' => $weeklyData['92'] != 0 ? $weeklyData['92'] : '',
                        'Week41' => $weeklyData['93'] != 0 ? $weeklyData['93'] : '',
                        'Week42' => $weeklyData['94'] != 0 ? $weeklyData['94'] : '',
                        'Week43' => $weeklyData['95'] != 0 ? $weeklyData['95'] : '',
                        'Oct' => collect($weeklyData)->only([92, 93, 94, 95])->sum() != 0 ? collect($weeklyData)->only([92, 93, 94, 95])->sum() : '',
                        'Week44' => $weeklyData['96'] != 0 ? $weeklyData['96'] : '',
                        'Week45' => $weeklyData['97'] != 0 ? $weeklyData['97'] : '',
                        'Week46' => $weeklyData['98'] != 0 ? $weeklyData['98'] : '',
                        'Week47' => $weeklyData['99'] != 0 ? $weeklyData['99'] : '',
                        'Week48' => $weeklyData['100'] != 0 ? $weeklyData['100'] : '',
                        'Nov' => collect($weeklyData)->only([96, 97, 98, 99, 100])->sum() != 0 ? collect($weeklyData)->only([96, 97, 98, 99, 100])->sum() : '',
                        'Week49' => $weeklyData['101'] != 0 ? $weeklyData['101'] : '',
                        'Week50' => $weeklyData['102'] != 0 ? $weeklyData['102'] : '',
                        'Week51' => $weeklyData['103'] != 0 ? $weeklyData['103'] : '',
                        'Week52' => $weeklyData['104'] != 0 ? $weeklyData['104'] : '',
                        'Dec' => collect($weeklyData)->only([101, 102, 103, 104])->sum() != 0 ? collect($weeklyData)->only([101, 102, 103, 104])->sum() : '',

                        'GrandTotalByProgram' => $grandTotal,
                    ];
                }
            }
        }

        $grandTotalForAllPrograms = array_sum(array_map('array_sum', $grandTotalByProgram));

        $mappedGroupedClasses[] = [
            'Site' => 'Grand Total',
            'Program' => '',
            'Week1' => $grandTotalByWeek['53'] != 0 ? $grandTotalByWeek['53'] : '',
            'Week2' => $grandTotalByWeek['54'] != 0 ? $grandTotalByWeek['54'] : '',
            'Week3' => $grandTotalByWeek['55'] != 0 ? $grandTotalByWeek['55'] : '',
            'Week4' => $grandTotalByWeek['56'] != 0 ? $grandTotalByWeek['56'] : '',
            'Jan' => collect($grandTotalByWeek)->only([53, 54, 55, 56])->sum() != 0 ? collect($grandTotalByWeek)->only([53, 54, 55, 56])->sum() : '',
            'Week5' => $grandTotalByWeek['57'] != 0 ? $grandTotalByWeek['57'] : '',
            'Week6' => $grandTotalByWeek['58'] != 0 ? $grandTotalByWeek['58'] : '',
            'Week7' => $grandTotalByWeek['59'] != 0 ? $grandTotalByWeek['59'] : '',
            'Week8' => $grandTotalByWeek['60'] != 0 ? $grandTotalByWeek['60'] : '',
            'Feb' => collect($grandTotalByWeek)->only([57, 58, 59, 60])->sum() != 0 ? collect($grandTotalByWeek)->only([57, 58, 59, 60])->sum() : '',
            'Week9' => $grandTotalByWeek['61'] != 0 ? $grandTotalByWeek['61'] : '',
            'Week10' => $grandTotalByWeek['62'] != 0 ? $grandTotalByWeek['62'] : '',
            'Week11' => $grandTotalByWeek['63'] != 0 ? $grandTotalByWeek['63'] : '',
            'Week12' => $grandTotalByWeek['64'] != 0 ? $grandTotalByWeek['64'] : '',
            'Week13' => $grandTotalByWeek['65'] != 0 ? $grandTotalByWeek['65'] : '',
            'Mar' => collect($grandTotalByWeek)->only([61, 62, 63, 64, 65])->sum() != 0 ? collect($grandTotalByWeek)->only([61, 62, 63, 64, 65])->sum() : '',
            'Week14' => $grandTotalByWeek['66'] != 0 ? $grandTotalByWeek['66'] : '',
            'Week15' => $grandTotalByWeek['67'] != 0 ? $grandTotalByWeek['67'] : '',
            'Week16' => $grandTotalByWeek['68'] != 0 ? $grandTotalByWeek['68'] : '',
            'Week17' => $grandTotalByWeek['69'] != 0 ? $grandTotalByWeek['69'] : '',
            'Apr' => collect($grandTotalByWeek)->only([66, 67, 68, 69])->sum() != 0 ? collect($grandTotalByWeek)->only([66, 67, 68, 69])->sum() : '',
            'Week18' => $grandTotalByWeek['70'] != 0 ? $grandTotalByWeek['70'] : '',
            'Week19' => $grandTotalByWeek['71'] != 0 ? $grandTotalByWeek['71'] : '',
            'Week20' => $grandTotalByWeek['72'] != 0 ? $grandTotalByWeek['72'] : '',
            'Week21' => $grandTotalByWeek['73'] != 0 ? $grandTotalByWeek['73'] : '',
            'May' => collect($grandTotalByWeek)->only([70, 71, 72, 73])->sum() != 0 ? collect($grandTotalByWeek)->only([70, 71, 72, 73])->sum() : '',
            'Week22' => $grandTotalByWeek['74'] != 0 ? $grandTotalByWeek['74'] : '',
            'Week23' => $grandTotalByWeek['75'] != 0 ? $grandTotalByWeek['75'] : '',
            'Week24' => $grandTotalByWeek['76'] != 0 ? $grandTotalByWeek['76'] : '',
            'Week25' => $grandTotalByWeek['77'] != 0 ? $grandTotalByWeek['77'] : '',
            'Week26' => $grandTotalByWeek['78'] != 0 ? $grandTotalByWeek['78'] : '',
            'Jun' => collect($grandTotalByWeek)->only([74, 75, 76, 77, 78])->sum() != 0 ? collect($grandTotalByWeek)->only([74, 75, 76, 77, 78])->sum() : '',
            'Week27' => $grandTotalByWeek['79'] != 0 ? $grandTotalByWeek['79'] : '',
            'Week28' => $grandTotalByWeek['80'] != 0 ? $grandTotalByWeek['80'] : '',
            'Week29' => $grandTotalByWeek['81'] != 0 ? $grandTotalByWeek['81'] : '',
            'Week30' => $grandTotalByWeek['82'] != 0 ? $grandTotalByWeek['82'] : '',
            'Jul' => collect($grandTotalByWeek)->only([79, 80, 81, 82])->sum() != 0 ? collect($grandTotalByWeek)->only([79, 80, 81, 82])->sum() : '',
            'Week31' => $grandTotalByWeek['83'] != 0 ? $grandTotalByWeek['83'] : '',
            'Week32' => $grandTotalByWeek['84'] != 0 ? $grandTotalByWeek['84'] : '',
            'Week33' => $grandTotalByWeek['85'] != 0 ? $grandTotalByWeek['85'] : '',
            'Week34' => $grandTotalByWeek['86'] != 0 ? $grandTotalByWeek['86'] : '',
            'Week35' => $grandTotalByWeek['87'] != 0 ? $grandTotalByWeek['87'] : '',
            'Aug' => collect($grandTotalByWeek)->only([83, 84, 85, 86, 87])->sum() != 0 ? collect($grandTotalByWeek)->only([83, 84, 85, 86, 87])->sum() : '',
            'Week36' => $grandTotalByWeek['88'] != 0 ? $grandTotalByWeek['88'] : '',
            'Week37' => $grandTotalByWeek['89'] != 0 ? $grandTotalByWeek['89'] : '',
            'Week38' => $grandTotalByWeek['90'] != 0 ? $grandTotalByWeek['90'] : '',
            'Week39' => $grandTotalByWeek['91'] != 0 ? $grandTotalByWeek['91'] : '',
            'Sep' => collect($grandTotalByWeek)->only([88, 89, 90, 91])->sum() != 0 ? collect($grandTotalByWeek)->only([88, 89, 90, 91])->sum() : '',
            'Week40' => $grandTotalByWeek['92'] != 0 ? $grandTotalByWeek['92'] : '',
            'Week41' => $grandTotalByWeek['93'] != 0 ? $grandTotalByWeek['93'] : '',
            'Week42' => $grandTotalByWeek['94'] != 0 ? $grandTotalByWeek['94'] : '',
            'Week43' => $grandTotalByWeek['95'] != 0 ? $grandTotalByWeek['95'] : '',
            'Oct' => collect($grandTotalByWeek)->only([92, 93, 94, 95])->sum() != 0 ? collect($grandTotalByWeek)->only([92, 93, 94, 95])->sum() : '',
            'Week44' => $grandTotalByWeek['96'] != 0 ? $grandTotalByWeek['96'] : '',
            'Week45' => $grandTotalByWeek['97'] != 0 ? $grandTotalByWeek['97'] : '',
            'Week46' => $grandTotalByWeek['98'] != 0 ? $grandTotalByWeek['98'] : '',
            'Week47' => $grandTotalByWeek['99'] != 0 ? $grandTotalByWeek['99'] : '',
            'Week48' => $grandTotalByWeek['100'] != 0 ? $grandTotalByWeek['100'] : '',
            'Nov' => collect($grandTotalByWeek)->only([96, 97, 98, 99, 100])->sum() != 0 ? collect($grandTotalByWeek)->only([96, 97, 98, 99, 100])->sum() : '',
            'Week49' => $grandTotalByWeek['101'] != 0 ? $grandTotalByWeek['101'] : '',
            'Week50' => $grandTotalByWeek['102'] != 0 ? $grandTotalByWeek['102'] : '',
            'Week51' => $grandTotalByWeek['103'] != 0 ? $grandTotalByWeek['103'] : '',
            'Week52' => $grandTotalByWeek['104'] != 0 ? $grandTotalByWeek['104'] : '',
            'Dec' => collect($grandTotalByWeek)->only([101, 102, 103, 104])->sum() != 0 ? collect($grandTotalByWeek)->only([101, 102, 103, 104])->sum() : '',
            'GrandTotalByProgram' => $grandTotalForAllPrograms,
        ];

        $response = [
            'classes' => $mappedGroupedClasses,
        ];

        return Excel::download(new dashboardClassesExport($response), 'filtered_data.xlsx');
    }

    public function dashboardClassesExportGuatemala4(Request $request)
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
            ->when(true, function ($query) {
                $query->whereHas('site', function ($subquery) {
                    $subquery->where('is_active', 1);
                });

                $query->whereHas('site', function ($subquery) {
                    $subquery->where('country', 'Guatemala');
                });
            })

            ->where('is_active', 1) // You can keep or remove this line based on your requirements
            ->get();

        $year = 2024;
        $dateRanges = DateRange::where('year', $year)
            ->get();

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
                    '53' => 0,
                    '54' => 0,
                    '55' => 0,
                    '56' => 0,
                    '57' => 0,
                    '58' => 0,
                    '59' => 0,
                    '60' => 0,
                    '61' => 0,
                    '62' => 0,
                    '63' => 0,
                    '64' => 0,
                    '65' => 0,
                    '66' => 0,
                    '67' => 0,
                    '68' => 0,
                    '69' => 0,
                    '70' => 0,
                    '71' => 0,
                    '72' => 0,
                    '73' => 0,
                    '74' => 0,
                    '75' => 0,
                    '76' => 0,
                    '77' => 0,
                    '78' => 0,
                    '79' => 0,
                    '80' => 0,
                    '81' => 0,
                    '82' => 0,
                    '83' => 0,
                    '84' => 0,
                    '85' => 0,
                    '86' => 0,
                    '87' => 0,
                    '88' => 0,
                    '89' => 0,
                    '90' => 0,
                    '91' => 0,
                    '92' => 0,
                    '93' => 0,
                    '94' => 0,
                    '95' => 0,
                    '96' => 0,
                    '97' => 0,
                    '98' => 0,
                    '99' => 0,
                    '100' => 0,
                    '101' => 0,
                    '102' => 0,
                    '103' => 0,
                    '104' => 0,
                ];

                foreach ($programData as $week => $weekData) {
                    $weeklyData[$week] = isset($weekData['total_target']) ? $weekData['total_target'] : 0;
                }
                $grandTotal = $grandTotalByProgram[$siteName][$programName];
                if ($grandTotal != 0) {
                    $mappedGroupedClasses[] = [
                        'Site' => $siteName,
                        'Program' => $programName,
                        'Week1' => $weeklyData['53'] != 0 ? $weeklyData['53'] : '',
                        'Week2' => $weeklyData['54'] != 0 ? $weeklyData['54'] : '',
                        'Week3' => $weeklyData['55'] != 0 ? $weeklyData['55'] : '',
                        'Week4' => $weeklyData['56'] != 0 ? $weeklyData['56'] : '',
                        'Jan' => collect($weeklyData)->only([53, 54, 55, 56])->sum() != 0 ? collect($weeklyData)->only([53, 54, 55, 56])->sum() : '',
                        'Week5' => $weeklyData['57'] != 0 ? $weeklyData['57'] : '',
                        'Week6' => $weeklyData['58'] != 0 ? $weeklyData['58'] : '',
                        'Week7' => $weeklyData['59'] != 0 ? $weeklyData['59'] : '',
                        'Week8' => $weeklyData['60'] != 0 ? $weeklyData['60'] : '',
                        'Feb' => collect($weeklyData)->only([57, 58, 59, 60])->sum() != 0 ? collect($weeklyData)->only([57, 58, 59, 60])->sum() : '',
                        'Week9' => $weeklyData['61'] != 0 ? $weeklyData['61'] : '',
                        'Week10' => $weeklyData['62'] != 0 ? $weeklyData['62'] : '',
                        'Week11' => $weeklyData['63'] != 0 ? $weeklyData['63'] : '',
                        'Week12' => $weeklyData['64'] != 0 ? $weeklyData['64'] : '',
                        'Week13' => $weeklyData['65'] != 0 ? $weeklyData['65'] : '',
                        'Mar' => collect($weeklyData)->only([61, 62, 63, 64, 65])->sum() != 0 ? collect($weeklyData)->only([61, 62, 63, 64, 65])->sum() : '',
                        'Week14' => $weeklyData['66'] != 0 ? $weeklyData['66'] : '',
                        'Week15' => $weeklyData['67'] != 0 ? $weeklyData['67'] : '',
                        'Week16' => $weeklyData['68'] != 0 ? $weeklyData['68'] : '',
                        'Week17' => $weeklyData['69'] != 0 ? $weeklyData['69'] : '',
                        'Apr' => collect($weeklyData)->only([66, 67, 68, 69])->sum() != 0 ? collect($weeklyData)->only([66, 67, 68, 69])->sum() : '',
                        'Week18' => $weeklyData['70'] != 0 ? $weeklyData['70'] : '',
                        'Week19' => $weeklyData['71'] != 0 ? $weeklyData['71'] : '',
                        'Week20' => $weeklyData['72'] != 0 ? $weeklyData['72'] : '',
                        'Week21' => $weeklyData['73'] != 0 ? $weeklyData['73'] : '',
                        'May' => collect($weeklyData)->only([70, 71, 72, 73])->sum() != 0 ? collect($weeklyData)->only([70, 71, 72, 73])->sum() : '',
                        'Week22' => $weeklyData['74'] != 0 ? $weeklyData['74'] : '',
                        'Week23' => $weeklyData['75'] != 0 ? $weeklyData['75'] : '',
                        'Week24' => $weeklyData['76'] != 0 ? $weeklyData['76'] : '',
                        'Week25' => $weeklyData['77'] != 0 ? $weeklyData['77'] : '',
                        'Week26' => $weeklyData['78'] != 0 ? $weeklyData['78'] : '',
                        'Jun' => collect($weeklyData)->only([74, 75, 76, 77, 78])->sum() != 0 ? collect($weeklyData)->only([74, 75, 76, 77, 78])->sum() : '',
                        'Week27' => $weeklyData['79'] != 0 ? $weeklyData['79'] : '',
                        'Week28' => $weeklyData['80'] != 0 ? $weeklyData['80'] : '',
                        'Week29' => $weeklyData['81'] != 0 ? $weeklyData['81'] : '',
                        'Week30' => $weeklyData['82'] != 0 ? $weeklyData['82'] : '',
                        'Jul' => collect($weeklyData)->only([79, 80, 81, 82])->sum() != 0 ? collect($weeklyData)->only([79, 80, 81, 82])->sum() : '',
                        'Week31' => $weeklyData['83'] != 0 ? $weeklyData['83'] : '',
                        'Week32' => $weeklyData['84'] != 0 ? $weeklyData['84'] : '',
                        'Week33' => $weeklyData['85'] != 0 ? $weeklyData['85'] : '',
                        'Week34' => $weeklyData['86'] != 0 ? $weeklyData['86'] : '',
                        'Week35' => $weeklyData['87'] != 0 ? $weeklyData['87'] : '',
                        'Aug' => collect($weeklyData)->only([83, 84, 85, 86, 87])->sum() != 0 ? collect($weeklyData)->only([83, 84, 85, 86, 87])->sum() : '',
                        'Week36' => $weeklyData['88'] != 0 ? $weeklyData['88'] : '',
                        'Week37' => $weeklyData['89'] != 0 ? $weeklyData['89'] : '',
                        'Week38' => $weeklyData['90'] != 0 ? $weeklyData['90'] : '',
                        'Week39' => $weeklyData['91'] != 0 ? $weeklyData['91'] : '',
                        'Sep' => collect($weeklyData)->only([88, 89, 90, 91])->sum() != 0 ? collect($weeklyData)->only([88, 89, 90, 91])->sum() : '',
                        'Week40' => $weeklyData['92'] != 0 ? $weeklyData['92'] : '',
                        'Week41' => $weeklyData['93'] != 0 ? $weeklyData['93'] : '',
                        'Week42' => $weeklyData['94'] != 0 ? $weeklyData['94'] : '',
                        'Week43' => $weeklyData['95'] != 0 ? $weeklyData['95'] : '',
                        'Oct' => collect($weeklyData)->only([92, 93, 94, 95])->sum() != 0 ? collect($weeklyData)->only([92, 93, 94, 95])->sum() : '',
                        'Week44' => $weeklyData['96'] != 0 ? $weeklyData['96'] : '',
                        'Week45' => $weeklyData['97'] != 0 ? $weeklyData['97'] : '',
                        'Week46' => $weeklyData['98'] != 0 ? $weeklyData['98'] : '',
                        'Week47' => $weeklyData['99'] != 0 ? $weeklyData['99'] : '',
                        'Week48' => $weeklyData['100'] != 0 ? $weeklyData['100'] : '',
                        'Nov' => collect($weeklyData)->only([96, 97, 98, 99, 100])->sum() != 0 ? collect($weeklyData)->only([96, 97, 98, 99, 100])->sum() : '',
                        'Week49' => $weeklyData['101'] != 0 ? $weeklyData['101'] : '',
                        'Week50' => $weeklyData['102'] != 0 ? $weeklyData['102'] : '',
                        'Week51' => $weeklyData['103'] != 0 ? $weeklyData['103'] : '',
                        'Week52' => $weeklyData['104'] != 0 ? $weeklyData['104'] : '',
                        'Dec' => collect($weeklyData)->only([101, 102, 103, 104])->sum() != 0 ? collect($weeklyData)->only([101, 102, 103, 104])->sum() : '',

                        'GrandTotalByProgram' => $grandTotal,
                    ];
                }
            }
        }

        $grandTotalForAllPrograms = array_sum(array_map('array_sum', $grandTotalByProgram));

        $mappedGroupedClasses[] = [
            'Site' => 'Grand Total',
            'Program' => '',
            'Week1' => $grandTotalByWeek['53'] != 0 ? $grandTotalByWeek['53'] : '',
            'Week2' => $grandTotalByWeek['54'] != 0 ? $grandTotalByWeek['54'] : '',
            'Week3' => $grandTotalByWeek['55'] != 0 ? $grandTotalByWeek['55'] : '',
            'Week4' => $grandTotalByWeek['56'] != 0 ? $grandTotalByWeek['56'] : '',
            'Jan' => collect($grandTotalByWeek)->only([53, 54, 55, 56])->sum() != 0 ? collect($grandTotalByWeek)->only([53, 54, 55, 56])->sum() : '',
            'Week5' => $grandTotalByWeek['57'] != 0 ? $grandTotalByWeek['57'] : '',
            'Week6' => $grandTotalByWeek['58'] != 0 ? $grandTotalByWeek['58'] : '',
            'Week7' => $grandTotalByWeek['59'] != 0 ? $grandTotalByWeek['59'] : '',
            'Week8' => $grandTotalByWeek['60'] != 0 ? $grandTotalByWeek['60'] : '',
            'Feb' => collect($grandTotalByWeek)->only([57, 58, 59, 60])->sum() != 0 ? collect($grandTotalByWeek)->only([57, 58, 59, 60])->sum() : '',
            'Week9' => $grandTotalByWeek['61'] != 0 ? $grandTotalByWeek['61'] : '',
            'Week10' => $grandTotalByWeek['62'] != 0 ? $grandTotalByWeek['62'] : '',
            'Week11' => $grandTotalByWeek['63'] != 0 ? $grandTotalByWeek['63'] : '',
            'Week12' => $grandTotalByWeek['64'] != 0 ? $grandTotalByWeek['64'] : '',
            'Week13' => $grandTotalByWeek['65'] != 0 ? $grandTotalByWeek['65'] : '',
            'Mar' => collect($grandTotalByWeek)->only([61, 62, 63, 64, 65])->sum() != 0 ? collect($grandTotalByWeek)->only([61, 62, 63, 64, 65])->sum() : '',
            'Week14' => $grandTotalByWeek['66'] != 0 ? $grandTotalByWeek['66'] : '',
            'Week15' => $grandTotalByWeek['67'] != 0 ? $grandTotalByWeek['67'] : '',
            'Week16' => $grandTotalByWeek['68'] != 0 ? $grandTotalByWeek['68'] : '',
            'Week17' => $grandTotalByWeek['69'] != 0 ? $grandTotalByWeek['69'] : '',
            'Apr' => collect($grandTotalByWeek)->only([66, 67, 68, 69])->sum() != 0 ? collect($grandTotalByWeek)->only([66, 67, 68, 69])->sum() : '',
            'Week18' => $grandTotalByWeek['70'] != 0 ? $grandTotalByWeek['70'] : '',
            'Week19' => $grandTotalByWeek['71'] != 0 ? $grandTotalByWeek['71'] : '',
            'Week20' => $grandTotalByWeek['72'] != 0 ? $grandTotalByWeek['72'] : '',
            'Week21' => $grandTotalByWeek['73'] != 0 ? $grandTotalByWeek['73'] : '',
            'May' => collect($grandTotalByWeek)->only([70, 71, 72, 73])->sum() != 0 ? collect($grandTotalByWeek)->only([70, 71, 72, 73])->sum() : '',
            'Week22' => $grandTotalByWeek['74'] != 0 ? $grandTotalByWeek['74'] : '',
            'Week23' => $grandTotalByWeek['75'] != 0 ? $grandTotalByWeek['75'] : '',
            'Week24' => $grandTotalByWeek['76'] != 0 ? $grandTotalByWeek['76'] : '',
            'Week25' => $grandTotalByWeek['77'] != 0 ? $grandTotalByWeek['77'] : '',
            'Week26' => $grandTotalByWeek['78'] != 0 ? $grandTotalByWeek['78'] : '',
            'Jun' => collect($grandTotalByWeek)->only([74, 75, 76, 77, 78])->sum() != 0 ? collect($grandTotalByWeek)->only([74, 75, 76, 77, 78])->sum() : '',
            'Week27' => $grandTotalByWeek['79'] != 0 ? $grandTotalByWeek['79'] : '',
            'Week28' => $grandTotalByWeek['80'] != 0 ? $grandTotalByWeek['80'] : '',
            'Week29' => $grandTotalByWeek['81'] != 0 ? $grandTotalByWeek['81'] : '',
            'Week30' => $grandTotalByWeek['82'] != 0 ? $grandTotalByWeek['82'] : '',
            'Jul' => collect($grandTotalByWeek)->only([79, 80, 81, 82])->sum() != 0 ? collect($grandTotalByWeek)->only([79, 80, 81, 82])->sum() : '',
            'Week31' => $grandTotalByWeek['83'] != 0 ? $grandTotalByWeek['83'] : '',
            'Week32' => $grandTotalByWeek['84'] != 0 ? $grandTotalByWeek['84'] : '',
            'Week33' => $grandTotalByWeek['85'] != 0 ? $grandTotalByWeek['85'] : '',
            'Week34' => $grandTotalByWeek['86'] != 0 ? $grandTotalByWeek['86'] : '',
            'Week35' => $grandTotalByWeek['87'] != 0 ? $grandTotalByWeek['87'] : '',
            'Aug' => collect($grandTotalByWeek)->only([83, 84, 85, 86, 87])->sum() != 0 ? collect($grandTotalByWeek)->only([83, 84, 85, 86, 87])->sum() : '',
            'Week36' => $grandTotalByWeek['88'] != 0 ? $grandTotalByWeek['88'] : '',
            'Week37' => $grandTotalByWeek['89'] != 0 ? $grandTotalByWeek['89'] : '',
            'Week38' => $grandTotalByWeek['90'] != 0 ? $grandTotalByWeek['90'] : '',
            'Week39' => $grandTotalByWeek['91'] != 0 ? $grandTotalByWeek['91'] : '',
            'Sep' => collect($grandTotalByWeek)->only([88, 89, 90, 91])->sum() != 0 ? collect($grandTotalByWeek)->only([88, 89, 90, 91])->sum() : '',
            'Week40' => $grandTotalByWeek['92'] != 0 ? $grandTotalByWeek['92'] : '',
            'Week41' => $grandTotalByWeek['93'] != 0 ? $grandTotalByWeek['93'] : '',
            'Week42' => $grandTotalByWeek['94'] != 0 ? $grandTotalByWeek['94'] : '',
            'Week43' => $grandTotalByWeek['95'] != 0 ? $grandTotalByWeek['95'] : '',
            'Oct' => collect($grandTotalByWeek)->only([92, 93, 94, 95])->sum() != 0 ? collect($grandTotalByWeek)->only([92, 93, 94, 95])->sum() : '',
            'Week44' => $grandTotalByWeek['96'] != 0 ? $grandTotalByWeek['96'] : '',
            'Week45' => $grandTotalByWeek['97'] != 0 ? $grandTotalByWeek['97'] : '',
            'Week46' => $grandTotalByWeek['98'] != 0 ? $grandTotalByWeek['98'] : '',
            'Week47' => $grandTotalByWeek['99'] != 0 ? $grandTotalByWeek['99'] : '',
            'Week48' => $grandTotalByWeek['100'] != 0 ? $grandTotalByWeek['100'] : '',
            'Nov' => collect($grandTotalByWeek)->only([96, 97, 98, 99, 100])->sum() != 0 ? collect($grandTotalByWeek)->only([96, 97, 98, 99, 100])->sum() : '',
            'Week49' => $grandTotalByWeek['101'] != 0 ? $grandTotalByWeek['101'] : '',
            'Week50' => $grandTotalByWeek['102'] != 0 ? $grandTotalByWeek['102'] : '',
            'Week51' => $grandTotalByWeek['103'] != 0 ? $grandTotalByWeek['103'] : '',
            'Week52' => $grandTotalByWeek['104'] != 0 ? $grandTotalByWeek['104'] : '',
            'Dec' => collect($grandTotalByWeek)->only([101, 102, 103, 104])->sum() != 0 ? collect($grandTotalByWeek)->only([101, 102, 103, 104])->sum() : '',
            'GrandTotalByProgram' => $grandTotalForAllPrograms,
        ];

        $response = [
            'classes' => $mappedGroupedClasses,
        ];

        return Excel::download(new dashboardClassesExport($response), 'filtered_data.xlsx');
    }
    public function dashboardSiteClasses(Request $request)
    {
        $siteId = $request->input('site_id');
        $programFilter = $request->input('program_id');

        $programs = Site::where('is_active', 1)->where('country', 'Philippines')->get();

        $year = 2024;
        $dateRanges = DateRange::select('month_num')->where('year', $year)->groupBy('month_num')->get();

        $groupedClasses = [];
        $grandTotalByWeek = [];
        $grandTotalByProgram = [];

        foreach ($programs as $program) {
            $siteName = $program->name;
            if (!isset($grandTotalByProgram[$siteName])) {
                $grandTotalByProgram[$siteName] = 0;
            }
            foreach ($dateRanges as $dateRange) {
                $programId = $program->id;
                $month = $dateRange->month_num;
                $classes = Classes::with('site', 'program', 'dateRange', 'createdByUser', 'updatedByUser')
                    ->whereHas('dateRange', function ($subquery) use ($month, $year) {
                        $subquery->where('month_num', $month)->where('year', $year);
                    })
                    ->whereHas('program', function ($subquery) {
                        $subquery->where('is_active', 1);
                    })

                    ->when(true, function ($query) {
                        $query->whereHas('site', function ($subquery) {
                            $subquery->where('is_active', 1);
                        });
                    })
                    ->when(!empty($siteId), function ($query) use ($siteId) {
                        $query->whereIn('site_id', $siteId);
                    })
                    ->when(!empty($programFilter), function ($query) use ($programFilter) {
                        $query->whereIn('program_id', $programFilter);
                    })
                    ->where('site_id', $programId)
                    ->where('status', 'Active')
                    ->get();

                $totalTarget = $classes->sum('total_target');

                if (!isset($grandTotalByWeek[$month])) {
                    $grandTotalByWeek[$month] = 0;
                }

                $grandTotalByWeek[$month] += $totalTarget;
                $grandTotalByProgram[$siteName] += $totalTarget;

                if (!isset($groupedClasses[$siteName][$month])) {
                    $groupedClasses[$siteName][$month] = ['total_target' => 0];
                }
                $groupedClasses[$siteName][$month]['total_target'] += $totalTarget;
            }
        }

        $mappedGroupedClasses = [];
        foreach ($groupedClasses as $siteName => $siteData) {
            $weeklyData = [
                '1' => 0, '2' => 0, '3' => 0, '4' => 0, '5' => 0, '6' => 0, '7' => 0, '8' => 0,
                '9' => 0, '10' => 0, '11' => 0, '12' => 0,
            ];
            foreach ($siteData as $month => $weekData) {
                $weeklyData[$month] = isset($weekData['total_target']) ? $weekData['total_target'] : 0;
            }
            $grandTotal = $grandTotalByProgram[$siteName];
            if ($grandTotal != 0) {
                $mappedGroupedClasses[] = [
                    'Site' => $siteName,
                    'January' => $weeklyData['1'] ?: '',
                    'February' => $weeklyData['2'] ?: '',
                    'March' => $weeklyData['3'] ?: '',
                    'April' => $weeklyData['4'] ?: '',
                    'May' => $weeklyData['5'] ?: '',
                    'June' => $weeklyData['6'] ?: '',
                    'July' => $weeklyData['7'] ?: '',
                    'August' => $weeklyData['8'] ?: '',
                    'September' => $weeklyData['9'] ?: '',
                    'October' => $weeklyData['10'] ?: '',
                    'November' => $weeklyData['11'] ?: '',
                    'December' => $weeklyData['12'] ?: '',
                    'GrandTotalByProgram' => $grandTotal,
                ];
            }
        }

        $grandTotalForAllPrograms = array_sum($grandTotalByProgram);

        $mappedGroupedClasses[] = [
            'Site' => 'Grand Total',
            'January' => $grandTotalByWeek['1'] ?: '',
            'February' => $grandTotalByWeek['2'] ?: '',
            'March' => $grandTotalByWeek['3'] ?: '',
            'April' => $grandTotalByWeek['4'] ?: '',
            'May' => $grandTotalByWeek['5'] ?: '',
            'June' => $grandTotalByWeek['6'] ?: '',
            'July' => $grandTotalByWeek['7'] ?: '',
            'August' => $grandTotalByWeek['8'] ?: '',
            'September' => $grandTotalByWeek['9'] ?: '',
            'October' => $grandTotalByWeek['10'] ?: '',
            'November' => $grandTotalByWeek['11'] ?: '',
            'December' => $grandTotalByWeek['12'] ?: '',
            'GrandTotalByProgram' => $grandTotalForAllPrograms,
        ];
        $response = [
            'data' => $mappedGroupedClasses,
        ];

        return response()->json($response);
    }
    public function dashboardExternalClasses(Request $request)
    {
        $siteId = $request->input('site_id');
        $programFilter = $request->input('program_id');

        $programs = Site::where('is_active', 1)->where('country', 'Philippines')->get();

        $year = 2024;
        $dateRanges = DateRange::select('month_num')->where('year', $year)->groupBy('month_num')->get();

        $groupedClasses = [];
        $grandTotalByWeek = [];
        $grandTotalByProgram = [];

        foreach ($programs as $program) {
            $siteName = $program->name;
            if (!isset($grandTotalByProgram[$siteName])) {
                $grandTotalByProgram[$siteName] = 0;
            }
            foreach ($dateRanges as $dateRange) {
                $programId = $program->id;
                $month = $dateRange->month_num;
                $classes = Classes::with('site', 'program', 'dateRange', 'createdByUser', 'updatedByUser')
                    ->whereHas('dateRange', function ($subquery) use ($month, $year) {
                        $subquery->where('month_num', $month)->where('year', $year);
                    })
                    ->whereHas('program', function ($subquery) {
                        $subquery->where('is_active', 1);
                    })

                    ->when(true, function ($query) {
                        $query->whereHas('site', function ($subquery) {
                            $subquery->where('is_active', 1);
                        });
                    })
                    ->when(!empty($siteId), function ($query) use ($siteId) {
                        $query->whereIn('site_id', $siteId);
                    })
                    ->when(!empty($programFilter), function ($query) use ($programFilter) {
                        $query->whereIn('program_id', $programFilter);
                    })
                    ->where('site_id', $programId)
                    ->where('status', 'Active')
                    ->get();

                $totalTarget = $classes->sum('external_target');

                if (!isset($grandTotalByWeek[$month])) {
                    $grandTotalByWeek[$month] = 0;
                }

                $grandTotalByWeek[$month] += $totalTarget;
                $grandTotalByProgram[$siteName] += $totalTarget;

                if (!isset($groupedClasses[$siteName][$month])) {
                    $groupedClasses[$siteName][$month] = ['external_target' => 0];
                }
                $groupedClasses[$siteName][$month]['external_target'] += $totalTarget;
            }
        }

        $mappedGroupedClasses = [];
        foreach ($groupedClasses as $siteName => $siteData) {
            $weeklyData = [
                '1' => 0, '2' => 0, '3' => 0, '4' => 0, '5' => 0, '6' => 0, '7' => 0, '8' => 0,
                '9' => 0, '10' => 0, '11' => 0, '12' => 0,
            ];
            foreach ($siteData as $month => $weekData) {
                $weeklyData[$month] = isset($weekData['external_target']) ? $weekData['external_target'] : 0;
            }
            $grandTotal = $grandTotalByProgram[$siteName];
            if ($grandTotal != 0) {
                $mappedGroupedClasses[] = [
                    'Site' => $siteName,
                    'January' => $weeklyData['1'] ?: '',
                    'February' => $weeklyData['2'] ?: '',
                    'March' => $weeklyData['3'] ?: '',
                    'April' => $weeklyData['4'] ?: '',
                    'May' => $weeklyData['5'] ?: '',
                    'June' => $weeklyData['6'] ?: '',
                    'July' => $weeklyData['7'] ?: '',
                    'August' => $weeklyData['8'] ?: '',
                    'September' => $weeklyData['9'] ?: '',
                    'October' => $weeklyData['10'] ?: '',
                    'November' => $weeklyData['11'] ?: '',
                    'December' => $weeklyData['12'] ?: '',
                    'GrandTotalByProgram' => $grandTotal,
                ];
            }
        }

        $grandTotalForAllPrograms = array_sum($grandTotalByProgram);

        $mappedGroupedClasses[] = [
            'Site' => 'Grand Total',
            'January' => $grandTotalByWeek['1'] ?: '',
            'February' => $grandTotalByWeek['2'] ?: '',
            'March' => $grandTotalByWeek['3'] ?: '',
            'April' => $grandTotalByWeek['4'] ?: '',
            'May' => $grandTotalByWeek['5'] ?: '',
            'June' => $grandTotalByWeek['6'] ?: '',
            'July' => $grandTotalByWeek['7'] ?: '',
            'August' => $grandTotalByWeek['8'] ?: '',
            'September' => $grandTotalByWeek['9'] ?: '',
            'October' => $grandTotalByWeek['10'] ?: '',
            'November' => $grandTotalByWeek['11'] ?: '',
            'December' => $grandTotalByWeek['12'] ?: '',
            'GrandTotalByProgram' => $grandTotalForAllPrograms,
        ];
        $response = [
            'data' => $mappedGroupedClasses,
        ];

        return response()->json($response);
    }
    public function dashboardInternalClasses(Request $request)
    {
        $siteId = $request->input('site_id');
        $programFilter = $request->input('program_id');

        $programs = Site::where('is_active', 1)->where('country', 'Philippines')->get();

        $year = 2024;
        $dateRanges = DateRange::select('month_num')->where('year', $year)->groupBy('month_num')->get();

        $groupedClasses = [];
        $grandTotalByWeek = [];
        $grandTotalByProgram = [];

        foreach ($programs as $program) {
            $siteName = $program->name;
            if (!isset($grandTotalByProgram[$siteName])) {
                $grandTotalByProgram[$siteName] = 0;
            }
            foreach ($dateRanges as $dateRange) {
                $programId = $program->id;
                $month = $dateRange->month_num;
                $classes = Classes::with('site', 'program', 'dateRange', 'createdByUser', 'updatedByUser')
                    ->whereHas('dateRange', function ($subquery) use ($month, $year) {
                        $subquery->where('month_num', $month)->where('year', $year);
                    })
                    ->whereHas('program', function ($subquery) {
                        $subquery->where('is_active', 1);
                    })

                    ->when(true, function ($query) {
                        $query->whereHas('site', function ($subquery) {
                            $subquery->where('is_active', 1);
                        });
                    })
                    ->when(!empty($siteId), function ($query) use ($siteId) {
                        $query->whereIn('site_id', $siteId);
                    })
                    ->when(!empty($programFilter), function ($query) use ($programFilter) {
                        $query->whereIn('program_id', $programFilter);
                    })
                    ->where('site_id', $programId)
                    ->where('status', 'Active')
                    ->get();

                $totalTarget = $classes->sum('internal_target');

                if (!isset($grandTotalByWeek[$month])) {
                    $grandTotalByWeek[$month] = 0;
                }

                $grandTotalByWeek[$month] += $totalTarget;
                $grandTotalByProgram[$siteName] += $totalTarget;

                if (!isset($groupedClasses[$siteName][$month])) {
                    $groupedClasses[$siteName][$month] = ['internal_target' => 0];
                }
                $groupedClasses[$siteName][$month]['internal_target'] += $totalTarget;
            }
        }

        $mappedGroupedClasses = [];
        foreach ($groupedClasses as $siteName => $siteData) {
            $weeklyData = [
                '1' => 0, '2' => 0, '3' => 0, '4' => 0, '5' => 0, '6' => 0, '7' => 0, '8' => 0,
                '9' => 0, '10' => 0, '11' => 0, '12' => 0,
            ];
            foreach ($siteData as $month => $weekData) {
                $weeklyData[$month] = isset($weekData['internal_target']) ? $weekData['internal_target'] : 0;
            }
            $grandTotal = $grandTotalByProgram[$siteName];
            if ($grandTotal != 0) {
                $mappedGroupedClasses[] = [
                    'Site' => $siteName,
                    'January' => $weeklyData['1'] ?: '',
                    'February' => $weeklyData['2'] ?: '',
                    'March' => $weeklyData['3'] ?: '',
                    'April' => $weeklyData['4'] ?: '',
                    'May' => $weeklyData['5'] ?: '',
                    'June' => $weeklyData['6'] ?: '',
                    'July' => $weeklyData['7'] ?: '',
                    'August' => $weeklyData['8'] ?: '',
                    'September' => $weeklyData['9'] ?: '',
                    'October' => $weeklyData['10'] ?: '',
                    'November' => $weeklyData['11'] ?: '',
                    'December' => $weeklyData['12'] ?: '',
                    'GrandTotalByProgram' => $grandTotal,
                ];
            }
        }

        $grandTotalForAllPrograms = array_sum($grandTotalByProgram);

        $mappedGroupedClasses[] = [
            'Site' => 'Grand Total',
            'January' => $grandTotalByWeek['1'] ?: '',
            'February' => $grandTotalByWeek['2'] ?: '',
            'March' => $grandTotalByWeek['3'] ?: '',
            'April' => $grandTotalByWeek['4'] ?: '',
            'May' => $grandTotalByWeek['5'] ?: '',
            'June' => $grandTotalByWeek['6'] ?: '',
            'July' => $grandTotalByWeek['7'] ?: '',
            'August' => $grandTotalByWeek['8'] ?: '',
            'September' => $grandTotalByWeek['9'] ?: '',
            'October' => $grandTotalByWeek['10'] ?: '',
            'November' => $grandTotalByWeek['11'] ?: '',
            'December' => $grandTotalByWeek['12'] ?: '',
            'GrandTotalByProgram' => $grandTotalForAllPrograms,
        ];
        $response = [
            'data' => $mappedGroupedClasses,
        ];

        return response()->json($response);
    }
    public function dashboardSiteClassesJamaica(Request $request)
    {
        $siteId = $request->input('site_id');
        $programFilter = $request->input('program_id');

        $programs = Site::where('is_active', 1)->where('country', 'Jamaica')->get();

        $year = 2024;
        $dateRanges = DateRange::select('month_num')->where('year', $year)->groupBy('month_num')->get();

        $groupedClasses = [];
        $grandTotalByWeek = [];
        $grandTotalByProgram = [];

        foreach ($programs as $program) {
            $siteName = $program->name;
            if (!isset($grandTotalByProgram[$siteName])) {
                $grandTotalByProgram[$siteName] = 0;
            }
            foreach ($dateRanges as $dateRange) {
                $programId = $program->id;
                $month = $dateRange->month_num;
                $classes = Classes::with('site', 'program', 'dateRange', 'createdByUser', 'updatedByUser')
                    ->whereHas('dateRange', function ($subquery) use ($month, $year) {
                        $subquery->where('month_num', $month)->where('year', $year);
                    })
                    ->whereHas('program', function ($subquery) {
                        $subquery->where('is_active', 1);
                    })

                    ->when(true, function ($query) {
                        $query->whereHas('site', function ($subquery) {
                            $subquery->where('is_active', 1);
                        });
                    })
                    ->when(!empty($siteId), function ($query) use ($siteId) {
                        $query->whereIn('site_id', $siteId);
                    })
                    ->when(!empty($programFilter), function ($query) use ($programFilter) {
                        $query->whereIn('program_id', $programFilter);
                    })
                    ->where('site_id', $programId)
                    ->where('status', 'Active')
                    ->get();

                $totalTarget = $classes->sum('total_target');

                if (!isset($grandTotalByWeek[$month])) {
                    $grandTotalByWeek[$month] = 0;
                }

                $grandTotalByWeek[$month] += $totalTarget;
                $grandTotalByProgram[$siteName] += $totalTarget;

                if (!isset($groupedClasses[$siteName][$month])) {
                    $groupedClasses[$siteName][$month] = ['total_target' => 0];
                }
                $groupedClasses[$siteName][$month]['total_target'] += $totalTarget;
            }
        }

        $mappedGroupedClasses = [];
        foreach ($groupedClasses as $siteName => $siteData) {
            $weeklyData = [
                '1' => 0, '2' => 0, '3' => 0, '4' => 0, '5' => 0, '6' => 0, '7' => 0, '8' => 0,
                '9' => 0, '10' => 0, '11' => 0, '12' => 0,
            ];
            foreach ($siteData as $month => $weekData) {
                $weeklyData[$month] = isset($weekData['total_target']) ? $weekData['total_target'] : 0;
            }
            $grandTotal = $grandTotalByProgram[$siteName];
            if ($grandTotal != 0) {
                $mappedGroupedClasses[] = [
                    'Site' => $siteName,
                    'January' => $weeklyData['1'] ?: '',
                    'February' => $weeklyData['2'] ?: '',
                    'March' => $weeklyData['3'] ?: '',
                    'April' => $weeklyData['4'] ?: '',
                    'May' => $weeklyData['5'] ?: '',
                    'June' => $weeklyData['6'] ?: '',
                    'July' => $weeklyData['7'] ?: '',
                    'August' => $weeklyData['8'] ?: '',
                    'September' => $weeklyData['9'] ?: '',
                    'October' => $weeklyData['10'] ?: '',
                    'November' => $weeklyData['11'] ?: '',
                    'December' => $weeklyData['12'] ?: '',
                    'GrandTotalByProgram' => $grandTotal,
                ];
            }
        }

        $grandTotalForAllPrograms = array_sum($grandTotalByProgram);

        $mappedGroupedClasses[] = [
            'Site' => 'Grand Total',
            'January' => $grandTotalByWeek['1'] ?: '',
            'February' => $grandTotalByWeek['2'] ?: '',
            'March' => $grandTotalByWeek['3'] ?: '',
            'April' => $grandTotalByWeek['4'] ?: '',
            'May' => $grandTotalByWeek['5'] ?: '',
            'June' => $grandTotalByWeek['6'] ?: '',
            'July' => $grandTotalByWeek['7'] ?: '',
            'August' => $grandTotalByWeek['8'] ?: '',
            'September' => $grandTotalByWeek['9'] ?: '',
            'October' => $grandTotalByWeek['10'] ?: '',
            'November' => $grandTotalByWeek['11'] ?: '',
            'December' => $grandTotalByWeek['12'] ?: '',
            'GrandTotalByProgram' => $grandTotalForAllPrograms,
        ];
        $response = [
            'data' => $mappedGroupedClasses,
        ];

        return response()->json($response);
    }
    public function dashboardSiteClassesGuatemala(Request $request)
    {
        $siteId = $request->input('site_id');
        $programFilter = $request->input('program_id');

        $programs = Site::where('is_active', 1)->where('country', 'Guatemala')->get();

        $year = 2024;
        $dateRanges = DateRange::select('month_num')->where('year', $year)->groupBy('month_num')->get();

        $groupedClasses = [];
        $grandTotalByWeek = [];
        $grandTotalByProgram = [];

        foreach ($programs as $program) {
            $siteName = $program->name;
            if (!isset($grandTotalByProgram[$siteName])) {
                $grandTotalByProgram[$siteName] = 0;
            }
            foreach ($dateRanges as $dateRange) {
                $programId = $program->id;
                $month = $dateRange->month_num;
                $classes = Classes::with('site', 'program', 'dateRange', 'createdByUser', 'updatedByUser')
                    ->whereHas('dateRange', function ($subquery) use ($month, $year) {
                        $subquery->where('month_num', $month)->where('year', $year);
                    })
                    ->whereHas('program', function ($subquery) {
                        $subquery->where('is_active', 1);
                    })

                    ->when(true, function ($query) {
                        $query->whereHas('site', function ($subquery) {
                            $subquery->where('is_active', 1);
                        });
                    })
                    ->when(!empty($siteId), function ($query) use ($siteId) {
                        $query->whereIn('site_id', $siteId);
                    })
                    ->when(!empty($programFilter), function ($query) use ($programFilter) {
                        $query->whereIn('program_id', $programFilter);
                    })
                    ->where('site_id', $programId)
                    ->where('status', 'Active')
                    ->get();

                $totalTarget = $classes->sum('total_target');

                if (!isset($grandTotalByWeek[$month])) {
                    $grandTotalByWeek[$month] = 0;
                }

                $grandTotalByWeek[$month] += $totalTarget;
                $grandTotalByProgram[$siteName] += $totalTarget;

                if (!isset($groupedClasses[$siteName][$month])) {
                    $groupedClasses[$siteName][$month] = ['total_target' => 0];
                }
                $groupedClasses[$siteName][$month]['total_target'] += $totalTarget;
            }
        }

        $mappedGroupedClasses = [];
        foreach ($groupedClasses as $siteName => $siteData) {
            $weeklyData = [
                '1' => 0, '2' => 0, '3' => 0, '4' => 0, '5' => 0, '6' => 0, '7' => 0, '8' => 0,
                '9' => 0, '10' => 0, '11' => 0, '12' => 0,
            ];
            foreach ($siteData as $month => $weekData) {
                $weeklyData[$month] = isset($weekData['total_target']) ? $weekData['total_target'] : 0;
            }
            $grandTotal = $grandTotalByProgram[$siteName];
            if ($grandTotal != 0) {
                $mappedGroupedClasses[] = [
                    'Site' => $siteName,
                    'January' => $weeklyData['1'] ?: '',
                    'February' => $weeklyData['2'] ?: '',
                    'March' => $weeklyData['3'] ?: '',
                    'April' => $weeklyData['4'] ?: '',
                    'May' => $weeklyData['5'] ?: '',
                    'June' => $weeklyData['6'] ?: '',
                    'July' => $weeklyData['7'] ?: '',
                    'August' => $weeklyData['8'] ?: '',
                    'September' => $weeklyData['9'] ?: '',
                    'October' => $weeklyData['10'] ?: '',
                    'November' => $weeklyData['11'] ?: '',
                    'December' => $weeklyData['12'] ?: '',
                    'GrandTotalByProgram' => $grandTotal,
                ];
            }
        }

        $grandTotalForAllPrograms = array_sum($grandTotalByProgram);

        $mappedGroupedClasses[] = [
            'Site' => 'Grand Total',
            'January' => $grandTotalByWeek['1'] ?: '',
            'February' => $grandTotalByWeek['2'] ?: '',
            'March' => $grandTotalByWeek['3'] ?: '',
            'April' => $grandTotalByWeek['4'] ?: '',
            'May' => $grandTotalByWeek['5'] ?: '',
            'June' => $grandTotalByWeek['6'] ?: '',
            'July' => $grandTotalByWeek['7'] ?: '',
            'August' => $grandTotalByWeek['8'] ?: '',
            'September' => $grandTotalByWeek['9'] ?: '',
            'October' => $grandTotalByWeek['10'] ?: '',
            'November' => $grandTotalByWeek['11'] ?: '',
            'December' => $grandTotalByWeek['12'] ?: '',
            'GrandTotalByProgram' => $grandTotalForAllPrograms,
        ];
        $response = [
            'data' => $mappedGroupedClasses,
        ];

        return response()->json($response);
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
            ->when(true, function ($query) {
                $query->whereHas('site', function ($subquery) {
                    $subquery->where('is_active', 1);
                });

                $query->whereHas('site', function ($subquery) {
                    $subquery->where('country', 'Philippines');
                });
            })

            ->where('is_active', 1) // You can keep or remove this line based on your requirements
            ->get();

        $year = 2024;
        $dateRanges = DateRange::where('year', $year)
            ->get();

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
                    '53' => 0,
                    '54' => 0,
                    '55' => 0,
                    '56' => 0,
                    '57' => 0,
                    '58' => 0,
                    '59' => 0,
                    '60' => 0,
                    '61' => 0,
                    '62' => 0,
                    '63' => 0,
                    '64' => 0,
                    '65' => 0,
                    '66' => 0,
                    '67' => 0,
                    '68' => 0,
                    '69' => 0,
                    '70' => 0,
                    '71' => 0,
                    '72' => 0,
                    '73' => 0,
                    '74' => 0,
                    '75' => 0,
                    '76' => 0,
                    '77' => 0,
                    '78' => 0,
                    '79' => 0,
                    '80' => 0,
                    '81' => 0,
                    '82' => 0,
                    '83' => 0,
                    '84' => 0,
                    '85' => 0,
                    '86' => 0,
                    '87' => 0,
                    '88' => 0,
                    '89' => 0,
                    '90' => 0,
                    '91' => 0,
                    '92' => 0,
                    '93' => 0,
                    '94' => 0,
                    '95' => 0,
                    '96' => 0,
                    '97' => 0,
                    '98' => 0,
                    '99' => 0,
                    '100' => 0,
                    '101' => 0,
                    '102' => 0,
                    '103' => 0,
                    '104' => 0,
                ];

                foreach ($programData as $week => $weekData) {
                    $weeklyData[$week] = isset($weekData['total_target']) ? $weekData['total_target'] : 0;
                }
                $grandTotal = $grandTotalByProgram[$siteName][$programName];
                if ($grandTotal != 0) {
                    $mappedGroupedClasses[] = [
                        'Site' => $siteName,
                        'Program' => $programName,
                        'Week1' => $weeklyData['53'] != 0 ? $weeklyData['53'] : '',

                        'Week2' => $weeklyData['54'] != 0 ? $weeklyData['54'] : '',
                        'Week3' => $weeklyData['55'] != 0 ? $weeklyData['55'] : '',
                        'Week4' => $weeklyData['56'] != 0 ? $weeklyData['56'] : '',
                        'Jan' => collect($weeklyData)->only([53, 54, 55, 56])->sum() != 0 ? collect($weeklyData)->only([53, 54, 55, 56])->sum() : '',
                        'Week5' => $weeklyData['57'] != 0 ? $weeklyData['57'] : '',
                        'Week6' => $weeklyData['58'] != 0 ? $weeklyData['58'] : '',
                        'Week7' => $weeklyData['59'] != 0 ? $weeklyData['59'] : '',
                        'Week8' => $weeklyData['60'] != 0 ? $weeklyData['60'] : '',
                        'Feb' => collect($weeklyData)->only([57, 58, 59, 60])->sum() != 0 ? collect($weeklyData)->only([57, 58, 59, 60])->sum() : '',
                        'Week9' => $weeklyData['61'] != 0 ? $weeklyData['61'] : '',
                        'Week10' => $weeklyData['62'] != 0 ? $weeklyData['62'] : '',
                        'Week11' => $weeklyData['63'] != 0 ? $weeklyData['63'] : '',
                        'Week12' => $weeklyData['64'] != 0 ? $weeklyData['64'] : '',
                        'Week13' => $weeklyData['65'] != 0 ? $weeklyData['65'] : '',
                        'Mar' => collect($weeklyData)->only([61, 62, 63, 64, 65])->sum() != 0 ? collect($weeklyData)->only([61, 62, 63, 64, 65])->sum() : '',
                        'Week14' => $weeklyData['66'] != 0 ? $weeklyData['66'] : '',
                        'Week15' => $weeklyData['67'] != 0 ? $weeklyData['67'] : '',
                        'Week16' => $weeklyData['68'] != 0 ? $weeklyData['68'] : '',
                        'Week17' => $weeklyData['69'] != 0 ? $weeklyData['69'] : '',
                        'Apr' => collect($weeklyData)->only([66, 67, 68, 69])->sum() != 0 ? collect($weeklyData)->only([66, 67, 68, 69])->sum() : '',
                        'Week18' => $weeklyData['70'] != 0 ? $weeklyData['70'] : '',
                        'Week19' => $weeklyData['71'] != 0 ? $weeklyData['71'] : '',
                        'Week20' => $weeklyData['72'] != 0 ? $weeklyData['72'] : '',
                        'Week21' => $weeklyData['73'] != 0 ? $weeklyData['73'] : '',
                        'May' => collect($weeklyData)->only([70, 71, 72, 73])->sum() != 0 ? collect($weeklyData)->only([70, 71, 72, 73])->sum() : '',
                        'Week22' => $weeklyData['74'] != 0 ? $weeklyData['74'] : '',
                        'Week23' => $weeklyData['75'] != 0 ? $weeklyData['75'] : '',
                        'Week24' => $weeklyData['76'] != 0 ? $weeklyData['76'] : '',
                        'Week25' => $weeklyData['77'] != 0 ? $weeklyData['77'] : '',
                        'Week26' => $weeklyData['78'] != 0 ? $weeklyData['78'] : '',
                        'Jun' => collect($weeklyData)->only([74, 75, 76, 77, 78])->sum() != 0 ? collect($weeklyData)->only([74, 75, 76, 77, 78])->sum() : '',
                        'Week27' => $weeklyData['79'] != 0 ? $weeklyData['79'] : '',
                        'Week28' => $weeklyData['80'] != 0 ? $weeklyData['80'] : '',
                        'Week29' => $weeklyData['81'] != 0 ? $weeklyData['81'] : '',
                        'Week30' => $weeklyData['82'] != 0 ? $weeklyData['82'] : '',
                        'Jul' => collect($weeklyData)->only([79, 80, 81, 82])->sum() != 0 ? collect($weeklyData)->only([79, 80, 81, 82])->sum() : '',
                        'Week31' => $weeklyData['83'] != 0 ? $weeklyData['83'] : '',
                        'Week32' => $weeklyData['84'] != 0 ? $weeklyData['84'] : '',
                        'Week33' => $weeklyData['85'] != 0 ? $weeklyData['85'] : '',
                        'Week34' => $weeklyData['86'] != 0 ? $weeklyData['86'] : '',
                        'Week35' => $weeklyData['87'] != 0 ? $weeklyData['87'] : '',
                        'Aug' => collect($weeklyData)->only([83, 84, 85, 86, 87])->sum() != 0 ? collect($weeklyData)->only([83, 84, 85, 86, 87])->sum() : '',
                        'Week36' => $weeklyData['88'] != 0 ? $weeklyData['88'] : '',
                        'Week37' => $weeklyData['89'] != 0 ? $weeklyData['89'] : '',
                        'Week38' => $weeklyData['90'] != 0 ? $weeklyData['90'] : '',
                        'Week39' => $weeklyData['91'] != 0 ? $weeklyData['91'] : '',
                        'Sep' => collect($weeklyData)->only([88, 89, 90, 91])->sum() != 0 ? collect($weeklyData)->only([88, 89, 90, 91])->sum() : '',
                        'Week40' => $weeklyData['92'] != 0 ? $weeklyData['92'] : '',
                        'Week41' => $weeklyData['93'] != 0 ? $weeklyData['93'] : '',
                        'Week42' => $weeklyData['94'] != 0 ? $weeklyData['94'] : '',
                        'Week43' => $weeklyData['95'] != 0 ? $weeklyData['95'] : '',
                        'Oct' => collect($weeklyData)->only([92, 93, 94, 95])->sum() != 0 ? collect($weeklyData)->only([92, 93, 94, 95])->sum() : '',
                        'Week44' => $weeklyData['96'] != 0 ? $weeklyData['96'] : '',
                        'Week45' => $weeklyData['97'] != 0 ? $weeklyData['97'] : '',
                        'Week46' => $weeklyData['98'] != 0 ? $weeklyData['98'] : '',
                        'Week47' => $weeklyData['99'] != 0 ? $weeklyData['99'] : '',
                        'Week48' => $weeklyData['100'] != 0 ? $weeklyData['100'] : '',
                        'Nov' => collect($weeklyData)->only([96, 97, 98, 99, 100])->sum() != 0 ? collect($weeklyData)->only([96, 97, 98, 99, 100])->sum() : '',
                        'Week49' => $weeklyData['101'] != 0 ? $weeklyData['101'] : '',
                        'Week50' => $weeklyData['102'] != 0 ? $weeklyData['102'] : '',
                        'Week51' => $weeklyData['103'] != 0 ? $weeklyData['103'] : '',
                        'Week52' => $weeklyData['104'] != 0 ? $weeklyData['104'] : '',
                        'Dec' => collect($weeklyData)->only([101, 102, 103, 104])->sum() != 0 ? collect($weeklyData)->only([101, 102, 103, 104])->sum() : '',

                        'GrandTotalByProgram' => $grandTotal,
                    ];
                }
            }
        }

        $grandTotalForAllPrograms = array_sum(array_map('array_sum', $grandTotalByProgram));

        $mappedGroupedClasses[] = [
            'Site' => 'Grand Total',
            'Program' => '',
            'Week1' => $grandTotalByWeek['53'] != 0 ? $grandTotalByWeek['53'] : '',
            'Week2' => $grandTotalByWeek['54'] != 0 ? $grandTotalByWeek['54'] : '',
            'Week3' => $grandTotalByWeek['55'] != 0 ? $grandTotalByWeek['55'] : '',
            'Week4' => $grandTotalByWeek['56'] != 0 ? $grandTotalByWeek['56'] : '',
            'Jan' => collect($grandTotalByWeek)->only([53, 54, 55, 56])->sum() != 0 ? collect($grandTotalByWeek)->only([53, 54, 55, 56])->sum() : '',
            'Week5' => $grandTotalByWeek['57'] != 0 ? $grandTotalByWeek['57'] : '',
            'Week6' => $grandTotalByWeek['58'] != 0 ? $grandTotalByWeek['58'] : '',
            'Week7' => $grandTotalByWeek['59'] != 0 ? $grandTotalByWeek['59'] : '',
            'Week8' => $grandTotalByWeek['60'] != 0 ? $grandTotalByWeek['60'] : '',
            'Feb' => collect($grandTotalByWeek)->only([57, 58, 59, 60])->sum() != 0 ? collect($grandTotalByWeek)->only([57, 58, 59, 60])->sum() : '',
            'Week9' => $grandTotalByWeek['61'] != 0 ? $grandTotalByWeek['61'] : '',
            'Week10' => $grandTotalByWeek['62'] != 0 ? $grandTotalByWeek['62'] : '',
            'Week11' => $grandTotalByWeek['63'] != 0 ? $grandTotalByWeek['63'] : '',
            'Week12' => $grandTotalByWeek['64'] != 0 ? $grandTotalByWeek['64'] : '',
            'Week13' => $grandTotalByWeek['65'] != 0 ? $grandTotalByWeek['65'] : '',
            'Mar' => collect($grandTotalByWeek)->only([61, 62, 63, 64, 65])->sum() != 0 ? collect($grandTotalByWeek)->only([61, 62, 63, 64, 65])->sum() : '',
            'Week14' => $grandTotalByWeek['66'] != 0 ? $grandTotalByWeek['66'] : '',
            'Week15' => $grandTotalByWeek['67'] != 0 ? $grandTotalByWeek['67'] : '',
            'Week16' => $grandTotalByWeek['68'] != 0 ? $grandTotalByWeek['68'] : '',
            'Week17' => $grandTotalByWeek['69'] != 0 ? $grandTotalByWeek['69'] : '',
            'Apr' => collect($grandTotalByWeek)->only([66, 67, 68, 69])->sum() != 0 ? collect($grandTotalByWeek)->only([66, 67, 68, 69])->sum() : '',
            'Week18' => $grandTotalByWeek['70'] != 0 ? $grandTotalByWeek['70'] : '',
            'Week19' => $grandTotalByWeek['71'] != 0 ? $grandTotalByWeek['71'] : '',
            'Week20' => $grandTotalByWeek['72'] != 0 ? $grandTotalByWeek['72'] : '',
            'Week21' => $grandTotalByWeek['73'] != 0 ? $grandTotalByWeek['73'] : '',
            'May' => collect($grandTotalByWeek)->only([70, 71, 72, 73])->sum() != 0 ? collect($grandTotalByWeek)->only([70, 71, 72, 73])->sum() : '',
            'Week22' => $grandTotalByWeek['74'] != 0 ? $grandTotalByWeek['74'] : '',
            'Week23' => $grandTotalByWeek['75'] != 0 ? $grandTotalByWeek['75'] : '',
            'Week24' => $grandTotalByWeek['76'] != 0 ? $grandTotalByWeek['76'] : '',
            'Week25' => $grandTotalByWeek['77'] != 0 ? $grandTotalByWeek['77'] : '',
            'Week26' => $grandTotalByWeek['78'] != 0 ? $grandTotalByWeek['78'] : '',
            'Jun' => collect($grandTotalByWeek)->only([74, 75, 76, 77, 78])->sum() != 0 ? collect($grandTotalByWeek)->only([74, 75, 76, 77, 78])->sum() : '',
            'Week27' => $grandTotalByWeek['79'] != 0 ? $grandTotalByWeek['79'] : '',
            'Week28' => $grandTotalByWeek['80'] != 0 ? $grandTotalByWeek['80'] : '',
            'Week29' => $grandTotalByWeek['81'] != 0 ? $grandTotalByWeek['81'] : '',
            'Week30' => $grandTotalByWeek['82'] != 0 ? $grandTotalByWeek['82'] : '',
            'Jul' => collect($grandTotalByWeek)->only([79, 80, 81, 82])->sum() != 0 ? collect($grandTotalByWeek)->only([79, 80, 81, 82])->sum() : '',
            'Week31' => $grandTotalByWeek['83'] != 0 ? $grandTotalByWeek['83'] : '',
            'Week32' => $grandTotalByWeek['84'] != 0 ? $grandTotalByWeek['84'] : '',
            'Week33' => $grandTotalByWeek['85'] != 0 ? $grandTotalByWeek['85'] : '',
            'Week34' => $grandTotalByWeek['86'] != 0 ? $grandTotalByWeek['86'] : '',
            'Week35' => $grandTotalByWeek['87'] != 0 ? $grandTotalByWeek['87'] : '',
            'Aug' => collect($grandTotalByWeek)->only([83, 84, 85, 86, 87])->sum() != 0 ? collect($grandTotalByWeek)->only([83, 84, 85, 86, 87])->sum() : '',
            'Week36' => $grandTotalByWeek['88'] != 0 ? $grandTotalByWeek['88'] : '',
            'Week37' => $grandTotalByWeek['89'] != 0 ? $grandTotalByWeek['89'] : '',
            'Week38' => $grandTotalByWeek['90'] != 0 ? $grandTotalByWeek['90'] : '',

            'Week39' => $grandTotalByWeek['91'] != 0 ? $grandTotalByWeek['91'] : '',
            'Sep' => collect($grandTotalByWeek)->only([88, 89, 90, 91])->sum() != 0 ? collect($grandTotalByWeek)->only([88, 89, 90, 91])->sum() : '',
            'Week40' => $grandTotalByWeek['92'] != 0 ? $grandTotalByWeek['92'] : '',
            'Week41' => $grandTotalByWeek['93'] != 0 ? $grandTotalByWeek['93'] : '',
            'Week42' => $grandTotalByWeek['94'] != 0 ? $grandTotalByWeek['94'] : '',
            'Week43' => $grandTotalByWeek['95'] != 0 ? $grandTotalByWeek['95'] : '',
            'Oct' => collect($grandTotalByWeek)->only([92, 93, 94, 95])->sum() != 0 ? collect($grandTotalByWeek)->only([92, 93, 94, 95])->sum() : '',
            'Week44' => $grandTotalByWeek['96'] != 0 ? $grandTotalByWeek['96'] : '',
            'Week45' => $grandTotalByWeek['97'] != 0 ? $grandTotalByWeek['97'] : '',
            'Week46' => $grandTotalByWeek['98'] != 0 ? $grandTotalByWeek['98'] : '',
            'Week47' => $grandTotalByWeek['99'] != 0 ? $grandTotalByWeek['99'] : '',
            'Week48' => $grandTotalByWeek['100'] != 0 ? $grandTotalByWeek['100'] : '',
            'Nov' => collect($grandTotalByWeek)->only([96, 97, 98, 99, 100])->sum() != 0 ? collect($grandTotalByWeek)->only([96, 97, 98, 99, 100])->sum() : '',
            'Week49' => $grandTotalByWeek['101'] != 0 ? $grandTotalByWeek['101'] : '',
            'Week50' => $grandTotalByWeek['102'] != 0 ? $grandTotalByWeek['102'] : '',
            'Week51' => $grandTotalByWeek['103'] != 0 ? $grandTotalByWeek['103'] : '',
            'Week52' => $grandTotalByWeek['104'] != 0 ? $grandTotalByWeek['104'] : '',
            'Dec' => collect($grandTotalByWeek)->only([101, 102, 103, 104])->sum() != 0 ? collect($grandTotalByWeek)->only([101, 102, 103, 104])->sum() : '',
            'GrandTotalByProgram' => $grandTotalForAllPrograms,
        ];

        $response = [
            'classes' => $mappedGroupedClasses,
        ];

        return response()->json($response);
    }

    public function dashboardClasses2(Request $request)
    {

        $query = Classes::with(['site', 'program', 'dateRange', 'createdByUser', 'updatedByUser', 'cancelledByUser'])
            ->whereHas('site', function ($query) use ($request) {
                $query->where('country', '=', 'Philippines')
                    ->where('is_active', '=', 1);
            })
            ->whereHas('program', function ($query) use ($request) {
                $query->where('is_active', '=', 1);
            })
            ->whereHas('dateRange', function ($query) use ($request) {
                $query->where('year', '=', '2024');
            });

        if ($request->has('sites_selected') && $request->sites_selected !== null) {
            $query->where('site_id', $request->sites_selected);
        }

        if ($request->has('programs_selected') && $request->programs_selected !== null) {
            $query->where('program_id', $request->programs_selected);
        }

        if ($request->has('month_selected') && $request->month_selected !== null) {
            $query->whereHas('dateRange', function ($query) use ($request) {
                $query->where('month_num', $request->month_selected);
            });
        }

        if ($request->has('week_selected') && $request->week_selected !== null) {
            $query->whereHas('dateRange', function ($query) use ($request) {
                $query->where('date_Range_id', $request->week_selected);
            });
        }

        $classes = $query->select([
            'id',
            'pushedback_id',
            'within_sla',
            'condition',
            'requested_by',
            'original_start_date',
            'changes',
            'agreed_start_date',
            'approved_date',
            'cancelled_date',
            'wfm_date_requested',
            'notice_weeks',
            'external_target',
            'internal_target',
            'notice_days',
            'pipeline_utilized',
            'total_target',
            'remarks',
            'status',
            'category',
            'type_of_hiring',
            'update_status',
            'approved_status',
            'with_erf',
            'erf_number',
            'approved_by',
            'cancelled_by',
            'ta',
            'wave_no',
            'wf',
            'tr',
            'cl',
            'op',
            'created_by',
            'site_id',
            'program_id',
            'updated_by',
            'date_range_id',
            'created_at',
            'updated_at',
        ])
            ->get();
        $formattedClasses = $classes->map(function ($class) {
            return [
                'id' => $class->id,
                'country' => $class->site->country,
                'region' => $class->site->region,
                'site_name' => $class->site->name,
                'program_name' => $class->program->name,
                'date_range' => $class->dateRange->date_range,
                'external_target' => $class->external_target,
                'internal_target' => $class->internal_target,
                'total_target' => $class->total_target,
                'within_sla' => $class->within_sla,
                'condition' => $class->condition,
                'original_start_date' => $class->original_start_date,
                'changes' => $class->changes,
                'agreed_start_date' => $class->agreed_start_date,
                'wfm_date_requested' => $class->wfm_date_requested,
                'notice_weeks' => number_format($class->notice_weeks, 2),
                'notice_days' => $class->notice_days,
                'pipeline_utilized' => $class->pipeline_utilized,
                'remarks' => $class->remarks,
                'status' => $class->status,
                'category' => $class->category,
                'type_of_hiring' => $class->type_of_hiring,
                'with_erf' => $class->with_erf,
                'erf_number' => $class->erf_number,
                'wave_no' => $class->wave_no,
                'ta' => $class->ta,
                'wf' => $class->wf,
                'tr' => $class->tr,
                'cl' => $class->cl,
                'op' => $class->op,
                'approved_by' => $class->approved_by,
                'cancelled_by' => $class->cancelledByUser ? $class->cancelledByUser->name : null,
                'requested_by' => $class->requested_by,
                'created_by' => $class->createdByUser->name,
                'updated_by' => $class->updatedByUser ? $class->updatedByUser->name : null,
                'approved_date' => $class->approved_date,
                'cancelled_date' => $class->cancelled_date,
                'created_at' => $class->created_at->format('m-d-Y H:i'),
                'updated_at' => $class->updated_at->format('m-d-Y H:i'),
            ];
        });

        return response()->json([
            'classes' => $formattedClasses,
        ]);
    }
    public function dashboardCancelledClasses(Request $request)
    {

        $query = Classes::with(['site', 'program', 'dateRange', 'createdByUser', 'updatedByUser', 'cancelledByUser'])
            ->where('status', 'Cancelled')
            ->whereHas('site', function ($query) use ($request) {
                $query->where('country', '=', 'Philippines')
                    ->where('is_active', '=', 1);
            })
            ->whereHas('program', function ($query) use ($request) {
                $query->where('is_active', '=', 1);
            })
            ->whereHas('dateRange', function ($query) use ($request) {
                $query->where('year', '=', '2024');
            });

        if ($request->has('sites_selected') && $request->sites_selected !== null) {
            $query->where('site_id', $request->sites_selected);
        }

        if ($request->has('programs_selected') && $request->programs_selected !== null) {
            $query->where('program_id', $request->programs_selected);
        }

        if ($request->has('month_selected') && $request->month_selected !== null) {
            $query->whereHas('dateRange', function ($query) use ($request) {
                $query->where('month_num', $request->month_selected);
            });
        }

        if ($request->has('week_selected') && $request->week_selected !== null) {
            $query->whereHas('dateRange', function ($query) use ($request) {
                $query->where('date_Range_id', $request->week_selected);
            });
        }


        $classes = $query->select([
            'id',
            'pushedback_id',
            'within_sla',
            'condition',
            'requested_by',
            'original_start_date',
            'changes',
            'agreed_start_date',
            'approved_date',
            'cancelled_date',
            'wfm_date_requested',
            'notice_weeks',
            'external_target',
            'internal_target',
            'notice_days',
            'pipeline_utilized',
            'total_target',
            'remarks',
            'status',
            'category',
            'type_of_hiring',
            'update_status',
            'approved_status',
            'with_erf',
            'erf_number',
            'approved_by',
            'cancelled_by',
            'ta',
            'wave_no',
            'wf',
            'tr',
            'cl',
            'op',
            'created_by',
            'site_id',
            'program_id',
            'updated_by',
            'date_range_id',
            'created_at',
            'updated_at',
        ])
            ->get();
        $formattedClasses = $classes->map(function ($class) {
            return [
                'id' => $class->id,
                'country' => $class->site->country,
                'region' => $class->site->region,
                'site_name' => $class->site->name,
                'program_name' => $class->program->name,
                'date_range' => $class->dateRange->date_range,
                'external_target' => $class->external_target,
                'internal_target' => $class->internal_target,
                'total_target' => $class->total_target,
                'within_sla' => $class->within_sla,
                'condition' => $class->condition,
                'original_start_date' => $class->original_start_date,
                'changes' => $class->changes,
                'agreed_start_date' => $class->agreed_start_date,
                'wfm_date_requested' => $class->wfm_date_requested,
                'notice_weeks' => number_format($class->notice_weeks, 2),
                'notice_days' => $class->notice_days,
                'pipeline_utilized' => $class->pipeline_utilized,
                'remarks' => $class->remarks,
                'status' => $class->status,
                'category' => $class->category,
                'type_of_hiring' => $class->type_of_hiring,
                'with_erf' => $class->with_erf,
                'erf_number' => $class->erf_number,
                'wave_no' => $class->wave_no,
                'ta' => $class->ta,
                'wf' => $class->wf,
                'tr' => $class->tr,
                'cl' => $class->cl,
                'op' => $class->op,
                'approved_by' => $class->approved_by,
                'cancelled_by' => $class->cancelledByUser ? $class->cancelledByUser->name : null,
                'requested_by' => $class->requested_by,
                'created_by' => $class->createdByUser->name,
                'updated_by' => $class->updatedByUser ? $class->updatedByUser->name : null,
                'approved_date' => $class->approved_date,
                'cancelled_date' => $class->cancelled_date,
                'created_at' => $class->created_at->format('m-d-Y H:i'),
                'updated_at' => $class->updated_at->format('m-d-Y H:i'),
            ];
        });


        return response()->json([
            'classes' => $formattedClasses,
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
            ->when(true, function ($query) {
                $query->whereHas('site', function ($subquery) {
                    $subquery->where('is_active', 1);
                });

                $query->whereHas('site', function ($subquery) {
                    $subquery->where('country', 'Philippines');
                });
            })

            ->where('is_active', 1) // You can keep or remove this line based on your requirements
            ->get();

        $year = 2024;
        $dateRanges = DateRange::where('year', $year)
            ->get();

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
                    '53' => 0,
                    '54' => 0,
                    '55' => 0,
                    '56' => 0,
                    '57' => 0,
                    '58' => 0,
                    '59' => 0,
                    '60' => 0,
                    '61' => 0,
                    '62' => 0,
                    '63' => 0,
                    '64' => 0,
                    '65' => 0,
                    '66' => 0,
                    '67' => 0,
                    '68' => 0,
                    '69' => 0,
                    '70' => 0,
                    '71' => 0,
                    '72' => 0,
                    '73' => 0,
                    '74' => 0,
                    '75' => 0,
                    '76' => 0,
                    '77' => 0,
                    '78' => 0,
                    '79' => 0,
                    '80' => 0,
                    '81' => 0,
                    '82' => 0,
                    '83' => 0,
                    '84' => 0,
                    '85' => 0,
                    '86' => 0,
                    '87' => 0,
                    '88' => 0,
                    '89' => 0,
                    '90' => 0,
                    '91' => 0,
                    '92' => 0,
                    '93' => 0,
                    '94' => 0,
                    '95' => 0,
                    '96' => 0,
                    '97' => 0,
                    '98' => 0,
                    '99' => 0,
                    '100' => 0,
                    '101' => 0,
                    '102' => 0,
                    '103' => 0,
                    '104' => 0,
                ];

                foreach ($programData as $week => $weekData) {
                    $weeklyData[$week] = isset($weekData['total_target']) ? $weekData['total_target'] : 0;
                }
                $grandTotal = $grandTotalByProgram[$siteName][$programName];
                if ($grandTotal != 0) {
                    $mappedGroupedClasses[] = [
                        'Site' => $siteName,
                        'Program' => $programName,
                        'Week1' => $weeklyData['53'] != 0 ? $weeklyData['53'] : '',

                        'Week2' => $weeklyData['54'] != 0 ? $weeklyData['54'] : '',
                        'Week3' => $weeklyData['55'] != 0 ? $weeklyData['55'] : '',
                        'Week4' => $weeklyData['56'] != 0 ? $weeklyData['56'] : '',
                        'Jan' => collect($weeklyData)->only([53, 54, 55, 56])->sum() != 0 ? collect($weeklyData)->only([53, 54, 55, 56])->sum() : '',
                        'Week5' => $weeklyData['57'] != 0 ? $weeklyData['57'] : '',
                        'Week6' => $weeklyData['58'] != 0 ? $weeklyData['58'] : '',
                        'Week7' => $weeklyData['59'] != 0 ? $weeklyData['59'] : '',
                        'Week8' => $weeklyData['60'] != 0 ? $weeklyData['60'] : '',
                        'Feb' => collect($weeklyData)->only([57, 58, 59, 60])->sum() != 0 ? collect($weeklyData)->only([57, 58, 59, 60])->sum() : '',
                        'Week9' => $weeklyData['61'] != 0 ? $weeklyData['61'] : '',
                        'Week10' => $weeklyData['62'] != 0 ? $weeklyData['62'] : '',
                        'Week11' => $weeklyData['63'] != 0 ? $weeklyData['63'] : '',
                        'Week12' => $weeklyData['64'] != 0 ? $weeklyData['64'] : '',
                        'Week13' => $weeklyData['65'] != 0 ? $weeklyData['65'] : '',
                        'Mar' => collect($weeklyData)->only([61, 62, 63, 64, 65])->sum() != 0 ? collect($weeklyData)->only([61, 62, 63, 64, 65])->sum() : '',
                        'Week14' => $weeklyData['66'] != 0 ? $weeklyData['66'] : '',
                        'Week15' => $weeklyData['67'] != 0 ? $weeklyData['67'] : '',
                        'Week16' => $weeklyData['68'] != 0 ? $weeklyData['68'] : '',
                        'Week17' => $weeklyData['69'] != 0 ? $weeklyData['69'] : '',
                        'Apr' => collect($weeklyData)->only([66, 67, 68, 69])->sum() != 0 ? collect($weeklyData)->only([66, 67, 68, 69])->sum() : '',
                        'Week18' => $weeklyData['70'] != 0 ? $weeklyData['70'] : '',
                        'Week19' => $weeklyData['71'] != 0 ? $weeklyData['71'] : '',
                        'Week20' => $weeklyData['72'] != 0 ? $weeklyData['72'] : '',
                        'Week21' => $weeklyData['73'] != 0 ? $weeklyData['73'] : '',
                        'May' => collect($weeklyData)->only([70, 71, 72, 73])->sum() != 0 ? collect($weeklyData)->only([70, 71, 72, 73])->sum() : '',
                        'Week22' => $weeklyData['74'] != 0 ? $weeklyData['74'] : '',
                        'Week23' => $weeklyData['75'] != 0 ? $weeklyData['75'] : '',
                        'Week24' => $weeklyData['76'] != 0 ? $weeklyData['76'] : '',
                        'Week25' => $weeklyData['77'] != 0 ? $weeklyData['77'] : '',
                        'Week26' => $weeklyData['78'] != 0 ? $weeklyData['78'] : '',
                        'Jun' => collect($weeklyData)->only([74, 75, 76, 77, 78])->sum() != 0 ? collect($weeklyData)->only([74, 75, 76, 77, 78])->sum() : '',
                        'Week27' => $weeklyData['79'] != 0 ? $weeklyData['79'] : '',
                        'Week28' => $weeklyData['80'] != 0 ? $weeklyData['80'] : '',
                        'Week29' => $weeklyData['81'] != 0 ? $weeklyData['81'] : '',
                        'Week30' => $weeklyData['82'] != 0 ? $weeklyData['82'] : '',
                        'Jul' => collect($weeklyData)->only([79, 80, 81, 82])->sum() != 0 ? collect($weeklyData)->only([79, 80, 81, 82])->sum() : '',
                        'Week31' => $weeklyData['83'] != 0 ? $weeklyData['83'] : '',
                        'Week32' => $weeklyData['84'] != 0 ? $weeklyData['84'] : '',
                        'Week33' => $weeklyData['85'] != 0 ? $weeklyData['85'] : '',
                        'Week34' => $weeklyData['86'] != 0 ? $weeklyData['86'] : '',
                        'Week35' => $weeklyData['87'] != 0 ? $weeklyData['87'] : '',
                        'Aug' => collect($weeklyData)->only([83, 84, 85, 86, 87])->sum() != 0 ? collect($weeklyData)->only([83, 84, 85, 86, 87])->sum() : '',
                        'Week36' => $weeklyData['88'] != 0 ? $weeklyData['88'] : '',
                        'Week37' => $weeklyData['89'] != 0 ? $weeklyData['89'] : '',
                        'Week38' => $weeklyData['90'] != 0 ? $weeklyData['90'] : '',
                        'Week39' => $weeklyData['91'] != 0 ? $weeklyData['91'] : '',
                        'Sep' => collect($weeklyData)->only([88, 89, 90, 91])->sum() != 0 ? collect($weeklyData)->only([88, 89, 90, 91])->sum() : '',
                        'Week40' => $weeklyData['92'] != 0 ? $weeklyData['92'] : '',
                        'Week41' => $weeklyData['93'] != 0 ? $weeklyData['93'] : '',
                        'Week42' => $weeklyData['94'] != 0 ? $weeklyData['94'] : '',
                        'Week43' => $weeklyData['95'] != 0 ? $weeklyData['95'] : '',
                        'Oct' => collect($weeklyData)->only([92, 93, 94, 95])->sum() != 0 ? collect($weeklyData)->only([92, 93, 94, 95])->sum() : '',
                        'Week44' => $weeklyData['96'] != 0 ? $weeklyData['96'] : '',
                        'Week45' => $weeklyData['97'] != 0 ? $weeklyData['97'] : '',
                        'Week46' => $weeklyData['98'] != 0 ? $weeklyData['98'] : '',
                        'Week47' => $weeklyData['99'] != 0 ? $weeklyData['99'] : '',
                        'Week48' => $weeklyData['100'] != 0 ? $weeklyData['100'] : '',
                        'Nov' => collect($weeklyData)->only([96, 97, 98, 99, 100])->sum() != 0 ? collect($weeklyData)->only([96, 97, 98, 99, 100])->sum() : '',
                        'Week49' => $weeklyData['101'] != 0 ? $weeklyData['101'] : '',
                        'Week50' => $weeklyData['102'] != 0 ? $weeklyData['102'] : '',
                        'Week51' => $weeklyData['103'] != 0 ? $weeklyData['103'] : '',
                        'Week52' => $weeklyData['104'] != 0 ? $weeklyData['104'] : '',
                        'Dec' => collect($weeklyData)->only([101, 102, 103, 104])->sum() != 0 ? collect($weeklyData)->only([101, 102, 103, 104])->sum() : '',

                        'GrandTotalByProgram' => $grandTotal,
                    ];
                }
            }
        }

        $grandTotalForAllPrograms = array_sum(array_map('array_sum', $grandTotalByProgram));

        $mappedGroupedClasses[] = [
            'Site' => 'Grand Total',
            'Program' => '',
            'Week1' => $grandTotalByWeek['53'] != 0 ? $grandTotalByWeek['53'] : '',
            'Week2' => $grandTotalByWeek['54'] != 0 ? $grandTotalByWeek['54'] : '',
            'Week3' => $grandTotalByWeek['55'] != 0 ? $grandTotalByWeek['55'] : '',
            'Week4' => $grandTotalByWeek['56'] != 0 ? $grandTotalByWeek['56'] : '',
            'Jan' => collect($grandTotalByWeek)->only([53, 54, 55, 56])->sum() != 0 ? collect($grandTotalByWeek)->only([53, 54, 55, 56])->sum() : '',
            'Week5' => $grandTotalByWeek['57'] != 0 ? $grandTotalByWeek['57'] : '',
            'Week6' => $grandTotalByWeek['58'] != 0 ? $grandTotalByWeek['58'] : '',
            'Week7' => $grandTotalByWeek['59'] != 0 ? $grandTotalByWeek['59'] : '',
            'Week8' => $grandTotalByWeek['60'] != 0 ? $grandTotalByWeek['60'] : '',
            'Feb' => collect($grandTotalByWeek)->only([57, 58, 59, 60])->sum() != 0 ? collect($grandTotalByWeek)->only([57, 58, 59, 60])->sum() : '',
            'Week9' => $grandTotalByWeek['61'] != 0 ? $grandTotalByWeek['61'] : '',
            'Week10' => $grandTotalByWeek['62'] != 0 ? $grandTotalByWeek['62'] : '',
            'Week11' => $grandTotalByWeek['63'] != 0 ? $grandTotalByWeek['63'] : '',
            'Week12' => $grandTotalByWeek['64'] != 0 ? $grandTotalByWeek['64'] : '',
            'Week13' => $grandTotalByWeek['65'] != 0 ? $grandTotalByWeek['65'] : '',
            'Mar' => collect($grandTotalByWeek)->only([61, 62, 63, 64, 65])->sum() != 0 ? collect($grandTotalByWeek)->only([61, 62, 63, 64, 65])->sum() : '',
            'Week14' => $grandTotalByWeek['66'] != 0 ? $grandTotalByWeek['66'] : '',
            'Week15' => $grandTotalByWeek['67'] != 0 ? $grandTotalByWeek['67'] : '',
            'Week16' => $grandTotalByWeek['68'] != 0 ? $grandTotalByWeek['68'] : '',
            'Week17' => $grandTotalByWeek['69'] != 0 ? $grandTotalByWeek['69'] : '',
            'Apr' => collect($grandTotalByWeek)->only([66, 67, 68, 69])->sum() != 0 ? collect($grandTotalByWeek)->only([66, 67, 68, 69])->sum() : '',
            'Week18' => $grandTotalByWeek['70'] != 0 ? $grandTotalByWeek['70'] : '',
            'Week19' => $grandTotalByWeek['71'] != 0 ? $grandTotalByWeek['71'] : '',
            'Week20' => $grandTotalByWeek['72'] != 0 ? $grandTotalByWeek['72'] : '',
            'Week21' => $grandTotalByWeek['73'] != 0 ? $grandTotalByWeek['73'] : '',
            'May' => collect($grandTotalByWeek)->only([70, 71, 72, 73])->sum() != 0 ? collect($grandTotalByWeek)->only([70, 71, 72, 73])->sum() : '',
            'Week22' => $grandTotalByWeek['74'] != 0 ? $grandTotalByWeek['74'] : '',
            'Week23' => $grandTotalByWeek['75'] != 0 ? $grandTotalByWeek['75'] : '',
            'Week24' => $grandTotalByWeek['76'] != 0 ? $grandTotalByWeek['76'] : '',
            'Week25' => $grandTotalByWeek['77'] != 0 ? $grandTotalByWeek['77'] : '',
            'Week26' => $grandTotalByWeek['78'] != 0 ? $grandTotalByWeek['78'] : '',
            'Jun' => collect($grandTotalByWeek)->only([74, 75, 76, 77, 78])->sum() != 0 ? collect($grandTotalByWeek)->only([74, 75, 76, 77, 78])->sum() : '',
            'Week27' => $grandTotalByWeek['79'] != 0 ? $grandTotalByWeek['79'] : '',
            'Week28' => $grandTotalByWeek['80'] != 0 ? $grandTotalByWeek['80'] : '',
            'Week29' => $grandTotalByWeek['81'] != 0 ? $grandTotalByWeek['81'] : '',
            'Week30' => $grandTotalByWeek['82'] != 0 ? $grandTotalByWeek['82'] : '',
            'Jul' => collect($grandTotalByWeek)->only([79, 80, 81, 82])->sum() != 0 ? collect($grandTotalByWeek)->only([79, 80, 81, 82])->sum() : '',
            'Week31' => $grandTotalByWeek['83'] != 0 ? $grandTotalByWeek['83'] : '',
            'Week32' => $grandTotalByWeek['84'] != 0 ? $grandTotalByWeek['84'] : '',
            'Week33' => $grandTotalByWeek['85'] != 0 ? $grandTotalByWeek['85'] : '',
            'Week34' => $grandTotalByWeek['86'] != 0 ? $grandTotalByWeek['86'] : '',
            'Week35' => $grandTotalByWeek['87'] != 0 ? $grandTotalByWeek['87'] : '',
            'Aug' => collect($grandTotalByWeek)->only([83, 84, 85, 86, 87])->sum() != 0 ? collect($grandTotalByWeek)->only([83, 84, 85, 86, 87])->sum() : '',
            'Week36' => $grandTotalByWeek['88'] != 0 ? $grandTotalByWeek['88'] : '',
            'Week37' => $grandTotalByWeek['89'] != 0 ? $grandTotalByWeek['89'] : '',
            'Week38' => $grandTotalByWeek['90'] != 0 ? $grandTotalByWeek['90'] : '',

            'Week39' => $grandTotalByWeek['91'] != 0 ? $grandTotalByWeek['91'] : '',
            'Sep' => collect($grandTotalByWeek)->only([88, 89, 90, 91])->sum() != 0 ? collect($grandTotalByWeek)->only([88, 89, 90, 91])->sum() : '',
            'Week40' => $grandTotalByWeek['92'] != 0 ? $grandTotalByWeek['92'] : '',
            'Week41' => $grandTotalByWeek['93'] != 0 ? $grandTotalByWeek['93'] : '',
            'Week42' => $grandTotalByWeek['94'] != 0 ? $grandTotalByWeek['94'] : '',
            'Week43' => $grandTotalByWeek['95'] != 0 ? $grandTotalByWeek['95'] : '',
            'Oct' => collect($grandTotalByWeek)->only([92, 93, 94, 95])->sum() != 0 ? collect($grandTotalByWeek)->only([92, 93, 94, 95])->sum() : '',
            'Week44' => $grandTotalByWeek['96'] != 0 ? $grandTotalByWeek['96'] : '',
            'Week45' => $grandTotalByWeek['97'] != 0 ? $grandTotalByWeek['97'] : '',
            'Week46' => $grandTotalByWeek['98'] != 0 ? $grandTotalByWeek['98'] : '',
            'Week47' => $grandTotalByWeek['99'] != 0 ? $grandTotalByWeek['99'] : '',
            'Week48' => $grandTotalByWeek['100'] != 0 ? $grandTotalByWeek['100'] : '',
            'Nov' => collect($grandTotalByWeek)->only([96, 97, 98, 99, 100])->sum() != 0 ? collect($grandTotalByWeek)->only([96, 97, 98, 99, 100])->sum() : '',
            'Week49' => $grandTotalByWeek['101'] != 0 ? $grandTotalByWeek['101'] : '',
            'Week50' => $grandTotalByWeek['102'] != 0 ? $grandTotalByWeek['102'] : '',
            'Week51' => $grandTotalByWeek['103'] != 0 ? $grandTotalByWeek['103'] : '',
            'Week52' => $grandTotalByWeek['104'] != 0 ? $grandTotalByWeek['104'] : '',
            'Dec' => collect($grandTotalByWeek)->only([101, 102, 103, 104])->sum() != 0 ? collect($grandTotalByWeek)->only([101, 102, 103, 104])->sum() : '',
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
            ->when(true, function ($query) {
                $query->whereHas('site', function ($subquery) {
                    $subquery->where('is_active', 1);
                });

                $query->whereHas('site', function ($subquery) {
                    $subquery->where('country', 'Philippines');
                });
            })

            ->where('is_active', 1) // You can keep or remove this line based on your requirements
            ->get();

        $year = 2024;
        $dateRanges = DateRange::where('year', $year)
            ->get();

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
                    '53' => 0,
                    '54' => 0,
                    '55' => 0,
                    '56' => 0,
                    '57' => 0,
                    '58' => 0,
                    '59' => 0,
                    '60' => 0,
                    '61' => 0,
                    '62' => 0,
                    '63' => 0,
                    '64' => 0,
                    '65' => 0,
                    '66' => 0,
                    '67' => 0,
                    '68' => 0,
                    '69' => 0,
                    '70' => 0,
                    '71' => 0,
                    '72' => 0,
                    '73' => 0,
                    '74' => 0,
                    '75' => 0,
                    '76' => 0,
                    '77' => 0,
                    '78' => 0,
                    '79' => 0,
                    '80' => 0,
                    '81' => 0,
                    '82' => 0,
                    '83' => 0,
                    '84' => 0,
                    '85' => 0,
                    '86' => 0,
                    '87' => 0,
                    '88' => 0,
                    '89' => 0,
                    '90' => 0,
                    '91' => 0,
                    '92' => 0,
                    '93' => 0,
                    '94' => 0,
                    '95' => 0,
                    '96' => 0,
                    '97' => 0,
                    '98' => 0,
                    '99' => 0,
                    '100' => 0,
                    '101' => 0,
                    '102' => 0,
                    '103' => 0,
                    '104' => 0,
                ];

                foreach ($programData as $week => $weekData) {
                    $weeklyData[$week] = isset($weekData['total_target']) ? $weekData['total_target'] : 0;
                }
                $grandTotal = $grandTotalByProgram[$siteName][$programName];
                if ($grandTotal != 0) {
                    $mappedGroupedClasses[] = [
                        'Site' => $siteName,
                        'Program' => $programName,
                        'Week1' => $weeklyData['53'] != 0 ? $weeklyData['53'] : '',
                        'Week2' => $weeklyData['54'] != 0 ? $weeklyData['54'] : '',
                        'Week3' => $weeklyData['55'] != 0 ? $weeklyData['55'] : '',
                        'Week4' => $weeklyData['56'] != 0 ? $weeklyData['56'] : '',
                        'Jan' => collect($weeklyData)->only([53, 54, 55, 56])->sum() != 0 ? collect($weeklyData)->only([53, 54, 55, 56])->sum() : '',
                        'Week5' => $weeklyData['57'] != 0 ? $weeklyData['57'] : '',
                        'Week6' => $weeklyData['58'] != 0 ? $weeklyData['58'] : '',
                        'Week7' => $weeklyData['59'] != 0 ? $weeklyData['59'] : '',
                        'Week8' => $weeklyData['60'] != 0 ? $weeklyData['60'] : '',
                        'Feb' => collect($weeklyData)->only([57, 58, 59, 60])->sum() != 0 ? collect($weeklyData)->only([57, 58, 59, 60])->sum() : '',
                        'Week9' => $weeklyData['61'] != 0 ? $weeklyData['61'] : '',
                        'Week10' => $weeklyData['62'] != 0 ? $weeklyData['62'] : '',
                        'Week11' => $weeklyData['63'] != 0 ? $weeklyData['63'] : '',
                        'Week12' => $weeklyData['64'] != 0 ? $weeklyData['64'] : '',
                        'Week13' => $weeklyData['65'] != 0 ? $weeklyData['65'] : '',
                        'Mar' => collect($weeklyData)->only([61, 62, 63, 64, 65])->sum() != 0 ? collect($weeklyData)->only([61, 62, 63, 64, 65])->sum() : '',
                        'Week14' => $weeklyData['66'] != 0 ? $weeklyData['66'] : '',
                        'Week15' => $weeklyData['67'] != 0 ? $weeklyData['67'] : '',
                        'Week16' => $weeklyData['68'] != 0 ? $weeklyData['68'] : '',
                        'Week17' => $weeklyData['69'] != 0 ? $weeklyData['69'] : '',
                        'Apr' => collect($weeklyData)->only([66, 67, 68, 69])->sum() != 0 ? collect($weeklyData)->only([66, 67, 68, 69])->sum() : '',
                        'Week18' => $weeklyData['70'] != 0 ? $weeklyData['70'] : '',
                        'Week19' => $weeklyData['71'] != 0 ? $weeklyData['71'] : '',
                        'Week20' => $weeklyData['72'] != 0 ? $weeklyData['72'] : '',
                        'Week21' => $weeklyData['73'] != 0 ? $weeklyData['73'] : '',
                        'May' => collect($weeklyData)->only([70, 71, 72, 73])->sum() != 0 ? collect($weeklyData)->only([70, 71, 72, 73])->sum() : '',
                        'Week22' => $weeklyData['74'] != 0 ? $weeklyData['74'] : '',
                        'Week23' => $weeklyData['75'] != 0 ? $weeklyData['75'] : '',
                        'Week24' => $weeklyData['76'] != 0 ? $weeklyData['76'] : '',
                        'Week25' => $weeklyData['77'] != 0 ? $weeklyData['77'] : '',
                        'Week26' => $weeklyData['78'] != 0 ? $weeklyData['78'] : '',
                        'Jun' => collect($weeklyData)->only([74, 75, 76, 77, 78])->sum() != 0 ? collect($weeklyData)->only([74, 75, 76, 77, 78])->sum() : '',
                        'Week27' => $weeklyData['79'] != 0 ? $weeklyData['79'] : '',
                        'Week28' => $weeklyData['80'] != 0 ? $weeklyData['80'] : '',
                        'Week29' => $weeklyData['81'] != 0 ? $weeklyData['81'] : '',
                        'Week30' => $weeklyData['82'] != 0 ? $weeklyData['82'] : '',
                        'Jul' => collect($weeklyData)->only([79, 80, 81, 82])->sum() != 0 ? collect($weeklyData)->only([79, 80, 81, 82])->sum() : '',
                        'Week31' => $weeklyData['83'] != 0 ? $weeklyData['83'] : '',
                        'Week32' => $weeklyData['84'] != 0 ? $weeklyData['84'] : '',
                        'Week33' => $weeklyData['85'] != 0 ? $weeklyData['85'] : '',
                        'Week34' => $weeklyData['86'] != 0 ? $weeklyData['86'] : '',
                        'Week35' => $weeklyData['87'] != 0 ? $weeklyData['87'] : '',
                        'Aug' => collect($weeklyData)->only([83, 84, 85, 86, 87])->sum() != 0 ? collect($weeklyData)->only([83, 84, 85, 86, 87])->sum() : '',
                        'Week36' => $weeklyData['88'] != 0 ? $weeklyData['88'] : '',
                        'Week37' => $weeklyData['89'] != 0 ? $weeklyData['89'] : '',
                        'Week38' => $weeklyData['90'] != 0 ? $weeklyData['90'] : '',
                        'Week39' => $weeklyData['91'] != 0 ? $weeklyData['91'] : '',
                        'Sep' => collect($weeklyData)->only([88, 89, 90, 91])->sum() != 0 ? collect($weeklyData)->only([88, 89, 90, 91])->sum() : '',
                        'Week40' => $weeklyData['92'] != 0 ? $weeklyData['92'] : '',
                        'Week41' => $weeklyData['93'] != 0 ? $weeklyData['93'] : '',
                        'Week42' => $weeklyData['94'] != 0 ? $weeklyData['94'] : '',
                        'Week43' => $weeklyData['95'] != 0 ? $weeklyData['95'] : '',
                        'Oct' => collect($weeklyData)->only([92, 93, 94, 95])->sum() != 0 ? collect($weeklyData)->only([92, 93, 94, 95])->sum() : '',
                        'Week44' => $weeklyData['96'] != 0 ? $weeklyData['96'] : '',
                        'Week45' => $weeklyData['97'] != 0 ? $weeklyData['97'] : '',
                        'Week46' => $weeklyData['98'] != 0 ? $weeklyData['98'] : '',
                        'Week47' => $weeklyData['99'] != 0 ? $weeklyData['99'] : '',
                        'Week48' => $weeklyData['100'] != 0 ? $weeklyData['100'] : '',
                        'Nov' => collect($weeklyData)->only([96, 97, 98, 99, 100])->sum() != 0 ? collect($weeklyData)->only([96, 97, 98, 99, 100])->sum() : '',
                        'Week49' => $weeklyData['101'] != 0 ? $weeklyData['101'] : '',
                        'Week50' => $weeklyData['102'] != 0 ? $weeklyData['102'] : '',
                        'Week51' => $weeklyData['103'] != 0 ? $weeklyData['103'] : '',
                        'Week52' => $weeklyData['104'] != 0 ? $weeklyData['104'] : '',
                        'Dec' => collect($weeklyData)->only([101, 102, 103, 104])->sum() != 0 ? collect($weeklyData)->only([101, 102, 103, 104])->sum() : '',

                        'GrandTotalByProgram' => $grandTotal,
                    ];
                }
            }
        }

        $grandTotalForAllPrograms = array_sum(array_map('array_sum', $grandTotalByProgram));

        $mappedGroupedClasses[] = [
            'Site' => 'Grand Total',
            'Program' => '',
            'Week1' => $grandTotalByWeek['53'] != 0 ? $grandTotalByWeek['53'] : '',
            'Week2' => $grandTotalByWeek['54'] != 0 ? $grandTotalByWeek['54'] : '',
            'Week3' => $grandTotalByWeek['55'] != 0 ? $grandTotalByWeek['55'] : '',
            'Week4' => $grandTotalByWeek['56'] != 0 ? $grandTotalByWeek['56'] : '',
            'Jan' => collect($grandTotalByWeek)->only([53, 54, 55, 56])->sum() != 0 ? collect($grandTotalByWeek)->only([53, 54, 55, 56])->sum() : '',
            'Week5' => $grandTotalByWeek['57'] != 0 ? $grandTotalByWeek['57'] : '',
            'Week6' => $grandTotalByWeek['58'] != 0 ? $grandTotalByWeek['58'] : '',
            'Week7' => $grandTotalByWeek['59'] != 0 ? $grandTotalByWeek['59'] : '',
            'Week8' => $grandTotalByWeek['60'] != 0 ? $grandTotalByWeek['60'] : '',
            'Feb' => collect($grandTotalByWeek)->only([57, 58, 59, 60])->sum() != 0 ? collect($grandTotalByWeek)->only([57, 58, 59, 60])->sum() : '',
            'Week9' => $grandTotalByWeek['61'] != 0 ? $grandTotalByWeek['61'] : '',
            'Week10' => $grandTotalByWeek['62'] != 0 ? $grandTotalByWeek['62'] : '',
            'Week11' => $grandTotalByWeek['63'] != 0 ? $grandTotalByWeek['63'] : '',
            'Week12' => $grandTotalByWeek['64'] != 0 ? $grandTotalByWeek['64'] : '',
            'Week13' => $grandTotalByWeek['65'] != 0 ? $grandTotalByWeek['65'] : '',
            'Mar' => collect($grandTotalByWeek)->only([61, 62, 63, 64, 65])->sum() != 0 ? collect($grandTotalByWeek)->only([61, 62, 63, 64, 65])->sum() : '',
            'Week14' => $grandTotalByWeek['66'] != 0 ? $grandTotalByWeek['66'] : '',
            'Week15' => $grandTotalByWeek['67'] != 0 ? $grandTotalByWeek['67'] : '',
            'Week16' => $grandTotalByWeek['68'] != 0 ? $grandTotalByWeek['68'] : '',
            'Week17' => $grandTotalByWeek['69'] != 0 ? $grandTotalByWeek['69'] : '',
            'Apr' => collect($grandTotalByWeek)->only([66, 67, 68, 69])->sum() != 0 ? collect($grandTotalByWeek)->only([66, 67, 68, 69])->sum() : '',
            'Week18' => $grandTotalByWeek['70'] != 0 ? $grandTotalByWeek['70'] : '',
            'Week19' => $grandTotalByWeek['71'] != 0 ? $grandTotalByWeek['71'] : '',
            'Week20' => $grandTotalByWeek['72'] != 0 ? $grandTotalByWeek['72'] : '',
            'Week21' => $grandTotalByWeek['73'] != 0 ? $grandTotalByWeek['73'] : '',
            'May' => collect($grandTotalByWeek)->only([70, 71, 72, 73])->sum() != 0 ? collect($grandTotalByWeek)->only([70, 71, 72, 73])->sum() : '',
            'Week22' => $grandTotalByWeek['74'] != 0 ? $grandTotalByWeek['74'] : '',
            'Week23' => $grandTotalByWeek['75'] != 0 ? $grandTotalByWeek['75'] : '',
            'Week24' => $grandTotalByWeek['76'] != 0 ? $grandTotalByWeek['76'] : '',
            'Week25' => $grandTotalByWeek['77'] != 0 ? $grandTotalByWeek['77'] : '',
            'Week26' => $grandTotalByWeek['78'] != 0 ? $grandTotalByWeek['78'] : '',
            'Jun' => collect($grandTotalByWeek)->only([74, 75, 76, 77, 78])->sum() != 0 ? collect($grandTotalByWeek)->only([74, 75, 76, 77, 78])->sum() : '',
            'Week27' => $grandTotalByWeek['79'] != 0 ? $grandTotalByWeek['79'] : '',
            'Week28' => $grandTotalByWeek['80'] != 0 ? $grandTotalByWeek['80'] : '',
            'Week29' => $grandTotalByWeek['81'] != 0 ? $grandTotalByWeek['81'] : '',
            'Week30' => $grandTotalByWeek['82'] != 0 ? $grandTotalByWeek['82'] : '',
            'Jul' => collect($grandTotalByWeek)->only([79, 80, 81, 82])->sum() != 0 ? collect($grandTotalByWeek)->only([79, 80, 81, 82])->sum() : '',
            'Week31' => $grandTotalByWeek['83'] != 0 ? $grandTotalByWeek['83'] : '',
            'Week32' => $grandTotalByWeek['84'] != 0 ? $grandTotalByWeek['84'] : '',
            'Week33' => $grandTotalByWeek['85'] != 0 ? $grandTotalByWeek['85'] : '',
            'Week34' => $grandTotalByWeek['86'] != 0 ? $grandTotalByWeek['86'] : '',
            'Week35' => $grandTotalByWeek['87'] != 0 ? $grandTotalByWeek['87'] : '',
            'Aug' => collect($grandTotalByWeek)->only([83, 84, 85, 86, 87])->sum() != 0 ? collect($grandTotalByWeek)->only([83, 84, 85, 86, 87])->sum() : '',
            'Week36' => $grandTotalByWeek['88'] != 0 ? $grandTotalByWeek['88'] : '',
            'Week37' => $grandTotalByWeek['89'] != 0 ? $grandTotalByWeek['89'] : '',
            'Week38' => $grandTotalByWeek['90'] != 0 ? $grandTotalByWeek['90'] : '',

            'Week39' => $grandTotalByWeek['91'] != 0 ? $grandTotalByWeek['91'] : '',
            'Sep' => collect($grandTotalByWeek)->only([88, 89, 90, 91])->sum() != 0 ? collect($grandTotalByWeek)->only([88, 89, 90, 91])->sum() : '',
            'Week40' => $grandTotalByWeek['92'] != 0 ? $grandTotalByWeek['92'] : '',
            'Week41' => $grandTotalByWeek['93'] != 0 ? $grandTotalByWeek['93'] : '',
            'Week42' => $grandTotalByWeek['94'] != 0 ? $grandTotalByWeek['94'] : '',
            'Week43' => $grandTotalByWeek['95'] != 0 ? $grandTotalByWeek['95'] : '',
            'Oct' => collect($grandTotalByWeek)->only([92, 93, 94, 95])->sum() != 0 ? collect($grandTotalByWeek)->only([92, 93, 94, 95])->sum() : '',
            'Week44' => $grandTotalByWeek['96'] != 0 ? $grandTotalByWeek['96'] : '',
            'Week45' => $grandTotalByWeek['97'] != 0 ? $grandTotalByWeek['97'] : '',
            'Week46' => $grandTotalByWeek['98'] != 0 ? $grandTotalByWeek['98'] : '',
            'Week47' => $grandTotalByWeek['99'] != 0 ? $grandTotalByWeek['99'] : '',
            'Week48' => $grandTotalByWeek['100'] != 0 ? $grandTotalByWeek['100'] : '',
            'Nov' => collect($grandTotalByWeek)->only([96, 97, 98, 99, 100])->sum() != 0 ? collect($grandTotalByWeek)->only([96, 97, 98, 99, 100])->sum() : '',
            'Week49' => $grandTotalByWeek['101'] != 0 ? $grandTotalByWeek['101'] : '',
            'Week50' => $grandTotalByWeek['102'] != 0 ? $grandTotalByWeek['102'] : '',
            'Week51' => $grandTotalByWeek['103'] != 0 ? $grandTotalByWeek['103'] : '',
            'Week52' => $grandTotalByWeek['104'] != 0 ? $grandTotalByWeek['104'] : '',
            'Dec' => collect($grandTotalByWeek)->only([101, 102, 103, 104])->sum() != 0 ? collect($grandTotalByWeek)->only([101, 102, 103, 104])->sum() : '',
            'GrandTotalByProgram' => $grandTotalForAllPrograms,
        ];

        $response = [
            'classes' => $mappedGroupedClasses,
        ];

        return response()->json($response);
    }
    public function dashboardClassesJamaica(Request $request)
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
            ->when(true, function ($query) {
                $query->whereHas('site', function ($subquery) {
                    $subquery->where('is_active', 1);
                });

                $query->whereHas('site', function ($subquery) {
                    $subquery->where('country', 'Jamaica');
                });
            })

            ->where('is_active', 1) // You can keep or remove this line based on your requirements
            ->get();

        $year = 2024;
        $dateRanges = DateRange::where('year', $year)
            ->get();

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
                    '53' => 0,
                    '54' => 0,
                    '55' => 0,
                    '56' => 0,
                    '57' => 0,
                    '58' => 0,
                    '59' => 0,
                    '60' => 0,
                    '61' => 0,
                    '62' => 0,
                    '63' => 0,
                    '64' => 0,
                    '65' => 0,
                    '66' => 0,
                    '67' => 0,
                    '68' => 0,
                    '69' => 0,
                    '70' => 0,
                    '71' => 0,
                    '72' => 0,
                    '73' => 0,
                    '74' => 0,
                    '75' => 0,
                    '76' => 0,
                    '77' => 0,
                    '78' => 0,
                    '79' => 0,
                    '80' => 0,
                    '81' => 0,
                    '82' => 0,
                    '83' => 0,
                    '84' => 0,
                    '85' => 0,
                    '86' => 0,
                    '87' => 0,
                    '88' => 0,
                    '89' => 0,
                    '90' => 0,
                    '91' => 0,
                    '92' => 0,
                    '93' => 0,
                    '94' => 0,
                    '95' => 0,
                    '96' => 0,
                    '97' => 0,
                    '98' => 0,
                    '99' => 0,
                    '100' => 0,
                    '101' => 0,
                    '102' => 0,
                    '103' => 0,
                    '104' => 0,
                ];

                foreach ($programData as $week => $weekData) {
                    $weeklyData[$week] = isset($weekData['total_target']) ? $weekData['total_target'] : 0;
                }
                $grandTotal = $grandTotalByProgram[$siteName][$programName];
                if ($grandTotal != 0) {
                    $mappedGroupedClasses[] = [
                        'Site' => $siteName,
                        'Program' => $programName,
                        'Week1' => $weeklyData['53'] != 0 ? $weeklyData['53'] : '',

                        'Week2' => $weeklyData['54'] != 0 ? $weeklyData['54'] : '',
                        'Week3' => $weeklyData['55'] != 0 ? $weeklyData['55'] : '',
                        'Week4' => $weeklyData['56'] != 0 ? $weeklyData['56'] : '',
                        'Jan' => collect($weeklyData)->only([53, 54, 55, 56])->sum() != 0 ? collect($weeklyData)->only([53, 54, 55, 56])->sum() : '',
                        'Week5' => $weeklyData['57'] != 0 ? $weeklyData['57'] : '',
                        'Week6' => $weeklyData['58'] != 0 ? $weeklyData['58'] : '',
                        'Week7' => $weeklyData['59'] != 0 ? $weeklyData['59'] : '',
                        'Week8' => $weeklyData['60'] != 0 ? $weeklyData['60'] : '',
                        'Feb' => collect($weeklyData)->only([57, 58, 59, 60])->sum() != 0 ? collect($weeklyData)->only([57, 58, 59, 60])->sum() : '',
                        'Week9' => $weeklyData['61'] != 0 ? $weeklyData['61'] : '',
                        'Week10' => $weeklyData['62'] != 0 ? $weeklyData['62'] : '',
                        'Week11' => $weeklyData['63'] != 0 ? $weeklyData['63'] : '',
                        'Week12' => $weeklyData['64'] != 0 ? $weeklyData['64'] : '',
                        'Week13' => $weeklyData['65'] != 0 ? $weeklyData['65'] : '',
                        'Mar' => collect($weeklyData)->only([61, 62, 63, 64, 65])->sum() != 0 ? collect($weeklyData)->only([61, 62, 63, 64, 65])->sum() : '',
                        'Week14' => $weeklyData['66'] != 0 ? $weeklyData['66'] : '',
                        'Week15' => $weeklyData['67'] != 0 ? $weeklyData['67'] : '',
                        'Week16' => $weeklyData['68'] != 0 ? $weeklyData['68'] : '',
                        'Week17' => $weeklyData['69'] != 0 ? $weeklyData['69'] : '',
                        'Apr' => collect($weeklyData)->only([66, 67, 68, 69])->sum() != 0 ? collect($weeklyData)->only([66, 67, 68, 69])->sum() : '',
                        'Week18' => $weeklyData['70'] != 0 ? $weeklyData['70'] : '',
                        'Week19' => $weeklyData['71'] != 0 ? $weeklyData['71'] : '',
                        'Week20' => $weeklyData['72'] != 0 ? $weeklyData['72'] : '',
                        'Week21' => $weeklyData['73'] != 0 ? $weeklyData['73'] : '',
                        'May' => collect($weeklyData)->only([70, 71, 72, 73])->sum() != 0 ? collect($weeklyData)->only([70, 71, 72, 73])->sum() : '',
                        'Week22' => $weeklyData['74'] != 0 ? $weeklyData['74'] : '',
                        'Week23' => $weeklyData['75'] != 0 ? $weeklyData['75'] : '',
                        'Week24' => $weeklyData['76'] != 0 ? $weeklyData['76'] : '',
                        'Week25' => $weeklyData['77'] != 0 ? $weeklyData['77'] : '',
                        'Week26' => $weeklyData['78'] != 0 ? $weeklyData['78'] : '',
                        'Jun' => collect($weeklyData)->only([74, 75, 76, 77, 78])->sum() != 0 ? collect($weeklyData)->only([74, 75, 76, 77, 78])->sum() : '',
                        'Week27' => $weeklyData['79'] != 0 ? $weeklyData['79'] : '',
                        'Week28' => $weeklyData['80'] != 0 ? $weeklyData['80'] : '',
                        'Week29' => $weeklyData['81'] != 0 ? $weeklyData['81'] : '',
                        'Week30' => $weeklyData['82'] != 0 ? $weeklyData['82'] : '',
                        'Jul' => collect($weeklyData)->only([79, 80, 81, 82])->sum() != 0 ? collect($weeklyData)->only([79, 80, 81, 82])->sum() : '',
                        'Week31' => $weeklyData['83'] != 0 ? $weeklyData['83'] : '',
                        'Week32' => $weeklyData['84'] != 0 ? $weeklyData['84'] : '',
                        'Week33' => $weeklyData['85'] != 0 ? $weeklyData['85'] : '',
                        'Week34' => $weeklyData['86'] != 0 ? $weeklyData['86'] : '',
                        'Week35' => $weeklyData['87'] != 0 ? $weeklyData['87'] : '',
                        'Aug' => collect($weeklyData)->only([83, 84, 85, 86, 87])->sum() != 0 ? collect($weeklyData)->only([83, 84, 85, 86, 87])->sum() : '',
                        'Week36' => $weeklyData['88'] != 0 ? $weeklyData['88'] : '',
                        'Week37' => $weeklyData['89'] != 0 ? $weeklyData['89'] : '',
                        'Week38' => $weeklyData['90'] != 0 ? $weeklyData['90'] : '',
                        'Week39' => $weeklyData['91'] != 0 ? $weeklyData['91'] : '',
                        'Sep' => collect($weeklyData)->only([88, 89, 90, 91])->sum() != 0 ? collect($weeklyData)->only([88, 89, 90, 91])->sum() : '',
                        'Week40' => $weeklyData['92'] != 0 ? $weeklyData['92'] : '',
                        'Week41' => $weeklyData['93'] != 0 ? $weeklyData['93'] : '',
                        'Week42' => $weeklyData['94'] != 0 ? $weeklyData['94'] : '',
                        'Week43' => $weeklyData['95'] != 0 ? $weeklyData['95'] : '',
                        'Oct' => collect($weeklyData)->only([92, 93, 94, 95])->sum() != 0 ? collect($weeklyData)->only([92, 93, 94, 95])->sum() : '',
                        'Week44' => $weeklyData['96'] != 0 ? $weeklyData['96'] : '',
                        'Week45' => $weeklyData['97'] != 0 ? $weeklyData['97'] : '',
                        'Week46' => $weeklyData['98'] != 0 ? $weeklyData['98'] : '',
                        'Week47' => $weeklyData['99'] != 0 ? $weeklyData['99'] : '',
                        'Week48' => $weeklyData['100'] != 0 ? $weeklyData['100'] : '',
                        'Nov' => collect($weeklyData)->only([96, 97, 98, 99, 100])->sum() != 0 ? collect($weeklyData)->only([96, 97, 98, 99, 100])->sum() : '',
                        'Week49' => $weeklyData['101'] != 0 ? $weeklyData['101'] : '',
                        'Week50' => $weeklyData['102'] != 0 ? $weeklyData['102'] : '',
                        'Week51' => $weeklyData['103'] != 0 ? $weeklyData['103'] : '',
                        'Week52' => $weeklyData['104'] != 0 ? $weeklyData['104'] : '',
                        'Dec' => collect($weeklyData)->only([101, 102, 103, 104])->sum() != 0 ? collect($weeklyData)->only([101, 102, 103, 104])->sum() : '',

                        'GrandTotalByProgram' => $grandTotal,
                    ];
                }
            }
        }

        $grandTotalForAllPrograms = array_sum(array_map('array_sum', $grandTotalByProgram));

        $mappedGroupedClasses[] = [
            'Site' => 'Grand Total',
            'Program' => '',
            'Week1' => $grandTotalByWeek['53'] != 0 ? $grandTotalByWeek['53'] : '',
            'Week2' => $grandTotalByWeek['54'] != 0 ? $grandTotalByWeek['54'] : '',
            'Week3' => $grandTotalByWeek['55'] != 0 ? $grandTotalByWeek['55'] : '',
            'Week4' => $grandTotalByWeek['56'] != 0 ? $grandTotalByWeek['56'] : '',
            'Jan' => collect($grandTotalByWeek)->only([53, 54, 55, 56])->sum() != 0 ? collect($grandTotalByWeek)->only([53, 54, 55, 56])->sum() : '',
            'Week5' => $grandTotalByWeek['57'] != 0 ? $grandTotalByWeek['57'] : '',
            'Week6' => $grandTotalByWeek['58'] != 0 ? $grandTotalByWeek['58'] : '',
            'Week7' => $grandTotalByWeek['59'] != 0 ? $grandTotalByWeek['59'] : '',
            'Week8' => $grandTotalByWeek['60'] != 0 ? $grandTotalByWeek['60'] : '',
            'Feb' => collect($grandTotalByWeek)->only([57, 58, 59, 60])->sum() != 0 ? collect($grandTotalByWeek)->only([57, 58, 59, 60])->sum() : '',
            'Week9' => $grandTotalByWeek['61'] != 0 ? $grandTotalByWeek['61'] : '',
            'Week10' => $grandTotalByWeek['62'] != 0 ? $grandTotalByWeek['62'] : '',
            'Week11' => $grandTotalByWeek['63'] != 0 ? $grandTotalByWeek['63'] : '',
            'Week12' => $grandTotalByWeek['64'] != 0 ? $grandTotalByWeek['64'] : '',
            'Week13' => $grandTotalByWeek['65'] != 0 ? $grandTotalByWeek['65'] : '',
            'Mar' => collect($grandTotalByWeek)->only([61, 62, 63, 64, 65])->sum() != 0 ? collect($grandTotalByWeek)->only([61, 62, 63, 64, 65])->sum() : '',
            'Week14' => $grandTotalByWeek['66'] != 0 ? $grandTotalByWeek['66'] : '',
            'Week15' => $grandTotalByWeek['67'] != 0 ? $grandTotalByWeek['67'] : '',
            'Week16' => $grandTotalByWeek['68'] != 0 ? $grandTotalByWeek['68'] : '',
            'Week17' => $grandTotalByWeek['69'] != 0 ? $grandTotalByWeek['69'] : '',
            'Apr' => collect($grandTotalByWeek)->only([66, 67, 68, 69])->sum() != 0 ? collect($grandTotalByWeek)->only([66, 67, 68, 69])->sum() : '',
            'Week18' => $grandTotalByWeek['70'] != 0 ? $grandTotalByWeek['70'] : '',
            'Week19' => $grandTotalByWeek['71'] != 0 ? $grandTotalByWeek['71'] : '',
            'Week20' => $grandTotalByWeek['72'] != 0 ? $grandTotalByWeek['72'] : '',
            'Week21' => $grandTotalByWeek['73'] != 0 ? $grandTotalByWeek['73'] : '',
            'May' => collect($grandTotalByWeek)->only([70, 71, 72, 73])->sum() != 0 ? collect($grandTotalByWeek)->only([70, 71, 72, 73])->sum() : '',
            'Week22' => $grandTotalByWeek['74'] != 0 ? $grandTotalByWeek['74'] : '',
            'Week23' => $grandTotalByWeek['75'] != 0 ? $grandTotalByWeek['75'] : '',
            'Week24' => $grandTotalByWeek['76'] != 0 ? $grandTotalByWeek['76'] : '',
            'Week25' => $grandTotalByWeek['77'] != 0 ? $grandTotalByWeek['77'] : '',
            'Week26' => $grandTotalByWeek['78'] != 0 ? $grandTotalByWeek['78'] : '',
            'Jun' => collect($grandTotalByWeek)->only([74, 75, 76, 77, 78])->sum() != 0 ? collect($grandTotalByWeek)->only([74, 75, 76, 77, 78])->sum() : '',
            'Week27' => $grandTotalByWeek['79'] != 0 ? $grandTotalByWeek['79'] : '',
            'Week28' => $grandTotalByWeek['80'] != 0 ? $grandTotalByWeek['80'] : '',
            'Week29' => $grandTotalByWeek['81'] != 0 ? $grandTotalByWeek['81'] : '',
            'Week30' => $grandTotalByWeek['82'] != 0 ? $grandTotalByWeek['82'] : '',
            'Jul' => collect($grandTotalByWeek)->only([79, 80, 81, 82])->sum() != 0 ? collect($grandTotalByWeek)->only([79, 80, 81, 82])->sum() : '',
            'Week31' => $grandTotalByWeek['83'] != 0 ? $grandTotalByWeek['83'] : '',
            'Week32' => $grandTotalByWeek['84'] != 0 ? $grandTotalByWeek['84'] : '',
            'Week33' => $grandTotalByWeek['85'] != 0 ? $grandTotalByWeek['85'] : '',
            'Week34' => $grandTotalByWeek['86'] != 0 ? $grandTotalByWeek['86'] : '',
            'Week35' => $grandTotalByWeek['87'] != 0 ? $grandTotalByWeek['87'] : '',
            'Aug' => collect($grandTotalByWeek)->only([83, 84, 85, 86, 87])->sum() != 0 ? collect($grandTotalByWeek)->only([83, 84, 85, 86, 87])->sum() : '',
            'Week36' => $grandTotalByWeek['88'] != 0 ? $grandTotalByWeek['88'] : '',
            'Week37' => $grandTotalByWeek['89'] != 0 ? $grandTotalByWeek['89'] : '',
            'Week38' => $grandTotalByWeek['90'] != 0 ? $grandTotalByWeek['90'] : '',

            'Week39' => $grandTotalByWeek['91'] != 0 ? $grandTotalByWeek['91'] : '',
            'Sep' => collect($grandTotalByWeek)->only([88, 89, 90, 91])->sum() != 0 ? collect($grandTotalByWeek)->only([88, 89, 90, 91])->sum() : '',
            'Week40' => $grandTotalByWeek['92'] != 0 ? $grandTotalByWeek['92'] : '',
            'Week41' => $grandTotalByWeek['93'] != 0 ? $grandTotalByWeek['93'] : '',
            'Week42' => $grandTotalByWeek['94'] != 0 ? $grandTotalByWeek['94'] : '',
            'Week43' => $grandTotalByWeek['95'] != 0 ? $grandTotalByWeek['95'] : '',
            'Oct' => collect($grandTotalByWeek)->only([92, 93, 94, 95])->sum() != 0 ? collect($grandTotalByWeek)->only([92, 93, 94, 95])->sum() : '',
            'Week44' => $grandTotalByWeek['96'] != 0 ? $grandTotalByWeek['96'] : '',
            'Week45' => $grandTotalByWeek['97'] != 0 ? $grandTotalByWeek['97'] : '',
            'Week46' => $grandTotalByWeek['98'] != 0 ? $grandTotalByWeek['98'] : '',
            'Week47' => $grandTotalByWeek['99'] != 0 ? $grandTotalByWeek['99'] : '',
            'Week48' => $grandTotalByWeek['100'] != 0 ? $grandTotalByWeek['100'] : '',
            'Nov' => collect($grandTotalByWeek)->only([96, 97, 98, 99, 100])->sum() != 0 ? collect($grandTotalByWeek)->only([96, 97, 98, 99, 100])->sum() : '',
            'Week49' => $grandTotalByWeek['101'] != 0 ? $grandTotalByWeek['101'] : '',
            'Week50' => $grandTotalByWeek['102'] != 0 ? $grandTotalByWeek['102'] : '',
            'Week51' => $grandTotalByWeek['103'] != 0 ? $grandTotalByWeek['103'] : '',
            'Week52' => $grandTotalByWeek['104'] != 0 ? $grandTotalByWeek['104'] : '',
            'Dec' => collect($grandTotalByWeek)->only([101, 102, 103, 104])->sum() != 0 ? collect($grandTotalByWeek)->only([101, 102, 103, 104])->sum() : '',
            'GrandTotalByProgram' => $grandTotalForAllPrograms,
        ];

        $response = [
            'classes' => $mappedGroupedClasses,
        ];

        return response()->json($response);
    }

    public function dashboardClassesJamaica2(Request $request)
    {

        $query = Classes::with(['site', 'program', 'dateRange', 'createdByUser', 'updatedByUser', 'cancelledByUser'])
            ->whereHas('site', function ($query) use ($request) {
                $query->where('country', '=', 'Jamaica')
                    ->where('is_active', '=', 1);
            })
            ->whereHas('program', function ($query) use ($request) {
                $query->where('is_active', '=', 1);
            })
            ->whereHas('dateRange', function ($query) use ($request) {
                $query->where('year', '=', '2024');
            });

        if ($request->has('sites_selected') && $request->sites_selected !== null) {
            $query->where('site_id', $request->sites_selected);
        }

        if ($request->has('programs_selected') && $request->programs_selected !== null) {
            $query->where('program_id', $request->programs_selected);
        }

        if ($request->has('month_selected') && $request->month_selected !== null) {
            $query->whereHas('dateRange', function ($query) use ($request) {
                $query->where('month_num', $request->month_selected);
            });
        }

        if ($request->has('week_selected') && $request->week_selected !== null) {
            $query->whereHas('dateRange', function ($query) use ($request) {
                $query->where('date_Range_id', $request->week_selected);
            });
        }

        $classes = $query->select([
            'id',
            'pushedback_id',
            'within_sla',
            'condition',
            'requested_by',
            'original_start_date',
            'changes',
            'agreed_start_date',
            'approved_date',
            'cancelled_date',
            'wfm_date_requested',
            'notice_weeks',
            'external_target',
            'internal_target',
            'notice_days',
            'pipeline_utilized',
            'total_target',
            'remarks',
            'status',
            'category',
            'type_of_hiring',
            'update_status',
            'approved_status',
            'with_erf',
            'erf_number',
            'approved_by',
            'cancelled_by',
            'ta',
            'wave_no',
            'wf',
            'tr',
            'cl',
            'op',
            'created_by',
            'site_id',
            'program_id',
            'updated_by',
            'date_range_id',
            'created_at',
            'updated_at',
        ])
            ->get();
        $formattedClasses = $classes->map(function ($class) {
            return [
                'id' => $class->id,
                'country' => $class->site->country,
                'region' => $class->site->region,
                'site_name' => $class->site->name,
                'program_name' => $class->program->name,
                'date_range' => $class->dateRange->date_range,
                'external_target' => $class->external_target,
                'internal_target' => $class->internal_target,
                'total_target' => $class->total_target,
                'within_sla' => $class->within_sla,
                'condition' => $class->condition,
                'original_start_date' => $class->original_start_date,
                'changes' => $class->changes,
                'agreed_start_date' => $class->agreed_start_date,
                'wfm_date_requested' => $class->wfm_date_requested,
                'notice_weeks' => number_format($class->notice_weeks, 2),
                'notice_days' => $class->notice_days,
                'pipeline_utilized' => $class->pipeline_utilized,
                'remarks' => $class->remarks,
                'status' => $class->status,
                'category' => $class->category,
                'type_of_hiring' => $class->type_of_hiring,
                'with_erf' => $class->with_erf,
                'erf_number' => $class->erf_number,
                'wave_no' => $class->wave_no,
                'ta' => $class->ta,
                'wf' => $class->wf,
                'tr' => $class->tr,
                'cl' => $class->cl,
                'op' => $class->op,
                'approved_by' => $class->approved_by,
                'cancelled_by' => $class->cancelledByUser ? $class->cancelledByUser->name : null,
                'requested_by' => $class->requested_by,
                'created_by' => $class->createdByUser->name,
                'updated_by' => $class->updatedByUser ? $class->updatedByUser->name : null,
                'approved_date' => $class->approved_date,
                'cancelled_date' => $class->cancelled_date,
                'created_at' => $class->created_at->format('m-d-Y H:i'),
                'updated_at' => $class->updated_at->format('m-d-Y H:i'),
            ];
        });

        return response()->json([
            'classes' => $formattedClasses,
        ]);
    }

    public function dashboardClassesJamaica3(Request $request)
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
            ->when(true, function ($query) {
                $query->whereHas('site', function ($subquery) {
                    $subquery->where('is_active', 1);
                });

                $query->whereHas('site', function ($subquery) {
                    $subquery->where('country', 'Jamaica');
                });
            })

            ->where('is_active', 1) // You can keep or remove this line based on your requirements
            ->get();

        $year = 2024;
        $dateRanges = DateRange::where('year', $year)
            ->get();

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
                    '53' => 0,
                    '54' => 0,
                    '55' => 0,
                    '56' => 0,
                    '57' => 0,
                    '58' => 0,
                    '59' => 0,
                    '60' => 0,
                    '61' => 0,
                    '62' => 0,
                    '63' => 0,
                    '64' => 0,
                    '65' => 0,
                    '66' => 0,
                    '67' => 0,
                    '68' => 0,
                    '69' => 0,
                    '70' => 0,
                    '71' => 0,
                    '72' => 0,
                    '73' => 0,
                    '74' => 0,
                    '75' => 0,
                    '76' => 0,
                    '77' => 0,
                    '78' => 0,
                    '79' => 0,
                    '80' => 0,
                    '81' => 0,
                    '82' => 0,
                    '83' => 0,
                    '84' => 0,
                    '85' => 0,
                    '86' => 0,
                    '87' => 0,
                    '88' => 0,
                    '89' => 0,
                    '90' => 0,
                    '91' => 0,
                    '92' => 0,
                    '93' => 0,
                    '94' => 0,
                    '95' => 0,
                    '96' => 0,
                    '97' => 0,
                    '98' => 0,
                    '99' => 0,
                    '100' => 0,
                    '101' => 0,
                    '102' => 0,
                    '103' => 0,
                    '104' => 0,
                ];

                foreach ($programData as $week => $weekData) {
                    $weeklyData[$week] = isset($weekData['total_target']) ? $weekData['total_target'] : 0;
                }
                $grandTotal = $grandTotalByProgram[$siteName][$programName];
                if ($grandTotal != 0) {
                    $mappedGroupedClasses[] = [
                        'Site' => $siteName,
                        'Program' => $programName,
                        'Week1' => $weeklyData['53'] != 0 ? $weeklyData['53'] : '',

                        'Week2' => $weeklyData['54'] != 0 ? $weeklyData['54'] : '',
                        'Week3' => $weeklyData['55'] != 0 ? $weeklyData['55'] : '',
                        'Week4' => $weeklyData['56'] != 0 ? $weeklyData['56'] : '',
                        'Jan' => collect($weeklyData)->only([53, 54, 55, 56])->sum() != 0 ? collect($weeklyData)->only([53, 54, 55, 56])->sum() : '',
                        'Week5' => $weeklyData['57'] != 0 ? $weeklyData['57'] : '',
                        'Week6' => $weeklyData['58'] != 0 ? $weeklyData['58'] : '',
                        'Week7' => $weeklyData['59'] != 0 ? $weeklyData['59'] : '',
                        'Week8' => $weeklyData['60'] != 0 ? $weeklyData['60'] : '',
                        'Feb' => collect($weeklyData)->only([57, 58, 59, 60])->sum() != 0 ? collect($weeklyData)->only([57, 58, 59, 60])->sum() : '',
                        'Week9' => $weeklyData['61'] != 0 ? $weeklyData['61'] : '',
                        'Week10' => $weeklyData['62'] != 0 ? $weeklyData['62'] : '',
                        'Week11' => $weeklyData['63'] != 0 ? $weeklyData['63'] : '',
                        'Week12' => $weeklyData['64'] != 0 ? $weeklyData['64'] : '',
                        'Week13' => $weeklyData['65'] != 0 ? $weeklyData['65'] : '',
                        'Mar' => collect($weeklyData)->only([61, 62, 63, 64, 65])->sum() != 0 ? collect($weeklyData)->only([61, 62, 63, 64, 65])->sum() : '',
                        'Week14' => $weeklyData['66'] != 0 ? $weeklyData['66'] : '',
                        'Week15' => $weeklyData['67'] != 0 ? $weeklyData['67'] : '',
                        'Week16' => $weeklyData['68'] != 0 ? $weeklyData['68'] : '',
                        'Week17' => $weeklyData['69'] != 0 ? $weeklyData['69'] : '',
                        'Apr' => collect($weeklyData)->only([66, 67, 68, 69])->sum() != 0 ? collect($weeklyData)->only([66, 67, 68, 69])->sum() : '',
                        'Week18' => $weeklyData['70'] != 0 ? $weeklyData['70'] : '',
                        'Week19' => $weeklyData['71'] != 0 ? $weeklyData['71'] : '',
                        'Week20' => $weeklyData['72'] != 0 ? $weeklyData['72'] : '',
                        'Week21' => $weeklyData['73'] != 0 ? $weeklyData['73'] : '',
                        'May' => collect($weeklyData)->only([70, 71, 72, 73])->sum() != 0 ? collect($weeklyData)->only([70, 71, 72, 73])->sum() : '',
                        'Week22' => $weeklyData['74'] != 0 ? $weeklyData['74'] : '',
                        'Week23' => $weeklyData['75'] != 0 ? $weeklyData['75'] : '',
                        'Week24' => $weeklyData['76'] != 0 ? $weeklyData['76'] : '',
                        'Week25' => $weeklyData['77'] != 0 ? $weeklyData['77'] : '',
                        'Week26' => $weeklyData['78'] != 0 ? $weeklyData['78'] : '',
                        'Jun' => collect($weeklyData)->only([74, 75, 76, 77, 78])->sum() != 0 ? collect($weeklyData)->only([74, 75, 76, 77, 78])->sum() : '',
                        'Week27' => $weeklyData['79'] != 0 ? $weeklyData['79'] : '',
                        'Week28' => $weeklyData['80'] != 0 ? $weeklyData['80'] : '',
                        'Week29' => $weeklyData['81'] != 0 ? $weeklyData['81'] : '',
                        'Week30' => $weeklyData['82'] != 0 ? $weeklyData['82'] : '',
                        'Jul' => collect($weeklyData)->only([79, 80, 81, 82])->sum() != 0 ? collect($weeklyData)->only([79, 80, 81, 82])->sum() : '',
                        'Week31' => $weeklyData['83'] != 0 ? $weeklyData['83'] : '',
                        'Week32' => $weeklyData['84'] != 0 ? $weeklyData['84'] : '',
                        'Week33' => $weeklyData['85'] != 0 ? $weeklyData['85'] : '',
                        'Week34' => $weeklyData['86'] != 0 ? $weeklyData['86'] : '',
                        'Week35' => $weeklyData['87'] != 0 ? $weeklyData['87'] : '',
                        'Aug' => collect($weeklyData)->only([83, 84, 85, 86, 87])->sum() != 0 ? collect($weeklyData)->only([83, 84, 85, 86, 87])->sum() : '',
                        'Week36' => $weeklyData['88'] != 0 ? $weeklyData['88'] : '',
                        'Week37' => $weeklyData['89'] != 0 ? $weeklyData['89'] : '',
                        'Week38' => $weeklyData['90'] != 0 ? $weeklyData['90'] : '',
                        'Week39' => $weeklyData['91'] != 0 ? $weeklyData['91'] : '',
                        'Sep' => collect($weeklyData)->only([88, 89, 90, 91])->sum() != 0 ? collect($weeklyData)->only([88, 89, 90, 91])->sum() : '',
                        'Week40' => $weeklyData['92'] != 0 ? $weeklyData['92'] : '',
                        'Week41' => $weeklyData['93'] != 0 ? $weeklyData['93'] : '',
                        'Week42' => $weeklyData['94'] != 0 ? $weeklyData['94'] : '',
                        'Week43' => $weeklyData['95'] != 0 ? $weeklyData['95'] : '',
                        'Oct' => collect($weeklyData)->only([92, 93, 94, 95])->sum() != 0 ? collect($weeklyData)->only([92, 93, 94, 95])->sum() : '',
                        'Week44' => $weeklyData['96'] != 0 ? $weeklyData['96'] : '',
                        'Week45' => $weeklyData['97'] != 0 ? $weeklyData['97'] : '',
                        'Week46' => $weeklyData['98'] != 0 ? $weeklyData['98'] : '',
                        'Week47' => $weeklyData['99'] != 0 ? $weeklyData['99'] : '',
                        'Week48' => $weeklyData['100'] != 0 ? $weeklyData['100'] : '',
                        'Nov' => collect($weeklyData)->only([96, 97, 98, 99, 100])->sum() != 0 ? collect($weeklyData)->only([96, 97, 98, 99, 100])->sum() : '',
                        'Week49' => $weeklyData['101'] != 0 ? $weeklyData['101'] : '',
                        'Week50' => $weeklyData['102'] != 0 ? $weeklyData['102'] : '',
                        'Week51' => $weeklyData['103'] != 0 ? $weeklyData['103'] : '',
                        'Week52' => $weeklyData['104'] != 0 ? $weeklyData['104'] : '',
                        'Dec' => collect($weeklyData)->only([101, 102, 103, 104])->sum() != 0 ? collect($weeklyData)->only([101, 102, 103, 104])->sum() : '',

                        'GrandTotalByProgram' => $grandTotal,
                    ];
                }
            }
        }

        $grandTotalForAllPrograms = array_sum(array_map('array_sum', $grandTotalByProgram));

        $mappedGroupedClasses[] = [
            'Site' => 'Grand Total',
            'Program' => '',
            'Week1' => $grandTotalByWeek['53'] != 0 ? $grandTotalByWeek['53'] : '',
            'Week2' => $grandTotalByWeek['54'] != 0 ? $grandTotalByWeek['54'] : '',
            'Week3' => $grandTotalByWeek['55'] != 0 ? $grandTotalByWeek['55'] : '',
            'Week4' => $grandTotalByWeek['56'] != 0 ? $grandTotalByWeek['56'] : '',
            'Jan' => collect($grandTotalByWeek)->only([53, 54, 55, 56])->sum() != 0 ? collect($grandTotalByWeek)->only([53, 54, 55, 56])->sum() : '',
            'Week5' => $grandTotalByWeek['57'] != 0 ? $grandTotalByWeek['57'] : '',
            'Week6' => $grandTotalByWeek['58'] != 0 ? $grandTotalByWeek['58'] : '',
            'Week7' => $grandTotalByWeek['59'] != 0 ? $grandTotalByWeek['59'] : '',
            'Week8' => $grandTotalByWeek['60'] != 0 ? $grandTotalByWeek['60'] : '',
            'Feb' => collect($grandTotalByWeek)->only([57, 58, 59, 60])->sum() != 0 ? collect($grandTotalByWeek)->only([57, 58, 59, 60])->sum() : '',
            'Week9' => $grandTotalByWeek['61'] != 0 ? $grandTotalByWeek['61'] : '',
            'Week10' => $grandTotalByWeek['62'] != 0 ? $grandTotalByWeek['62'] : '',
            'Week11' => $grandTotalByWeek['63'] != 0 ? $grandTotalByWeek['63'] : '',
            'Week12' => $grandTotalByWeek['64'] != 0 ? $grandTotalByWeek['64'] : '',
            'Week13' => $grandTotalByWeek['65'] != 0 ? $grandTotalByWeek['65'] : '',
            'Mar' => collect($grandTotalByWeek)->only([61, 62, 63, 64, 65])->sum() != 0 ? collect($grandTotalByWeek)->only([61, 62, 63, 64, 65])->sum() : '',
            'Week14' => $grandTotalByWeek['66'] != 0 ? $grandTotalByWeek['66'] : '',
            'Week15' => $grandTotalByWeek['67'] != 0 ? $grandTotalByWeek['67'] : '',
            'Week16' => $grandTotalByWeek['68'] != 0 ? $grandTotalByWeek['68'] : '',
            'Week17' => $grandTotalByWeek['69'] != 0 ? $grandTotalByWeek['69'] : '',
            'Apr' => collect($grandTotalByWeek)->only([66, 67, 68, 69])->sum() != 0 ? collect($grandTotalByWeek)->only([66, 67, 68, 69])->sum() : '',
            'Week18' => $grandTotalByWeek['70'] != 0 ? $grandTotalByWeek['70'] : '',
            'Week19' => $grandTotalByWeek['71'] != 0 ? $grandTotalByWeek['71'] : '',
            'Week20' => $grandTotalByWeek['72'] != 0 ? $grandTotalByWeek['72'] : '',
            'Week21' => $grandTotalByWeek['73'] != 0 ? $grandTotalByWeek['73'] : '',
            'May' => collect($grandTotalByWeek)->only([70, 71, 72, 73])->sum() != 0 ? collect($grandTotalByWeek)->only([70, 71, 72, 73])->sum() : '',
            'Week22' => $grandTotalByWeek['74'] != 0 ? $grandTotalByWeek['74'] : '',
            'Week23' => $grandTotalByWeek['75'] != 0 ? $grandTotalByWeek['75'] : '',
            'Week24' => $grandTotalByWeek['76'] != 0 ? $grandTotalByWeek['76'] : '',
            'Week25' => $grandTotalByWeek['77'] != 0 ? $grandTotalByWeek['77'] : '',
            'Week26' => $grandTotalByWeek['78'] != 0 ? $grandTotalByWeek['78'] : '',
            'Jun' => collect($grandTotalByWeek)->only([74, 75, 76, 77, 78])->sum() != 0 ? collect($grandTotalByWeek)->only([74, 75, 76, 77, 78])->sum() : '',
            'Week27' => $grandTotalByWeek['79'] != 0 ? $grandTotalByWeek['79'] : '',
            'Week28' => $grandTotalByWeek['80'] != 0 ? $grandTotalByWeek['80'] : '',
            'Week29' => $grandTotalByWeek['81'] != 0 ? $grandTotalByWeek['81'] : '',
            'Week30' => $grandTotalByWeek['82'] != 0 ? $grandTotalByWeek['82'] : '',
            'Jul' => collect($grandTotalByWeek)->only([79, 80, 81, 82])->sum() != 0 ? collect($grandTotalByWeek)->only([79, 80, 81, 82])->sum() : '',
            'Week31' => $grandTotalByWeek['83'] != 0 ? $grandTotalByWeek['83'] : '',
            'Week32' => $grandTotalByWeek['84'] != 0 ? $grandTotalByWeek['84'] : '',
            'Week33' => $grandTotalByWeek['85'] != 0 ? $grandTotalByWeek['85'] : '',
            'Week34' => $grandTotalByWeek['86'] != 0 ? $grandTotalByWeek['86'] : '',
            'Week35' => $grandTotalByWeek['87'] != 0 ? $grandTotalByWeek['87'] : '',
            'Aug' => collect($grandTotalByWeek)->only([83, 84, 85, 86, 87])->sum() != 0 ? collect($grandTotalByWeek)->only([83, 84, 85, 86, 87])->sum() : '',
            'Week36' => $grandTotalByWeek['88'] != 0 ? $grandTotalByWeek['88'] : '',
            'Week37' => $grandTotalByWeek['89'] != 0 ? $grandTotalByWeek['89'] : '',
            'Week38' => $grandTotalByWeek['90'] != 0 ? $grandTotalByWeek['90'] : '',

            'Week39' => $grandTotalByWeek['91'] != 0 ? $grandTotalByWeek['91'] : '',
            'Sep' => collect($grandTotalByWeek)->only([88, 89, 90, 91])->sum() != 0 ? collect($grandTotalByWeek)->only([88, 89, 90, 91])->sum() : '',
            'Week40' => $grandTotalByWeek['92'] != 0 ? $grandTotalByWeek['92'] : '',
            'Week41' => $grandTotalByWeek['93'] != 0 ? $grandTotalByWeek['93'] : '',
            'Week42' => $grandTotalByWeek['94'] != 0 ? $grandTotalByWeek['94'] : '',
            'Week43' => $grandTotalByWeek['95'] != 0 ? $grandTotalByWeek['95'] : '',
            'Oct' => collect($grandTotalByWeek)->only([92, 93, 94, 95])->sum() != 0 ? collect($grandTotalByWeek)->only([92, 93, 94, 95])->sum() : '',
            'Week44' => $grandTotalByWeek['96'] != 0 ? $grandTotalByWeek['96'] : '',
            'Week45' => $grandTotalByWeek['97'] != 0 ? $grandTotalByWeek['97'] : '',
            'Week46' => $grandTotalByWeek['98'] != 0 ? $grandTotalByWeek['98'] : '',
            'Week47' => $grandTotalByWeek['99'] != 0 ? $grandTotalByWeek['99'] : '',
            'Week48' => $grandTotalByWeek['100'] != 0 ? $grandTotalByWeek['100'] : '',
            'Nov' => collect($grandTotalByWeek)->only([96, 97, 98, 99, 100])->sum() != 0 ? collect($grandTotalByWeek)->only([96, 97, 98, 99, 100])->sum() : '',
            'Week49' => $grandTotalByWeek['101'] != 0 ? $grandTotalByWeek['101'] : '',
            'Week50' => $grandTotalByWeek['102'] != 0 ? $grandTotalByWeek['102'] : '',
            'Week51' => $grandTotalByWeek['103'] != 0 ? $grandTotalByWeek['103'] : '',
            'Week52' => $grandTotalByWeek['104'] != 0 ? $grandTotalByWeek['104'] : '',
            'Dec' => collect($grandTotalByWeek)->only([101, 102, 103, 104])->sum() != 0 ? collect($grandTotalByWeek)->only([101, 102, 103, 104])->sum() : '',
            'GrandTotalByProgram' => $grandTotalForAllPrograms,
        ];

        $response = [
            'classes' => $mappedGroupedClasses,
        ];

        return response()->json($response);
    }

    public function dashboardClassesJamaica4(Request $request)
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
            ->when(true, function ($query) {
                $query->whereHas('site', function ($subquery) {
                    $subquery->where('is_active', 1);
                });

                $query->whereHas('site', function ($subquery) {
                    $subquery->where('country', 'Jamaica');
                });
            })

            ->where('is_active', 1) // You can keep or remove this line based on your requirements
            ->get();

        $year = 2024;
        $dateRanges = DateRange::where('year', $year)
            ->get();

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
                    '53' => 0,
                    '54' => 0,
                    '55' => 0,
                    '56' => 0,
                    '57' => 0,
                    '58' => 0,
                    '59' => 0,
                    '60' => 0,
                    '61' => 0,
                    '62' => 0,
                    '63' => 0,
                    '64' => 0,
                    '65' => 0,
                    '66' => 0,
                    '67' => 0,
                    '68' => 0,
                    '69' => 0,
                    '70' => 0,
                    '71' => 0,
                    '72' => 0,
                    '73' => 0,
                    '74' => 0,
                    '75' => 0,
                    '76' => 0,
                    '77' => 0,
                    '78' => 0,
                    '79' => 0,
                    '80' => 0,
                    '81' => 0,
                    '82' => 0,
                    '83' => 0,
                    '84' => 0,
                    '85' => 0,
                    '86' => 0,
                    '87' => 0,
                    '88' => 0,
                    '89' => 0,
                    '90' => 0,
                    '91' => 0,
                    '92' => 0,
                    '93' => 0,
                    '94' => 0,
                    '95' => 0,
                    '96' => 0,
                    '97' => 0,
                    '98' => 0,
                    '99' => 0,
                    '100' => 0,
                    '101' => 0,
                    '102' => 0,
                    '103' => 0,
                    '104' => 0,
                ];

                foreach ($programData as $week => $weekData) {
                    $weeklyData[$week] = isset($weekData['total_target']) ? $weekData['total_target'] : 0;
                }
                $grandTotal = $grandTotalByProgram[$siteName][$programName];
                if ($grandTotal != 0) {
                    $mappedGroupedClasses[] = [
                        'Site' => $siteName,
                        'Program' => $programName,
                        'Week1' => $weeklyData['53'] != 0 ? $weeklyData['53'] : '',
                        'Week2' => $weeklyData['54'] != 0 ? $weeklyData['54'] : '',
                        'Week3' => $weeklyData['55'] != 0 ? $weeklyData['55'] : '',
                        'Week4' => $weeklyData['56'] != 0 ? $weeklyData['56'] : '',
                        'Jan' => collect($weeklyData)->only([53, 54, 55, 56])->sum() != 0 ? collect($weeklyData)->only([53, 54, 55, 56])->sum() : '',
                        'Week5' => $weeklyData['57'] != 0 ? $weeklyData['57'] : '',
                        'Week6' => $weeklyData['58'] != 0 ? $weeklyData['58'] : '',
                        'Week7' => $weeklyData['59'] != 0 ? $weeklyData['59'] : '',
                        'Week8' => $weeklyData['60'] != 0 ? $weeklyData['60'] : '',
                        'Feb' => collect($weeklyData)->only([57, 58, 59, 60])->sum() != 0 ? collect($weeklyData)->only([57, 58, 59, 60])->sum() : '',
                        'Week9' => $weeklyData['61'] != 0 ? $weeklyData['61'] : '',
                        'Week10' => $weeklyData['62'] != 0 ? $weeklyData['62'] : '',
                        'Week11' => $weeklyData['63'] != 0 ? $weeklyData['63'] : '',
                        'Week12' => $weeklyData['64'] != 0 ? $weeklyData['64'] : '',
                        'Week13' => $weeklyData['65'] != 0 ? $weeklyData['65'] : '',
                        'Mar' => collect($weeklyData)->only([61, 62, 63, 64, 65])->sum() != 0 ? collect($weeklyData)->only([61, 62, 63, 64, 65])->sum() : '',
                        'Week14' => $weeklyData['66'] != 0 ? $weeklyData['66'] : '',
                        'Week15' => $weeklyData['67'] != 0 ? $weeklyData['67'] : '',
                        'Week16' => $weeklyData['68'] != 0 ? $weeklyData['68'] : '',
                        'Week17' => $weeklyData['69'] != 0 ? $weeklyData['69'] : '',
                        'Apr' => collect($weeklyData)->only([66, 67, 68, 69])->sum() != 0 ? collect($weeklyData)->only([66, 67, 68, 69])->sum() : '',
                        'Week18' => $weeklyData['70'] != 0 ? $weeklyData['70'] : '',
                        'Week19' => $weeklyData['71'] != 0 ? $weeklyData['71'] : '',
                        'Week20' => $weeklyData['72'] != 0 ? $weeklyData['72'] : '',
                        'Week21' => $weeklyData['73'] != 0 ? $weeklyData['73'] : '',
                        'May' => collect($weeklyData)->only([70, 71, 72, 73])->sum() != 0 ? collect($weeklyData)->only([70, 71, 72, 73])->sum() : '',
                        'Week22' => $weeklyData['74'] != 0 ? $weeklyData['74'] : '',
                        'Week23' => $weeklyData['75'] != 0 ? $weeklyData['75'] : '',
                        'Week24' => $weeklyData['76'] != 0 ? $weeklyData['76'] : '',
                        'Week25' => $weeklyData['77'] != 0 ? $weeklyData['77'] : '',
                        'Week26' => $weeklyData['78'] != 0 ? $weeklyData['78'] : '',
                        'Jun' => collect($weeklyData)->only([74, 75, 76, 77, 78])->sum() != 0 ? collect($weeklyData)->only([74, 75, 76, 77, 78])->sum() : '',
                        'Week27' => $weeklyData['79'] != 0 ? $weeklyData['79'] : '',
                        'Week28' => $weeklyData['80'] != 0 ? $weeklyData['80'] : '',
                        'Week29' => $weeklyData['81'] != 0 ? $weeklyData['81'] : '',
                        'Week30' => $weeklyData['82'] != 0 ? $weeklyData['82'] : '',
                        'Jul' => collect($weeklyData)->only([79, 80, 81, 82])->sum() != 0 ? collect($weeklyData)->only([79, 80, 81, 82])->sum() : '',
                        'Week31' => $weeklyData['83'] != 0 ? $weeklyData['83'] : '',
                        'Week32' => $weeklyData['84'] != 0 ? $weeklyData['84'] : '',
                        'Week33' => $weeklyData['85'] != 0 ? $weeklyData['85'] : '',
                        'Week34' => $weeklyData['86'] != 0 ? $weeklyData['86'] : '',
                        'Week35' => $weeklyData['87'] != 0 ? $weeklyData['87'] : '',
                        'Aug' => collect($weeklyData)->only([83, 84, 85, 86, 87])->sum() != 0 ? collect($weeklyData)->only([83, 84, 85, 86, 87])->sum() : '',
                        'Week36' => $weeklyData['88'] != 0 ? $weeklyData['88'] : '',
                        'Week37' => $weeklyData['89'] != 0 ? $weeklyData['89'] : '',
                        'Week38' => $weeklyData['90'] != 0 ? $weeklyData['90'] : '',
                        'Week39' => $weeklyData['91'] != 0 ? $weeklyData['91'] : '',
                        'Sep' => collect($weeklyData)->only([88, 89, 90, 91])->sum() != 0 ? collect($weeklyData)->only([88, 89, 90, 91])->sum() : '',
                        'Week40' => $weeklyData['92'] != 0 ? $weeklyData['92'] : '',
                        'Week41' => $weeklyData['93'] != 0 ? $weeklyData['93'] : '',
                        'Week42' => $weeklyData['94'] != 0 ? $weeklyData['94'] : '',
                        'Week43' => $weeklyData['95'] != 0 ? $weeklyData['95'] : '',
                        'Oct' => collect($weeklyData)->only([92, 93, 94, 95])->sum() != 0 ? collect($weeklyData)->only([92, 93, 94, 95])->sum() : '',
                        'Week44' => $weeklyData['96'] != 0 ? $weeklyData['96'] : '',
                        'Week45' => $weeklyData['97'] != 0 ? $weeklyData['97'] : '',
                        'Week46' => $weeklyData['98'] != 0 ? $weeklyData['98'] : '',
                        'Week47' => $weeklyData['99'] != 0 ? $weeklyData['99'] : '',
                        'Week48' => $weeklyData['100'] != 0 ? $weeklyData['100'] : '',
                        'Nov' => collect($weeklyData)->only([96, 97, 98, 99, 100])->sum() != 0 ? collect($weeklyData)->only([96, 97, 98, 99, 100])->sum() : '',
                        'Week49' => $weeklyData['101'] != 0 ? $weeklyData['101'] : '',
                        'Week50' => $weeklyData['102'] != 0 ? $weeklyData['102'] : '',
                        'Week51' => $weeklyData['103'] != 0 ? $weeklyData['103'] : '',
                        'Week52' => $weeklyData['104'] != 0 ? $weeklyData['104'] : '',
                        'Dec' => collect($weeklyData)->only([101, 102, 103, 104])->sum() != 0 ? collect($weeklyData)->only([101, 102, 103, 104])->sum() : '',

                        'GrandTotalByProgram' => $grandTotal,
                    ];
                }
            }
        }

        $grandTotalForAllPrograms = array_sum(array_map('array_sum', $grandTotalByProgram));

        $mappedGroupedClasses[] = [
            'Site' => 'Grand Total',
            'Program' => '',
            'Week1' => $grandTotalByWeek['53'] != 0 ? $grandTotalByWeek['53'] : '',
            'Week2' => $grandTotalByWeek['54'] != 0 ? $grandTotalByWeek['54'] : '',
            'Week3' => $grandTotalByWeek['55'] != 0 ? $grandTotalByWeek['55'] : '',
            'Week4' => $grandTotalByWeek['56'] != 0 ? $grandTotalByWeek['56'] : '',
            'Jan' => collect($grandTotalByWeek)->only([53, 54, 55, 56])->sum() != 0 ? collect($grandTotalByWeek)->only([53, 54, 55, 56])->sum() : '',
            'Week5' => $grandTotalByWeek['57'] != 0 ? $grandTotalByWeek['57'] : '',
            'Week6' => $grandTotalByWeek['58'] != 0 ? $grandTotalByWeek['58'] : '',
            'Week7' => $grandTotalByWeek['59'] != 0 ? $grandTotalByWeek['59'] : '',
            'Week8' => $grandTotalByWeek['60'] != 0 ? $grandTotalByWeek['60'] : '',
            'Feb' => collect($grandTotalByWeek)->only([57, 58, 59, 60])->sum() != 0 ? collect($grandTotalByWeek)->only([57, 58, 59, 60])->sum() : '',
            'Week9' => $grandTotalByWeek['61'] != 0 ? $grandTotalByWeek['61'] : '',
            'Week10' => $grandTotalByWeek['62'] != 0 ? $grandTotalByWeek['62'] : '',
            'Week11' => $grandTotalByWeek['63'] != 0 ? $grandTotalByWeek['63'] : '',
            'Week12' => $grandTotalByWeek['64'] != 0 ? $grandTotalByWeek['64'] : '',
            'Week13' => $grandTotalByWeek['65'] != 0 ? $grandTotalByWeek['65'] : '',
            'Mar' => collect($grandTotalByWeek)->only([61, 62, 63, 64, 65])->sum() != 0 ? collect($grandTotalByWeek)->only([61, 62, 63, 64, 65])->sum() : '',
            'Week14' => $grandTotalByWeek['66'] != 0 ? $grandTotalByWeek['66'] : '',
            'Week15' => $grandTotalByWeek['67'] != 0 ? $grandTotalByWeek['67'] : '',
            'Week16' => $grandTotalByWeek['68'] != 0 ? $grandTotalByWeek['68'] : '',
            'Week17' => $grandTotalByWeek['69'] != 0 ? $grandTotalByWeek['69'] : '',
            'Apr' => collect($grandTotalByWeek)->only([66, 67, 68, 69])->sum() != 0 ? collect($grandTotalByWeek)->only([66, 67, 68, 69])->sum() : '',
            'Week18' => $grandTotalByWeek['70'] != 0 ? $grandTotalByWeek['70'] : '',
            'Week19' => $grandTotalByWeek['71'] != 0 ? $grandTotalByWeek['71'] : '',
            'Week20' => $grandTotalByWeek['72'] != 0 ? $grandTotalByWeek['72'] : '',
            'Week21' => $grandTotalByWeek['73'] != 0 ? $grandTotalByWeek['73'] : '',
            'May' => collect($grandTotalByWeek)->only([70, 71, 72, 73])->sum() != 0 ? collect($grandTotalByWeek)->only([70, 71, 72, 73])->sum() : '',
            'Week22' => $grandTotalByWeek['74'] != 0 ? $grandTotalByWeek['74'] : '',
            'Week23' => $grandTotalByWeek['75'] != 0 ? $grandTotalByWeek['75'] : '',
            'Week24' => $grandTotalByWeek['76'] != 0 ? $grandTotalByWeek['76'] : '',
            'Week25' => $grandTotalByWeek['77'] != 0 ? $grandTotalByWeek['77'] : '',
            'Week26' => $grandTotalByWeek['78'] != 0 ? $grandTotalByWeek['78'] : '',
            'Jun' => collect($grandTotalByWeek)->only([74, 75, 76, 77, 78])->sum() != 0 ? collect($grandTotalByWeek)->only([74, 75, 76, 77, 78])->sum() : '',
            'Week27' => $grandTotalByWeek['79'] != 0 ? $grandTotalByWeek['79'] : '',
            'Week28' => $grandTotalByWeek['80'] != 0 ? $grandTotalByWeek['80'] : '',
            'Week29' => $grandTotalByWeek['81'] != 0 ? $grandTotalByWeek['81'] : '',
            'Week30' => $grandTotalByWeek['82'] != 0 ? $grandTotalByWeek['82'] : '',
            'Jul' => collect($grandTotalByWeek)->only([79, 80, 81, 82])->sum() != 0 ? collect($grandTotalByWeek)->only([79, 80, 81, 82])->sum() : '',
            'Week31' => $grandTotalByWeek['83'] != 0 ? $grandTotalByWeek['83'] : '',
            'Week32' => $grandTotalByWeek['84'] != 0 ? $grandTotalByWeek['84'] : '',
            'Week33' => $grandTotalByWeek['85'] != 0 ? $grandTotalByWeek['85'] : '',
            'Week34' => $grandTotalByWeek['86'] != 0 ? $grandTotalByWeek['86'] : '',
            'Week35' => $grandTotalByWeek['87'] != 0 ? $grandTotalByWeek['87'] : '',
            'Aug' => collect($grandTotalByWeek)->only([83, 84, 85, 86, 87])->sum() != 0 ? collect($grandTotalByWeek)->only([83, 84, 85, 86, 87])->sum() : '',
            'Week36' => $grandTotalByWeek['88'] != 0 ? $grandTotalByWeek['88'] : '',
            'Week37' => $grandTotalByWeek['89'] != 0 ? $grandTotalByWeek['89'] : '',
            'Week38' => $grandTotalByWeek['90'] != 0 ? $grandTotalByWeek['90'] : '',

            'Week39' => $grandTotalByWeek['91'] != 0 ? $grandTotalByWeek['91'] : '',
            'Sep' => collect($grandTotalByWeek)->only([88, 89, 90, 91])->sum() != 0 ? collect($grandTotalByWeek)->only([88, 89, 90, 91])->sum() : '',
            'Week40' => $grandTotalByWeek['92'] != 0 ? $grandTotalByWeek['92'] : '',
            'Week41' => $grandTotalByWeek['93'] != 0 ? $grandTotalByWeek['93'] : '',
            'Week42' => $grandTotalByWeek['94'] != 0 ? $grandTotalByWeek['94'] : '',
            'Week43' => $grandTotalByWeek['95'] != 0 ? $grandTotalByWeek['95'] : '',
            'Oct' => collect($grandTotalByWeek)->only([92, 93, 94, 95])->sum() != 0 ? collect($grandTotalByWeek)->only([92, 93, 94, 95])->sum() : '',
            'Week44' => $grandTotalByWeek['96'] != 0 ? $grandTotalByWeek['96'] : '',
            'Week45' => $grandTotalByWeek['97'] != 0 ? $grandTotalByWeek['97'] : '',
            'Week46' => $grandTotalByWeek['98'] != 0 ? $grandTotalByWeek['98'] : '',
            'Week47' => $grandTotalByWeek['99'] != 0 ? $grandTotalByWeek['99'] : '',
            'Week48' => $grandTotalByWeek['100'] != 0 ? $grandTotalByWeek['100'] : '',
            'Nov' => collect($grandTotalByWeek)->only([96, 97, 98, 99, 100])->sum() != 0 ? collect($grandTotalByWeek)->only([96, 97, 98, 99, 100])->sum() : '',
            'Week49' => $grandTotalByWeek['101'] != 0 ? $grandTotalByWeek['101'] : '',
            'Week50' => $grandTotalByWeek['102'] != 0 ? $grandTotalByWeek['102'] : '',
            'Week51' => $grandTotalByWeek['103'] != 0 ? $grandTotalByWeek['103'] : '',
            'Week52' => $grandTotalByWeek['104'] != 0 ? $grandTotalByWeek['104'] : '',
            'Dec' => collect($grandTotalByWeek)->only([101, 102, 103, 104])->sum() != 0 ? collect($grandTotalByWeek)->only([101, 102, 103, 104])->sum() : '',
            'GrandTotalByProgram' => $grandTotalForAllPrograms,
        ];

        $response = [
            'classes' => $mappedGroupedClasses,
        ];

        return response()->json($response);
    }
    public function dashboardClassesGuatemala(Request $request)
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
            ->when(true, function ($query) {
                $query->whereHas('site', function ($subquery) {
                    $subquery->where('is_active', 1);
                });

                $query->whereHas('site', function ($subquery) {
                    $subquery->where('country', 'Guatemala');
                });
            })

            ->where('is_active', 1) // You can keep or remove this line based on your requirements
            ->get();

        $year = 2024;
        $dateRanges = DateRange::where('year', $year)
            ->get();

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
                    '53' => 0,
                    '54' => 0,
                    '55' => 0,
                    '56' => 0,
                    '57' => 0,
                    '58' => 0,
                    '59' => 0,
                    '60' => 0,
                    '61' => 0,
                    '62' => 0,
                    '63' => 0,
                    '64' => 0,
                    '65' => 0,
                    '66' => 0,
                    '67' => 0,
                    '68' => 0,
                    '69' => 0,
                    '70' => 0,
                    '71' => 0,
                    '72' => 0,
                    '73' => 0,
                    '74' => 0,
                    '75' => 0,
                    '76' => 0,
                    '77' => 0,
                    '78' => 0,
                    '79' => 0,
                    '80' => 0,
                    '81' => 0,
                    '82' => 0,
                    '83' => 0,
                    '84' => 0,
                    '85' => 0,
                    '86' => 0,
                    '87' => 0,
                    '88' => 0,
                    '89' => 0,
                    '90' => 0,
                    '91' => 0,
                    '92' => 0,
                    '93' => 0,
                    '94' => 0,
                    '95' => 0,
                    '96' => 0,
                    '97' => 0,
                    '98' => 0,
                    '99' => 0,
                    '100' => 0,
                    '101' => 0,
                    '102' => 0,
                    '103' => 0,
                    '104' => 0,
                ];

                foreach ($programData as $week => $weekData) {
                    $weeklyData[$week] = isset($weekData['total_target']) ? $weekData['total_target'] : 0;
                }
                $grandTotal = $grandTotalByProgram[$siteName][$programName];
                if ($grandTotal != 0) {
                    $mappedGroupedClasses[] = [
                        'Site' => $siteName,
                        'Program' => $programName,
                        'Week1' => $weeklyData['53'] != 0 ? $weeklyData['53'] : '',

                        'Week2' => $weeklyData['54'] != 0 ? $weeklyData['54'] : '',
                        'Week3' => $weeklyData['55'] != 0 ? $weeklyData['55'] : '',
                        'Week4' => $weeklyData['56'] != 0 ? $weeklyData['56'] : '',
                        'Jan' => collect($weeklyData)->only([53, 54, 55, 56])->sum() != 0 ? collect($weeklyData)->only([53, 54, 55, 56])->sum() : '',
                        'Week5' => $weeklyData['57'] != 0 ? $weeklyData['57'] : '',
                        'Week6' => $weeklyData['58'] != 0 ? $weeklyData['58'] : '',
                        'Week7' => $weeklyData['59'] != 0 ? $weeklyData['59'] : '',
                        'Week8' => $weeklyData['60'] != 0 ? $weeklyData['60'] : '',
                        'Feb' => collect($weeklyData)->only([57, 58, 59, 60])->sum() != 0 ? collect($weeklyData)->only([57, 58, 59, 60])->sum() : '',
                        'Week9' => $weeklyData['61'] != 0 ? $weeklyData['61'] : '',
                        'Week10' => $weeklyData['62'] != 0 ? $weeklyData['62'] : '',
                        'Week11' => $weeklyData['63'] != 0 ? $weeklyData['63'] : '',
                        'Week12' => $weeklyData['64'] != 0 ? $weeklyData['64'] : '',
                        'Week13' => $weeklyData['65'] != 0 ? $weeklyData['65'] : '',
                        'Mar' => collect($weeklyData)->only([61, 62, 63, 64, 65])->sum() != 0 ? collect($weeklyData)->only([61, 62, 63, 64, 65])->sum() : '',
                        'Week14' => $weeklyData['66'] != 0 ? $weeklyData['66'] : '',
                        'Week15' => $weeklyData['67'] != 0 ? $weeklyData['67'] : '',
                        'Week16' => $weeklyData['68'] != 0 ? $weeklyData['68'] : '',
                        'Week17' => $weeklyData['69'] != 0 ? $weeklyData['69'] : '',
                        'Apr' => collect($weeklyData)->only([66, 67, 68, 69])->sum() != 0 ? collect($weeklyData)->only([66, 67, 68, 69])->sum() : '',
                        'Week18' => $weeklyData['70'] != 0 ? $weeklyData['70'] : '',
                        'Week19' => $weeklyData['71'] != 0 ? $weeklyData['71'] : '',
                        'Week20' => $weeklyData['72'] != 0 ? $weeklyData['72'] : '',
                        'Week21' => $weeklyData['73'] != 0 ? $weeklyData['73'] : '',
                        'May' => collect($weeklyData)->only([70, 71, 72, 73])->sum() != 0 ? collect($weeklyData)->only([70, 71, 72, 73])->sum() : '',
                        'Week22' => $weeklyData['74'] != 0 ? $weeklyData['74'] : '',
                        'Week23' => $weeklyData['75'] != 0 ? $weeklyData['75'] : '',
                        'Week24' => $weeklyData['76'] != 0 ? $weeklyData['76'] : '',
                        'Week25' => $weeklyData['77'] != 0 ? $weeklyData['77'] : '',
                        'Week26' => $weeklyData['78'] != 0 ? $weeklyData['78'] : '',
                        'Jun' => collect($weeklyData)->only([74, 75, 76, 77, 78])->sum() != 0 ? collect($weeklyData)->only([74, 75, 76, 77, 78])->sum() : '',
                        'Week27' => $weeklyData['79'] != 0 ? $weeklyData['79'] : '',
                        'Week28' => $weeklyData['80'] != 0 ? $weeklyData['80'] : '',
                        'Week29' => $weeklyData['81'] != 0 ? $weeklyData['81'] : '',
                        'Week30' => $weeklyData['82'] != 0 ? $weeklyData['82'] : '',
                        'Jul' => collect($weeklyData)->only([79, 80, 81, 82])->sum() != 0 ? collect($weeklyData)->only([79, 80, 81, 82])->sum() : '',
                        'Week31' => $weeklyData['83'] != 0 ? $weeklyData['83'] : '',
                        'Week32' => $weeklyData['84'] != 0 ? $weeklyData['84'] : '',
                        'Week33' => $weeklyData['85'] != 0 ? $weeklyData['85'] : '',
                        'Week34' => $weeklyData['86'] != 0 ? $weeklyData['86'] : '',
                        'Week35' => $weeklyData['87'] != 0 ? $weeklyData['87'] : '',
                        'Aug' => collect($weeklyData)->only([83, 84, 85, 86, 87])->sum() != 0 ? collect($weeklyData)->only([83, 84, 85, 86, 87])->sum() : '',
                        'Week36' => $weeklyData['88'] != 0 ? $weeklyData['88'] : '',
                        'Week37' => $weeklyData['89'] != 0 ? $weeklyData['89'] : '',
                        'Week38' => $weeklyData['90'] != 0 ? $weeklyData['90'] : '',
                        'Week39' => $weeklyData['91'] != 0 ? $weeklyData['91'] : '',
                        'Sep' => collect($weeklyData)->only([88, 89, 90, 91])->sum() != 0 ? collect($weeklyData)->only([88, 89, 90, 91])->sum() : '',
                        'Week40' => $weeklyData['92'] != 0 ? $weeklyData['92'] : '',
                        'Week41' => $weeklyData['93'] != 0 ? $weeklyData['93'] : '',
                        'Week42' => $weeklyData['94'] != 0 ? $weeklyData['94'] : '',
                        'Week43' => $weeklyData['95'] != 0 ? $weeklyData['95'] : '',
                        'Oct' => collect($weeklyData)->only([92, 93, 94, 95])->sum() != 0 ? collect($weeklyData)->only([92, 93, 94, 95])->sum() : '',
                        'Week44' => $weeklyData['96'] != 0 ? $weeklyData['96'] : '',
                        'Week45' => $weeklyData['97'] != 0 ? $weeklyData['97'] : '',
                        'Week46' => $weeklyData['98'] != 0 ? $weeklyData['98'] : '',
                        'Week47' => $weeklyData['99'] != 0 ? $weeklyData['99'] : '',
                        'Week48' => $weeklyData['100'] != 0 ? $weeklyData['100'] : '',
                        'Nov' => collect($weeklyData)->only([96, 97, 98, 99, 100])->sum() != 0 ? collect($weeklyData)->only([96, 97, 98, 99, 100])->sum() : '',
                        'Week49' => $weeklyData['101'] != 0 ? $weeklyData['101'] : '',
                        'Week50' => $weeklyData['102'] != 0 ? $weeklyData['102'] : '',
                        'Week51' => $weeklyData['103'] != 0 ? $weeklyData['103'] : '',
                        'Week52' => $weeklyData['104'] != 0 ? $weeklyData['104'] : '',
                        'Dec' => collect($weeklyData)->only([101, 102, 103, 104])->sum() != 0 ? collect($weeklyData)->only([101, 102, 103, 104])->sum() : '',

                        'GrandTotalByProgram' => $grandTotal,
                    ];
                }
            }
        }

        $grandTotalForAllPrograms = array_sum(array_map('array_sum', $grandTotalByProgram));

        $mappedGroupedClasses[] = [
            'Site' => 'Grand Total',
            'Program' => '',
            'Week1' => $grandTotalByWeek['53'] != 0 ? $grandTotalByWeek['53'] : '',
            'Week2' => $grandTotalByWeek['54'] != 0 ? $grandTotalByWeek['54'] : '',
            'Week3' => $grandTotalByWeek['55'] != 0 ? $grandTotalByWeek['55'] : '',
            'Week4' => $grandTotalByWeek['56'] != 0 ? $grandTotalByWeek['56'] : '',
            'Jan' => collect($grandTotalByWeek)->only([53, 54, 55, 56])->sum() != 0 ? collect($grandTotalByWeek)->only([53, 54, 55, 56])->sum() : '',
            'Week5' => $grandTotalByWeek['57'] != 0 ? $grandTotalByWeek['57'] : '',
            'Week6' => $grandTotalByWeek['58'] != 0 ? $grandTotalByWeek['58'] : '',
            'Week7' => $grandTotalByWeek['59'] != 0 ? $grandTotalByWeek['59'] : '',
            'Week8' => $grandTotalByWeek['60'] != 0 ? $grandTotalByWeek['60'] : '',
            'Feb' => collect($grandTotalByWeek)->only([57, 58, 59, 60])->sum() != 0 ? collect($grandTotalByWeek)->only([57, 58, 59, 60])->sum() : '',
            'Week9' => $grandTotalByWeek['61'] != 0 ? $grandTotalByWeek['61'] : '',
            'Week10' => $grandTotalByWeek['62'] != 0 ? $grandTotalByWeek['62'] : '',
            'Week11' => $grandTotalByWeek['63'] != 0 ? $grandTotalByWeek['63'] : '',
            'Week12' => $grandTotalByWeek['64'] != 0 ? $grandTotalByWeek['64'] : '',
            'Week13' => $grandTotalByWeek['65'] != 0 ? $grandTotalByWeek['65'] : '',
            'Mar' => collect($grandTotalByWeek)->only([61, 62, 63, 64, 65])->sum() != 0 ? collect($grandTotalByWeek)->only([61, 62, 63, 64, 65])->sum() : '',
            'Week14' => $grandTotalByWeek['66'] != 0 ? $grandTotalByWeek['66'] : '',
            'Week15' => $grandTotalByWeek['67'] != 0 ? $grandTotalByWeek['67'] : '',
            'Week16' => $grandTotalByWeek['68'] != 0 ? $grandTotalByWeek['68'] : '',
            'Week17' => $grandTotalByWeek['69'] != 0 ? $grandTotalByWeek['69'] : '',
            'Apr' => collect($grandTotalByWeek)->only([66, 67, 68, 69])->sum() != 0 ? collect($grandTotalByWeek)->only([66, 67, 68, 69])->sum() : '',
            'Week18' => $grandTotalByWeek['70'] != 0 ? $grandTotalByWeek['70'] : '',
            'Week19' => $grandTotalByWeek['71'] != 0 ? $grandTotalByWeek['71'] : '',
            'Week20' => $grandTotalByWeek['72'] != 0 ? $grandTotalByWeek['72'] : '',
            'Week21' => $grandTotalByWeek['73'] != 0 ? $grandTotalByWeek['73'] : '',
            'May' => collect($grandTotalByWeek)->only([70, 71, 72, 73])->sum() != 0 ? collect($grandTotalByWeek)->only([70, 71, 72, 73])->sum() : '',
            'Week22' => $grandTotalByWeek['74'] != 0 ? $grandTotalByWeek['74'] : '',
            'Week23' => $grandTotalByWeek['75'] != 0 ? $grandTotalByWeek['75'] : '',
            'Week24' => $grandTotalByWeek['76'] != 0 ? $grandTotalByWeek['76'] : '',
            'Week25' => $grandTotalByWeek['77'] != 0 ? $grandTotalByWeek['77'] : '',
            'Week26' => $grandTotalByWeek['78'] != 0 ? $grandTotalByWeek['78'] : '',
            'Jun' => collect($grandTotalByWeek)->only([74, 75, 76, 77, 78])->sum() != 0 ? collect($grandTotalByWeek)->only([74, 75, 76, 77, 78])->sum() : '',
            'Week27' => $grandTotalByWeek['79'] != 0 ? $grandTotalByWeek['79'] : '',
            'Week28' => $grandTotalByWeek['80'] != 0 ? $grandTotalByWeek['80'] : '',
            'Week29' => $grandTotalByWeek['81'] != 0 ? $grandTotalByWeek['81'] : '',
            'Week30' => $grandTotalByWeek['82'] != 0 ? $grandTotalByWeek['82'] : '',
            'Jul' => collect($grandTotalByWeek)->only([79, 80, 81, 82])->sum() != 0 ? collect($grandTotalByWeek)->only([79, 80, 81, 82])->sum() : '',
            'Week31' => $grandTotalByWeek['83'] != 0 ? $grandTotalByWeek['83'] : '',
            'Week32' => $grandTotalByWeek['84'] != 0 ? $grandTotalByWeek['84'] : '',
            'Week33' => $grandTotalByWeek['85'] != 0 ? $grandTotalByWeek['85'] : '',
            'Week34' => $grandTotalByWeek['86'] != 0 ? $grandTotalByWeek['86'] : '',
            'Week35' => $grandTotalByWeek['87'] != 0 ? $grandTotalByWeek['87'] : '',
            'Aug' => collect($grandTotalByWeek)->only([83, 84, 85, 86, 87])->sum() != 0 ? collect($grandTotalByWeek)->only([83, 84, 85, 86, 87])->sum() : '',
            'Week36' => $grandTotalByWeek['88'] != 0 ? $grandTotalByWeek['88'] : '',
            'Week37' => $grandTotalByWeek['89'] != 0 ? $grandTotalByWeek['89'] : '',
            'Week38' => $grandTotalByWeek['90'] != 0 ? $grandTotalByWeek['90'] : '',

            'Week39' => $grandTotalByWeek['91'] != 0 ? $grandTotalByWeek['91'] : '',
            'Sep' => collect($grandTotalByWeek)->only([88, 89, 90, 91])->sum() != 0 ? collect($grandTotalByWeek)->only([88, 89, 90, 91])->sum() : '',
            'Week40' => $grandTotalByWeek['92'] != 0 ? $grandTotalByWeek['92'] : '',
            'Week41' => $grandTotalByWeek['93'] != 0 ? $grandTotalByWeek['93'] : '',
            'Week42' => $grandTotalByWeek['94'] != 0 ? $grandTotalByWeek['94'] : '',
            'Week43' => $grandTotalByWeek['95'] != 0 ? $grandTotalByWeek['95'] : '',
            'Oct' => collect($grandTotalByWeek)->only([92, 93, 94, 95])->sum() != 0 ? collect($grandTotalByWeek)->only([92, 93, 94, 95])->sum() : '',
            'Week44' => $grandTotalByWeek['96'] != 0 ? $grandTotalByWeek['96'] : '',
            'Week45' => $grandTotalByWeek['97'] != 0 ? $grandTotalByWeek['97'] : '',
            'Week46' => $grandTotalByWeek['98'] != 0 ? $grandTotalByWeek['98'] : '',
            'Week47' => $grandTotalByWeek['99'] != 0 ? $grandTotalByWeek['99'] : '',
            'Week48' => $grandTotalByWeek['100'] != 0 ? $grandTotalByWeek['100'] : '',
            'Nov' => collect($grandTotalByWeek)->only([96, 97, 98, 99, 100])->sum() != 0 ? collect($grandTotalByWeek)->only([96, 97, 98, 99, 100])->sum() : '',
            'Week49' => $grandTotalByWeek['101'] != 0 ? $grandTotalByWeek['101'] : '',
            'Week50' => $grandTotalByWeek['102'] != 0 ? $grandTotalByWeek['102'] : '',
            'Week51' => $grandTotalByWeek['103'] != 0 ? $grandTotalByWeek['103'] : '',
            'Week52' => $grandTotalByWeek['104'] != 0 ? $grandTotalByWeek['104'] : '',
            'Dec' => collect($grandTotalByWeek)->only([101, 102, 103, 104])->sum() != 0 ? collect($grandTotalByWeek)->only([101, 102, 103, 104])->sum() : '',
            'GrandTotalByProgram' => $grandTotalForAllPrograms,
        ];

        $response = [
            'classes' => $mappedGroupedClasses,
        ];

        return response()->json($response);
    }

    public function dashboardClassesGuatemala2(Request $request)
    {

        $query = Classes::with(['site', 'program', 'dateRange', 'createdByUser', 'updatedByUser', 'cancelledByUser'])
            ->whereHas('site', function ($query) use ($request) {
                $query->where('country', '=', 'Guatemala')
                    ->where('is_active', '=', 1);
            })
            ->whereHas('program', function ($query) use ($request) {
                $query->where('is_active', '=', 1);
            })
            ->whereHas('dateRange', function ($query) use ($request) {
                $query->where('year', '=', '2024');
            });

        if ($request->has('sites_selected') && $request->sites_selected !== null) {
            $query->where('site_id', $request->sites_selected);
        }

        if ($request->has('programs_selected') && $request->programs_selected !== null) {
            $query->where('program_id', $request->programs_selected);
        }

        if ($request->has('month_selected') && $request->month_selected !== null) {
            $query->whereHas('dateRange', function ($query) use ($request) {
                $query->where('month_num', $request->month_selected);
            });
        }

        if ($request->has('week_selected') && $request->week_selected !== null) {
            $query->whereHas('dateRange', function ($query) use ($request) {
                $query->where('date_Range_id', $request->week_selected);
            });
        }

        $classes = $query->select([
            'id',
            'pushedback_id',
            'within_sla',
            'condition',
            'requested_by',
            'original_start_date',
            'changes',
            'agreed_start_date',
            'approved_date',
            'cancelled_date',
            'wfm_date_requested',
            'notice_weeks',
            'external_target',
            'internal_target',
            'notice_days',
            'pipeline_utilized',
            'total_target',
            'remarks',
            'status',
            'category',
            'type_of_hiring',
            'update_status',
            'approved_status',
            'with_erf',
            'erf_number',
            'approved_by',
            'cancelled_by',
            'ta',
            'wave_no',
            'wf',
            'tr',
            'cl',
            'op',
            'created_by',
            'site_id',
            'program_id',
            'updated_by',
            'date_range_id',
            'created_at',
            'updated_at',
        ])
            ->get();
        $formattedClasses = $classes->map(function ($class) {
            return [
                'id' => $class->id,
                'country' => $class->site->country,
                'region' => $class->site->region,
                'site_name' => $class->site->name,
                'program_name' => $class->program->name,
                'date_range' => $class->dateRange->date_range,
                'external_target' => $class->external_target,
                'internal_target' => $class->internal_target,
                'total_target' => $class->total_target,
                'within_sla' => $class->within_sla,
                'condition' => $class->condition,
                'original_start_date' => $class->original_start_date,
                'changes' => $class->changes,
                'agreed_start_date' => $class->agreed_start_date,
                'wfm_date_requested' => $class->wfm_date_requested,
                'notice_weeks' => number_format($class->notice_weeks, 2),
                'notice_days' => $class->notice_days,
                'pipeline_utilized' => $class->pipeline_utilized,
                'remarks' => $class->remarks,
                'status' => $class->status,
                'category' => $class->category,
                'type_of_hiring' => $class->type_of_hiring,
                'with_erf' => $class->with_erf,
                'erf_number' => $class->erf_number,
                'wave_no' => $class->wave_no,
                'ta' => $class->ta,
                'wf' => $class->wf,
                'tr' => $class->tr,
                'cl' => $class->cl,
                'op' => $class->op,
                'approved_by' => $class->approved_by,
                'cancelled_by' => $class->cancelledByUser ? $class->cancelledByUser->name : null,
                'requested_by' => $class->requested_by,
                'created_by' => $class->createdByUser->name,
                'updated_by' => $class->updatedByUser ? $class->updatedByUser->name : null,
                'approved_date' => $class->approved_date,
                'cancelled_date' => $class->cancelled_date,
                'created_at' => $class->created_at->format('m-d-Y H:i'),
                'updated_at' => $class->updated_at->format('m-d-Y H:i'),
            ];
        });

        return response()->json([
            'classes' => $formattedClasses,
        ]);
    }

    public function dashboardClassesGuatemala3(Request $request)
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
            ->when(true, function ($query) {
                $query->whereHas('site', function ($subquery) {
                    $subquery->where('is_active', 1);
                });

                $query->whereHas('site', function ($subquery) {
                    $subquery->where('country', 'Guatemala');
                });
            })

            ->where('is_active', 1) // You can keep or remove this line based on your requirements
            ->get();

        $year = 2024;
        $dateRanges = DateRange::where('year', $year)
            ->get();

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
                    '53' => 0,
                    '54' => 0,
                    '55' => 0,
                    '56' => 0,
                    '57' => 0,
                    '58' => 0,
                    '59' => 0,
                    '60' => 0,
                    '61' => 0,
                    '62' => 0,
                    '63' => 0,
                    '64' => 0,
                    '65' => 0,
                    '66' => 0,
                    '67' => 0,
                    '68' => 0,
                    '69' => 0,
                    '70' => 0,
                    '71' => 0,
                    '72' => 0,
                    '73' => 0,
                    '74' => 0,
                    '75' => 0,
                    '76' => 0,
                    '77' => 0,
                    '78' => 0,
                    '79' => 0,
                    '80' => 0,
                    '81' => 0,
                    '82' => 0,
                    '83' => 0,
                    '84' => 0,
                    '85' => 0,
                    '86' => 0,
                    '87' => 0,
                    '88' => 0,
                    '89' => 0,
                    '90' => 0,
                    '91' => 0,
                    '92' => 0,
                    '93' => 0,
                    '94' => 0,
                    '95' => 0,
                    '96' => 0,
                    '97' => 0,
                    '98' => 0,
                    '99' => 0,
                    '100' => 0,
                    '101' => 0,
                    '102' => 0,
                    '103' => 0,
                    '104' => 0,
                ];

                foreach ($programData as $week => $weekData) {
                    $weeklyData[$week] = isset($weekData['total_target']) ? $weekData['total_target'] : 0;
                }
                $grandTotal = $grandTotalByProgram[$siteName][$programName];
                if ($grandTotal != 0) {
                    $mappedGroupedClasses[] = [
                        'Site' => $siteName,
                        'Program' => $programName,
                        'Week1' => $weeklyData['53'] != 0 ? $weeklyData['53'] : '',

                        'Week2' => $weeklyData['54'] != 0 ? $weeklyData['54'] : '',
                        'Week3' => $weeklyData['55'] != 0 ? $weeklyData['55'] : '',
                        'Week4' => $weeklyData['56'] != 0 ? $weeklyData['56'] : '',
                        'Jan' => collect($weeklyData)->only([53, 54, 55, 56])->sum() != 0 ? collect($weeklyData)->only([53, 54, 55, 56])->sum() : '',
                        'Week5' => $weeklyData['57'] != 0 ? $weeklyData['57'] : '',
                        'Week6' => $weeklyData['58'] != 0 ? $weeklyData['58'] : '',
                        'Week7' => $weeklyData['59'] != 0 ? $weeklyData['59'] : '',
                        'Week8' => $weeklyData['60'] != 0 ? $weeklyData['60'] : '',
                        'Feb' => collect($weeklyData)->only([57, 58, 59, 60])->sum() != 0 ? collect($weeklyData)->only([57, 58, 59, 60])->sum() : '',
                        'Week9' => $weeklyData['61'] != 0 ? $weeklyData['61'] : '',
                        'Week10' => $weeklyData['62'] != 0 ? $weeklyData['62'] : '',
                        'Week11' => $weeklyData['63'] != 0 ? $weeklyData['63'] : '',
                        'Week12' => $weeklyData['64'] != 0 ? $weeklyData['64'] : '',
                        'Week13' => $weeklyData['65'] != 0 ? $weeklyData['65'] : '',
                        'Mar' => collect($weeklyData)->only([61, 62, 63, 64, 65])->sum() != 0 ? collect($weeklyData)->only([61, 62, 63, 64, 65])->sum() : '',
                        'Week14' => $weeklyData['66'] != 0 ? $weeklyData['66'] : '',
                        'Week15' => $weeklyData['67'] != 0 ? $weeklyData['67'] : '',
                        'Week16' => $weeklyData['68'] != 0 ? $weeklyData['68'] : '',
                        'Week17' => $weeklyData['69'] != 0 ? $weeklyData['69'] : '',
                        'Apr' => collect($weeklyData)->only([66, 67, 68, 69])->sum() != 0 ? collect($weeklyData)->only([66, 67, 68, 69])->sum() : '',
                        'Week18' => $weeklyData['70'] != 0 ? $weeklyData['70'] : '',
                        'Week19' => $weeklyData['71'] != 0 ? $weeklyData['71'] : '',
                        'Week20' => $weeklyData['72'] != 0 ? $weeklyData['72'] : '',
                        'Week21' => $weeklyData['73'] != 0 ? $weeklyData['73'] : '',
                        'May' => collect($weeklyData)->only([70, 71, 72, 73])->sum() != 0 ? collect($weeklyData)->only([70, 71, 72, 73])->sum() : '',
                        'Week22' => $weeklyData['74'] != 0 ? $weeklyData['74'] : '',
                        'Week23' => $weeklyData['75'] != 0 ? $weeklyData['75'] : '',
                        'Week24' => $weeklyData['76'] != 0 ? $weeklyData['76'] : '',
                        'Week25' => $weeklyData['77'] != 0 ? $weeklyData['77'] : '',
                        'Week26' => $weeklyData['78'] != 0 ? $weeklyData['78'] : '',
                        'Jun' => collect($weeklyData)->only([74, 75, 76, 77, 78])->sum() != 0 ? collect($weeklyData)->only([74, 75, 76, 77, 78])->sum() : '',
                        'Week27' => $weeklyData['79'] != 0 ? $weeklyData['79'] : '',
                        'Week28' => $weeklyData['80'] != 0 ? $weeklyData['80'] : '',
                        'Week29' => $weeklyData['81'] != 0 ? $weeklyData['81'] : '',
                        'Week30' => $weeklyData['82'] != 0 ? $weeklyData['82'] : '',
                        'Jul' => collect($weeklyData)->only([79, 80, 81, 82])->sum() != 0 ? collect($weeklyData)->only([79, 80, 81, 82])->sum() : '',
                        'Week31' => $weeklyData['83'] != 0 ? $weeklyData['83'] : '',
                        'Week32' => $weeklyData['84'] != 0 ? $weeklyData['84'] : '',
                        'Week33' => $weeklyData['85'] != 0 ? $weeklyData['85'] : '',
                        'Week34' => $weeklyData['86'] != 0 ? $weeklyData['86'] : '',
                        'Week35' => $weeklyData['87'] != 0 ? $weeklyData['87'] : '',
                        'Aug' => collect($weeklyData)->only([83, 84, 85, 86, 87])->sum() != 0 ? collect($weeklyData)->only([83, 84, 85, 86, 87])->sum() : '',
                        'Week36' => $weeklyData['88'] != 0 ? $weeklyData['88'] : '',
                        'Week37' => $weeklyData['89'] != 0 ? $weeklyData['89'] : '',
                        'Week38' => $weeklyData['90'] != 0 ? $weeklyData['90'] : '',
                        'Week39' => $weeklyData['91'] != 0 ? $weeklyData['91'] : '',
                        'Sep' => collect($weeklyData)->only([88, 89, 90, 91])->sum() != 0 ? collect($weeklyData)->only([88, 89, 90, 91])->sum() : '',
                        'Week40' => $weeklyData['92'] != 0 ? $weeklyData['92'] : '',
                        'Week41' => $weeklyData['93'] != 0 ? $weeklyData['93'] : '',
                        'Week42' => $weeklyData['94'] != 0 ? $weeklyData['94'] : '',
                        'Week43' => $weeklyData['95'] != 0 ? $weeklyData['95'] : '',
                        'Oct' => collect($weeklyData)->only([92, 93, 94, 95])->sum() != 0 ? collect($weeklyData)->only([92, 93, 94, 95])->sum() : '',
                        'Week44' => $weeklyData['96'] != 0 ? $weeklyData['96'] : '',
                        'Week45' => $weeklyData['97'] != 0 ? $weeklyData['97'] : '',
                        'Week46' => $weeklyData['98'] != 0 ? $weeklyData['98'] : '',
                        'Week47' => $weeklyData['99'] != 0 ? $weeklyData['99'] : '',
                        'Week48' => $weeklyData['100'] != 0 ? $weeklyData['100'] : '',
                        'Nov' => collect($weeklyData)->only([96, 97, 98, 99, 100])->sum() != 0 ? collect($weeklyData)->only([96, 97, 98, 99, 100])->sum() : '',
                        'Week49' => $weeklyData['101'] != 0 ? $weeklyData['101'] : '',
                        'Week50' => $weeklyData['102'] != 0 ? $weeklyData['102'] : '',
                        'Week51' => $weeklyData['103'] != 0 ? $weeklyData['103'] : '',
                        'Week52' => $weeklyData['104'] != 0 ? $weeklyData['104'] : '',
                        'Dec' => collect($weeklyData)->only([101, 102, 103, 104])->sum() != 0 ? collect($weeklyData)->only([101, 102, 103, 104])->sum() : '',

                        'GrandTotalByProgram' => $grandTotal,
                    ];
                }
            }
        }

        $grandTotalForAllPrograms = array_sum(array_map('array_sum', $grandTotalByProgram));

        $mappedGroupedClasses[] = [
            'Site' => 'Grand Total',
            'Program' => '',
            'Week1' => $grandTotalByWeek['53'] != 0 ? $grandTotalByWeek['53'] : '',
            'Week2' => $grandTotalByWeek['54'] != 0 ? $grandTotalByWeek['54'] : '',
            'Week3' => $grandTotalByWeek['55'] != 0 ? $grandTotalByWeek['55'] : '',
            'Week4' => $grandTotalByWeek['56'] != 0 ? $grandTotalByWeek['56'] : '',
            'Jan' => collect($grandTotalByWeek)->only([53, 54, 55, 56])->sum() != 0 ? collect($grandTotalByWeek)->only([53, 54, 55, 56])->sum() : '',
            'Week5' => $grandTotalByWeek['57'] != 0 ? $grandTotalByWeek['57'] : '',
            'Week6' => $grandTotalByWeek['58'] != 0 ? $grandTotalByWeek['58'] : '',
            'Week7' => $grandTotalByWeek['59'] != 0 ? $grandTotalByWeek['59'] : '',
            'Week8' => $grandTotalByWeek['60'] != 0 ? $grandTotalByWeek['60'] : '',
            'Feb' => collect($grandTotalByWeek)->only([57, 58, 59, 60])->sum() != 0 ? collect($grandTotalByWeek)->only([57, 58, 59, 60])->sum() : '',
            'Week9' => $grandTotalByWeek['61'] != 0 ? $grandTotalByWeek['61'] : '',
            'Week10' => $grandTotalByWeek['62'] != 0 ? $grandTotalByWeek['62'] : '',
            'Week11' => $grandTotalByWeek['63'] != 0 ? $grandTotalByWeek['63'] : '',
            'Week12' => $grandTotalByWeek['64'] != 0 ? $grandTotalByWeek['64'] : '',
            'Week13' => $grandTotalByWeek['65'] != 0 ? $grandTotalByWeek['65'] : '',
            'Mar' => collect($grandTotalByWeek)->only([61, 62, 63, 64, 65])->sum() != 0 ? collect($grandTotalByWeek)->only([61, 62, 63, 64, 65])->sum() : '',
            'Week14' => $grandTotalByWeek['66'] != 0 ? $grandTotalByWeek['66'] : '',
            'Week15' => $grandTotalByWeek['67'] != 0 ? $grandTotalByWeek['67'] : '',
            'Week16' => $grandTotalByWeek['68'] != 0 ? $grandTotalByWeek['68'] : '',
            'Week17' => $grandTotalByWeek['69'] != 0 ? $grandTotalByWeek['69'] : '',
            'Apr' => collect($grandTotalByWeek)->only([66, 67, 68, 69])->sum() != 0 ? collect($grandTotalByWeek)->only([66, 67, 68, 69])->sum() : '',
            'Week18' => $grandTotalByWeek['70'] != 0 ? $grandTotalByWeek['70'] : '',
            'Week19' => $grandTotalByWeek['71'] != 0 ? $grandTotalByWeek['71'] : '',
            'Week20' => $grandTotalByWeek['72'] != 0 ? $grandTotalByWeek['72'] : '',
            'Week21' => $grandTotalByWeek['73'] != 0 ? $grandTotalByWeek['73'] : '',
            'May' => collect($grandTotalByWeek)->only([70, 71, 72, 73])->sum() != 0 ? collect($grandTotalByWeek)->only([70, 71, 72, 73])->sum() : '',
            'Week22' => $grandTotalByWeek['74'] != 0 ? $grandTotalByWeek['74'] : '',
            'Week23' => $grandTotalByWeek['75'] != 0 ? $grandTotalByWeek['75'] : '',
            'Week24' => $grandTotalByWeek['76'] != 0 ? $grandTotalByWeek['76'] : '',
            'Week25' => $grandTotalByWeek['77'] != 0 ? $grandTotalByWeek['77'] : '',
            'Week26' => $grandTotalByWeek['78'] != 0 ? $grandTotalByWeek['78'] : '',
            'Jun' => collect($grandTotalByWeek)->only([74, 75, 76, 77, 78])->sum() != 0 ? collect($grandTotalByWeek)->only([74, 75, 76, 77, 78])->sum() : '',
            'Week27' => $grandTotalByWeek['79'] != 0 ? $grandTotalByWeek['79'] : '',
            'Week28' => $grandTotalByWeek['80'] != 0 ? $grandTotalByWeek['80'] : '',
            'Week29' => $grandTotalByWeek['81'] != 0 ? $grandTotalByWeek['81'] : '',
            'Week30' => $grandTotalByWeek['82'] != 0 ? $grandTotalByWeek['82'] : '',
            'Jul' => collect($grandTotalByWeek)->only([79, 80, 81, 82])->sum() != 0 ? collect($grandTotalByWeek)->only([79, 80, 81, 82])->sum() : '',
            'Week31' => $grandTotalByWeek['83'] != 0 ? $grandTotalByWeek['83'] : '',
            'Week32' => $grandTotalByWeek['84'] != 0 ? $grandTotalByWeek['84'] : '',
            'Week33' => $grandTotalByWeek['85'] != 0 ? $grandTotalByWeek['85'] : '',
            'Week34' => $grandTotalByWeek['86'] != 0 ? $grandTotalByWeek['86'] : '',
            'Week35' => $grandTotalByWeek['87'] != 0 ? $grandTotalByWeek['87'] : '',
            'Aug' => collect($grandTotalByWeek)->only([83, 84, 85, 86, 87])->sum() != 0 ? collect($grandTotalByWeek)->only([83, 84, 85, 86, 87])->sum() : '',
            'Week36' => $grandTotalByWeek['88'] != 0 ? $grandTotalByWeek['88'] : '',
            'Week37' => $grandTotalByWeek['89'] != 0 ? $grandTotalByWeek['89'] : '',
            'Week38' => $grandTotalByWeek['90'] != 0 ? $grandTotalByWeek['90'] : '',

            'Week39' => $grandTotalByWeek['91'] != 0 ? $grandTotalByWeek['91'] : '',
            'Sep' => collect($grandTotalByWeek)->only([88, 89, 90, 91])->sum() != 0 ? collect($grandTotalByWeek)->only([88, 89, 90, 91])->sum() : '',
            'Week40' => $grandTotalByWeek['92'] != 0 ? $grandTotalByWeek['92'] : '',
            'Week41' => $grandTotalByWeek['93'] != 0 ? $grandTotalByWeek['93'] : '',
            'Week42' => $grandTotalByWeek['94'] != 0 ? $grandTotalByWeek['94'] : '',
            'Week43' => $grandTotalByWeek['95'] != 0 ? $grandTotalByWeek['95'] : '',
            'Oct' => collect($grandTotalByWeek)->only([92, 93, 94, 95])->sum() != 0 ? collect($grandTotalByWeek)->only([92, 93, 94, 95])->sum() : '',
            'Week44' => $grandTotalByWeek['96'] != 0 ? $grandTotalByWeek['96'] : '',
            'Week45' => $grandTotalByWeek['97'] != 0 ? $grandTotalByWeek['97'] : '',
            'Week46' => $grandTotalByWeek['98'] != 0 ? $grandTotalByWeek['98'] : '',
            'Week47' => $grandTotalByWeek['99'] != 0 ? $grandTotalByWeek['99'] : '',
            'Week48' => $grandTotalByWeek['100'] != 0 ? $grandTotalByWeek['100'] : '',
            'Nov' => collect($grandTotalByWeek)->only([96, 97, 98, 99, 100])->sum() != 0 ? collect($grandTotalByWeek)->only([96, 97, 98, 99, 100])->sum() : '',
            'Week49' => $grandTotalByWeek['101'] != 0 ? $grandTotalByWeek['101'] : '',
            'Week50' => $grandTotalByWeek['102'] != 0 ? $grandTotalByWeek['102'] : '',
            'Week51' => $grandTotalByWeek['103'] != 0 ? $grandTotalByWeek['103'] : '',
            'Week52' => $grandTotalByWeek['104'] != 0 ? $grandTotalByWeek['104'] : '',
            'Dec' => collect($grandTotalByWeek)->only([101, 102, 103, 104])->sum() != 0 ? collect($grandTotalByWeek)->only([101, 102, 103, 104])->sum() : '',
            'GrandTotalByProgram' => $grandTotalForAllPrograms,
        ];

        $response = [
            'classes' => $mappedGroupedClasses,
        ];

        return response()->json($response);
    }

    public function dashboardClassesGuatemala4(Request $request)
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
            ->when(true, function ($query) {
                $query->whereHas('site', function ($subquery) {
                    $subquery->where('is_active', 1);
                });

                $query->whereHas('site', function ($subquery) {
                    $subquery->where('country', 'Guatemala');
                });
            })

            ->where('is_active', 1) // You can keep or remove this line based on your requirements
            ->get();

        $year = 2024;
        $dateRanges = DateRange::where('year', $year)
            ->get();

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
                    '53' => 0,
                    '54' => 0,
                    '55' => 0,
                    '56' => 0,
                    '57' => 0,
                    '58' => 0,
                    '59' => 0,
                    '60' => 0,
                    '61' => 0,
                    '62' => 0,
                    '63' => 0,
                    '64' => 0,
                    '65' => 0,
                    '66' => 0,
                    '67' => 0,
                    '68' => 0,
                    '69' => 0,
                    '70' => 0,
                    '71' => 0,
                    '72' => 0,
                    '73' => 0,
                    '74' => 0,
                    '75' => 0,
                    '76' => 0,
                    '77' => 0,
                    '78' => 0,
                    '79' => 0,
                    '80' => 0,
                    '81' => 0,
                    '82' => 0,
                    '83' => 0,
                    '84' => 0,
                    '85' => 0,
                    '86' => 0,
                    '87' => 0,
                    '88' => 0,
                    '89' => 0,
                    '90' => 0,
                    '91' => 0,
                    '92' => 0,
                    '93' => 0,
                    '94' => 0,
                    '95' => 0,
                    '96' => 0,
                    '97' => 0,
                    '98' => 0,
                    '99' => 0,
                    '100' => 0,
                    '101' => 0,
                    '102' => 0,
                    '103' => 0,
                    '104' => 0,
                ];

                foreach ($programData as $week => $weekData) {
                    $weeklyData[$week] = isset($weekData['total_target']) ? $weekData['total_target'] : 0;
                }
                $grandTotal = $grandTotalByProgram[$siteName][$programName];
                if ($grandTotal != 0) {
                    $mappedGroupedClasses[] = [
                        'Site' => $siteName,
                        'Program' => $programName,
                        'Week1' => $weeklyData['53'] != 0 ? $weeklyData['53'] : '',
                        'Week2' => $weeklyData['54'] != 0 ? $weeklyData['54'] : '',
                        'Week3' => $weeklyData['55'] != 0 ? $weeklyData['55'] : '',
                        'Week4' => $weeklyData['56'] != 0 ? $weeklyData['56'] : '',
                        'Jan' => collect($weeklyData)->only([53, 54, 55, 56])->sum() != 0 ? collect($weeklyData)->only([53, 54, 55, 56])->sum() : '',
                        'Week5' => $weeklyData['57'] != 0 ? $weeklyData['57'] : '',
                        'Week6' => $weeklyData['58'] != 0 ? $weeklyData['58'] : '',
                        'Week7' => $weeklyData['59'] != 0 ? $weeklyData['59'] : '',
                        'Week8' => $weeklyData['60'] != 0 ? $weeklyData['60'] : '',
                        'Feb' => collect($weeklyData)->only([57, 58, 59, 60])->sum() != 0 ? collect($weeklyData)->only([57, 58, 59, 60])->sum() : '',
                        'Week9' => $weeklyData['61'] != 0 ? $weeklyData['61'] : '',
                        'Week10' => $weeklyData['62'] != 0 ? $weeklyData['62'] : '',
                        'Week11' => $weeklyData['63'] != 0 ? $weeklyData['63'] : '',
                        'Week12' => $weeklyData['64'] != 0 ? $weeklyData['64'] : '',
                        'Week13' => $weeklyData['65'] != 0 ? $weeklyData['65'] : '',
                        'Mar' => collect($weeklyData)->only([61, 62, 63, 64, 65])->sum() != 0 ? collect($weeklyData)->only([61, 62, 63, 64, 65])->sum() : '',
                        'Week14' => $weeklyData['66'] != 0 ? $weeklyData['66'] : '',
                        'Week15' => $weeklyData['67'] != 0 ? $weeklyData['67'] : '',
                        'Week16' => $weeklyData['68'] != 0 ? $weeklyData['68'] : '',
                        'Week17' => $weeklyData['69'] != 0 ? $weeklyData['69'] : '',
                        'Apr' => collect($weeklyData)->only([66, 67, 68, 69])->sum() != 0 ? collect($weeklyData)->only([66, 67, 68, 69])->sum() : '',
                        'Week18' => $weeklyData['70'] != 0 ? $weeklyData['70'] : '',
                        'Week19' => $weeklyData['71'] != 0 ? $weeklyData['71'] : '',
                        'Week20' => $weeklyData['72'] != 0 ? $weeklyData['72'] : '',
                        'Week21' => $weeklyData['73'] != 0 ? $weeklyData['73'] : '',
                        'May' => collect($weeklyData)->only([70, 71, 72, 73])->sum() != 0 ? collect($weeklyData)->only([70, 71, 72, 73])->sum() : '',
                        'Week22' => $weeklyData['74'] != 0 ? $weeklyData['74'] : '',
                        'Week23' => $weeklyData['75'] != 0 ? $weeklyData['75'] : '',
                        'Week24' => $weeklyData['76'] != 0 ? $weeklyData['76'] : '',
                        'Week25' => $weeklyData['77'] != 0 ? $weeklyData['77'] : '',
                        'Week26' => $weeklyData['78'] != 0 ? $weeklyData['78'] : '',
                        'Jun' => collect($weeklyData)->only([74, 75, 76, 77, 78])->sum() != 0 ? collect($weeklyData)->only([74, 75, 76, 77, 78])->sum() : '',
                        'Week27' => $weeklyData['79'] != 0 ? $weeklyData['79'] : '',
                        'Week28' => $weeklyData['80'] != 0 ? $weeklyData['80'] : '',
                        'Week29' => $weeklyData['81'] != 0 ? $weeklyData['81'] : '',
                        'Week30' => $weeklyData['82'] != 0 ? $weeklyData['82'] : '',
                        'Jul' => collect($weeklyData)->only([79, 80, 81, 82])->sum() != 0 ? collect($weeklyData)->only([79, 80, 81, 82])->sum() : '',
                        'Week31' => $weeklyData['83'] != 0 ? $weeklyData['83'] : '',
                        'Week32' => $weeklyData['84'] != 0 ? $weeklyData['84'] : '',
                        'Week33' => $weeklyData['85'] != 0 ? $weeklyData['85'] : '',
                        'Week34' => $weeklyData['86'] != 0 ? $weeklyData['86'] : '',
                        'Week35' => $weeklyData['87'] != 0 ? $weeklyData['87'] : '',
                        'Aug' => collect($weeklyData)->only([83, 84, 85, 86, 87])->sum() != 0 ? collect($weeklyData)->only([83, 84, 85, 86, 87])->sum() : '',
                        'Week36' => $weeklyData['88'] != 0 ? $weeklyData['88'] : '',
                        'Week37' => $weeklyData['89'] != 0 ? $weeklyData['89'] : '',
                        'Week38' => $weeklyData['90'] != 0 ? $weeklyData['90'] : '',
                        'Week39' => $weeklyData['91'] != 0 ? $weeklyData['91'] : '',
                        'Sep' => collect($weeklyData)->only([88, 89, 90, 91])->sum() != 0 ? collect($weeklyData)->only([88, 89, 90, 91])->sum() : '',
                        'Week40' => $weeklyData['92'] != 0 ? $weeklyData['92'] : '',
                        'Week41' => $weeklyData['93'] != 0 ? $weeklyData['93'] : '',
                        'Week42' => $weeklyData['94'] != 0 ? $weeklyData['94'] : '',
                        'Week43' => $weeklyData['95'] != 0 ? $weeklyData['95'] : '',
                        'Oct' => collect($weeklyData)->only([92, 93, 94, 95])->sum() != 0 ? collect($weeklyData)->only([92, 93, 94, 95])->sum() : '',
                        'Week44' => $weeklyData['96'] != 0 ? $weeklyData['96'] : '',
                        'Week45' => $weeklyData['97'] != 0 ? $weeklyData['97'] : '',
                        'Week46' => $weeklyData['98'] != 0 ? $weeklyData['98'] : '',
                        'Week47' => $weeklyData['99'] != 0 ? $weeklyData['99'] : '',
                        'Week48' => $weeklyData['100'] != 0 ? $weeklyData['100'] : '',
                        'Nov' => collect($weeklyData)->only([96, 97, 98, 99, 100])->sum() != 0 ? collect($weeklyData)->only([96, 97, 98, 99, 100])->sum() : '',
                        'Week49' => $weeklyData['101'] != 0 ? $weeklyData['101'] : '',
                        'Week50' => $weeklyData['102'] != 0 ? $weeklyData['102'] : '',
                        'Week51' => $weeklyData['103'] != 0 ? $weeklyData['103'] : '',
                        'Week52' => $weeklyData['104'] != 0 ? $weeklyData['104'] : '',
                        'Dec' => collect($weeklyData)->only([101, 102, 103, 104])->sum() != 0 ? collect($weeklyData)->only([101, 102, 103, 104])->sum() : '',

                        'GrandTotalByProgram' => $grandTotal,
                    ];
                }
            }
        }

        $grandTotalForAllPrograms = array_sum(array_map('array_sum', $grandTotalByProgram));

        $mappedGroupedClasses[] = [
            'Site' => 'Grand Total',
            'Program' => '',
            'Week1' => $grandTotalByWeek['53'] != 0 ? $grandTotalByWeek['53'] : '',
            'Week2' => $grandTotalByWeek['54'] != 0 ? $grandTotalByWeek['54'] : '',
            'Week3' => $grandTotalByWeek['55'] != 0 ? $grandTotalByWeek['55'] : '',
            'Week4' => $grandTotalByWeek['56'] != 0 ? $grandTotalByWeek['56'] : '',
            'Jan' => collect($grandTotalByWeek)->only([53, 54, 55, 56])->sum() != 0 ? collect($grandTotalByWeek)->only([53, 54, 55, 56])->sum() : '',
            'Week5' => $grandTotalByWeek['57'] != 0 ? $grandTotalByWeek['57'] : '',
            'Week6' => $grandTotalByWeek['58'] != 0 ? $grandTotalByWeek['58'] : '',
            'Week7' => $grandTotalByWeek['59'] != 0 ? $grandTotalByWeek['59'] : '',
            'Week8' => $grandTotalByWeek['60'] != 0 ? $grandTotalByWeek['60'] : '',
            'Feb' => collect($grandTotalByWeek)->only([57, 58, 59, 60])->sum() != 0 ? collect($grandTotalByWeek)->only([57, 58, 59, 60])->sum() : '',
            'Week9' => $grandTotalByWeek['61'] != 0 ? $grandTotalByWeek['61'] : '',
            'Week10' => $grandTotalByWeek['62'] != 0 ? $grandTotalByWeek['62'] : '',
            'Week11' => $grandTotalByWeek['63'] != 0 ? $grandTotalByWeek['63'] : '',
            'Week12' => $grandTotalByWeek['64'] != 0 ? $grandTotalByWeek['64'] : '',
            'Week13' => $grandTotalByWeek['65'] != 0 ? $grandTotalByWeek['65'] : '',
            'Mar' => collect($grandTotalByWeek)->only([61, 62, 63, 64, 65])->sum() != 0 ? collect($grandTotalByWeek)->only([61, 62, 63, 64, 65])->sum() : '',
            'Week14' => $grandTotalByWeek['66'] != 0 ? $grandTotalByWeek['66'] : '',
            'Week15' => $grandTotalByWeek['67'] != 0 ? $grandTotalByWeek['67'] : '',
            'Week16' => $grandTotalByWeek['68'] != 0 ? $grandTotalByWeek['68'] : '',
            'Week17' => $grandTotalByWeek['69'] != 0 ? $grandTotalByWeek['69'] : '',
            'Apr' => collect($grandTotalByWeek)->only([66, 67, 68, 69])->sum() != 0 ? collect($grandTotalByWeek)->only([66, 67, 68, 69])->sum() : '',
            'Week18' => $grandTotalByWeek['70'] != 0 ? $grandTotalByWeek['70'] : '',
            'Week19' => $grandTotalByWeek['71'] != 0 ? $grandTotalByWeek['71'] : '',
            'Week20' => $grandTotalByWeek['72'] != 0 ? $grandTotalByWeek['72'] : '',
            'Week21' => $grandTotalByWeek['73'] != 0 ? $grandTotalByWeek['73'] : '',
            'May' => collect($grandTotalByWeek)->only([70, 71, 72, 73])->sum() != 0 ? collect($grandTotalByWeek)->only([70, 71, 72, 73])->sum() : '',
            'Week22' => $grandTotalByWeek['74'] != 0 ? $grandTotalByWeek['74'] : '',
            'Week23' => $grandTotalByWeek['75'] != 0 ? $grandTotalByWeek['75'] : '',
            'Week24' => $grandTotalByWeek['76'] != 0 ? $grandTotalByWeek['76'] : '',
            'Week25' => $grandTotalByWeek['77'] != 0 ? $grandTotalByWeek['77'] : '',
            'Week26' => $grandTotalByWeek['78'] != 0 ? $grandTotalByWeek['78'] : '',
            'Jun' => collect($grandTotalByWeek)->only([74, 75, 76, 77, 78])->sum() != 0 ? collect($grandTotalByWeek)->only([74, 75, 76, 77, 78])->sum() : '',
            'Week27' => $grandTotalByWeek['79'] != 0 ? $grandTotalByWeek['79'] : '',
            'Week28' => $grandTotalByWeek['80'] != 0 ? $grandTotalByWeek['80'] : '',
            'Week29' => $grandTotalByWeek['81'] != 0 ? $grandTotalByWeek['81'] : '',
            'Week30' => $grandTotalByWeek['82'] != 0 ? $grandTotalByWeek['82'] : '',
            'Jul' => collect($grandTotalByWeek)->only([79, 80, 81, 82])->sum() != 0 ? collect($grandTotalByWeek)->only([79, 80, 81, 82])->sum() : '',
            'Week31' => $grandTotalByWeek['83'] != 0 ? $grandTotalByWeek['83'] : '',
            'Week32' => $grandTotalByWeek['84'] != 0 ? $grandTotalByWeek['84'] : '',
            'Week33' => $grandTotalByWeek['85'] != 0 ? $grandTotalByWeek['85'] : '',
            'Week34' => $grandTotalByWeek['86'] != 0 ? $grandTotalByWeek['86'] : '',
            'Week35' => $grandTotalByWeek['87'] != 0 ? $grandTotalByWeek['87'] : '',
            'Aug' => collect($grandTotalByWeek)->only([83, 84, 85, 86, 87])->sum() != 0 ? collect($grandTotalByWeek)->only([83, 84, 85, 86, 87])->sum() : '',
            'Week36' => $grandTotalByWeek['88'] != 0 ? $grandTotalByWeek['88'] : '',
            'Week37' => $grandTotalByWeek['89'] != 0 ? $grandTotalByWeek['89'] : '',
            'Week38' => $grandTotalByWeek['90'] != 0 ? $grandTotalByWeek['90'] : '',

            'Week39' => $grandTotalByWeek['91'] != 0 ? $grandTotalByWeek['91'] : '',
            'Sep' => collect($grandTotalByWeek)->only([88, 89, 90, 91])->sum() != 0 ? collect($grandTotalByWeek)->only([88, 89, 90, 91])->sum() : '',
            'Week40' => $grandTotalByWeek['92'] != 0 ? $grandTotalByWeek['92'] : '',
            'Week41' => $grandTotalByWeek['93'] != 0 ? $grandTotalByWeek['93'] : '',
            'Week42' => $grandTotalByWeek['94'] != 0 ? $grandTotalByWeek['94'] : '',
            'Week43' => $grandTotalByWeek['95'] != 0 ? $grandTotalByWeek['95'] : '',
            'Oct' => collect($grandTotalByWeek)->only([92, 93, 94, 95])->sum() != 0 ? collect($grandTotalByWeek)->only([92, 93, 94, 95])->sum() : '',
            'Week44' => $grandTotalByWeek['96'] != 0 ? $grandTotalByWeek['96'] : '',
            'Week45' => $grandTotalByWeek['97'] != 0 ? $grandTotalByWeek['97'] : '',
            'Week46' => $grandTotalByWeek['98'] != 0 ? $grandTotalByWeek['98'] : '',
            'Week47' => $grandTotalByWeek['99'] != 0 ? $grandTotalByWeek['99'] : '',
            'Week48' => $grandTotalByWeek['100'] != 0 ? $grandTotalByWeek['100'] : '',
            'Nov' => collect($grandTotalByWeek)->only([96, 97, 98, 99, 100])->sum() != 0 ? collect($grandTotalByWeek)->only([96, 97, 98, 99, 100])->sum() : '',
            'Week49' => $grandTotalByWeek['101'] != 0 ? $grandTotalByWeek['101'] : '',
            'Week50' => $grandTotalByWeek['102'] != 0 ? $grandTotalByWeek['102'] : '',
            'Week51' => $grandTotalByWeek['103'] != 0 ? $grandTotalByWeek['103'] : '',
            'Week52' => $grandTotalByWeek['104'] != 0 ? $grandTotalByWeek['104'] : '',
            'Dec' => collect($grandTotalByWeek)->only([101, 102, 103, 104])->sum() != 0 ? collect($grandTotalByWeek)->only([101, 102, 103, 104])->sum() : '',
            'GrandTotalByProgram' => $grandTotalForAllPrograms,
        ];

        $response = [
            'classes' => $mappedGroupedClasses,
        ];

        return response()->json($response);
    }
    public function classesallJam(Request $request)
    {
        $classes = Classes::whereHas('site', function ($query) {
            $query->where('country', '=', 'Jamaica');
        })
            ->whereHas('dateRange', function ($query) {
                $query->where('year', '=', '2024');
            })
            ->with('site', 'program', 'dateRange', 'createdByUser', 'updatedByUser')
            ->where('status', 'Active')
            ->when($request->has('sites_selected') && $request->sites_selected !== null, function ($query) use ($request) {
                $query->where('site_id', $request->sites_selected);
            })
            ->when($request->has('programs_selected') && $request->programs_selected !== null, function ($query) use ($request) {
                $query->where('program_id', $request->programs_selected);
            })
            ->when($request->has('month_selected') && $request->month_selected !== null, function ($query) use ($request) {
                $query->whereHas('dateRange', function ($query) use ($request) {
                    $query->where('month_num', $request->month_selected);
                });
            })
            ->when($request->has('week_selected') && $request->week_selected !== null, function ($query) use ($request) {
                $query->whereHas('dateRange', function ($query) use ($request) {
                    $query->where('date_Range_id', $request->week_selected);
                });
            })
            ->get();

        $formattedClasses = $classes->map(function ($class) {
            return [
                'id' => $class->id,
                'country' => $class->site->country,
                'region' => $class->site->region,
                'site_name' => $class->site->name,
                'site_id' => $class->site->id,
                'program_name' => $class->program->name,
                'program_id' => $class->program->id,
                'date_range' => $class->dateRange->date_range,
                'date_range_id' => $class->dateRange->id,
                'month' => $class->dateRange->month,
                'external_target' => $class->external_target,
                'internal_target' => $class->internal_target,
                'total_target' => $class->total_target,
                'within_sla' => $class->within_sla,
                'condition' => $class->condition,
                'original_start_date' => $class->original_start_date,
                'changes' => $class->changes,
                'agreed_start_date' => $class->agreed_start_date,
                'wfm_date_requested' => $class->wfm_date_requested,
                'notice_weeks' => number_format($class->notice_weeks, 2),
                'notice_days' => $class->notice_days,
                'pipeline_utilized' => $class->pipeline_utilized,
                'remarks' => $class->remarks,
                'status' => $class->status,
                'category' => $class->category,
                'type_of_hiring' => $class->type_of_hiring,
                'with_erf' => $class->with_erf,
                'erf_number' => $class->erf_number,
                'wave_no' => $class->wave_no,
                'ta' => $class->ta,
                'wf' => $class->wf,
                'tr' => $class->tr,
                'cl' => $class->cl,
                'op' => $class->op,
                'approved_by' => $class->approved_by,
                'cancelled_by' => $class->cancelledByUser ? $class->cancelledByUser->name : null,
                'requested_by' => $class->requested_by,
                'created_by' => $class->createdByUser->name,
                'updated_by' => $class->updatedByUser ? $class->updatedByUser->name : null,
                'approved_date' => $class->approved_date,
                'cancelled_date' => $class->cancelled_date,
                'created_at' => $class->created_at ? $class->created_at->format('m-d-Y H:i') : null,
                'updated_at' => $class->updated_at ? $class->updated_at->format('m-d-Y H:i') : null,
            ];
        });

        return response()->json([
            'classes' => $formattedClasses,
        ]);
    }
    public function classesallGua(Request $request)
    {
        $classes = Classes::whereHas('site', function ($query) {
            $query->where('country', '=', 'Guatemala');
        })
            ->whereHas('dateRange', function ($query) {
                $query->where('year', '=', '2024');
            })
            ->with('site', 'program', 'dateRange', 'createdByUser', 'updatedByUser')
            ->where('status', 'Active')
            ->when($request->has('sites_selected') && $request->sites_selected !== null, function ($query) use ($request) {
                $query->where('site_id', $request->sites_selected);
            })
            ->when($request->has('programs_selected') && $request->programs_selected !== null, function ($query) use ($request) {
                $query->where('program_id', $request->programs_selected);
            })
            ->when($request->has('month_selected') && $request->month_selected !== null, function ($query) use ($request) {
                $query->whereHas('dateRange', function ($query) use ($request) {
                    $query->where('month_num', $request->month_selected);
                });
            })
            ->when($request->has('week_selected') && $request->week_selected !== null, function ($query) use ($request) {
                $query->whereHas('dateRange', function ($query) use ($request) {
                    $query->where('date_Range_id', $request->week_selected);
                });
            })
            ->get();

        $formattedClasses = $classes->map(function ($class) {
            return [
                'id' => $class->id,
                'country' => $class->site->country,
                'region' => $class->site->region,
                'site_name' => $class->site->name,
                'site_id' => $class->site->id,
                'program_name' => $class->program->name,
                'program_id' => $class->program->id,
                'date_range' => $class->dateRange->date_range,
                'date_range_id' => $class->dateRange->id,
                'month' => $class->dateRange->month,
                'external_target' => $class->external_target,
                'internal_target' => $class->internal_target,
                'total_target' => $class->total_target,
                'within_sla' => $class->within_sla,
                'condition' => $class->condition,
                'original_start_date' => $class->original_start_date,
                'changes' => $class->changes,
                'agreed_start_date' => $class->agreed_start_date,
                'wfm_date_requested' => $class->wfm_date_requested,
                'notice_weeks' => number_format($class->notice_weeks, 2),
                'notice_days' => $class->notice_days,
                'pipeline_utilized' => $class->pipeline_utilized,
                'remarks' => $class->remarks,
                'status' => $class->status,
                'category' => $class->category,
                'type_of_hiring' => $class->type_of_hiring,
                'with_erf' => $class->with_erf,
                'erf_number' => $class->erf_number,
                'wave_no' => $class->wave_no,
                'ta' => $class->ta,
                'wf' => $class->wf,
                'tr' => $class->tr,
                'cl' => $class->cl,
                'op' => $class->op,
                'approved_by' => $class->approved_by,
                'cancelled_by' => $class->cancelledByUser ? $class->cancelledByUser->name : null,
                'requested_by' => $class->requested_by,
                'created_by' => $class->createdByUser->name,
                'updated_by' => $class->updatedByUser ? $class->updatedByUser->name : null,
                'approved_date' => $class->approved_date,
                'cancelled_date' => $class->cancelled_date,
                'created_at' => $class->created_at ? $class->created_at->format('m-d-Y H:i') : null,
                'updated_at' => $class->updated_at ? $class->updated_at->format('m-d-Y H:i') : null,
            ];
        });

        return response()->json([
            'classes' => $formattedClasses,
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

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $class = Classes::find($id);
        $class->status = 'Moved';
        $class->save();

        $newClass = $class->replicate();
        $newClass->update_status = $class->update_status + 1;
        $newClass->changes = 'Pushedback';
        $newClass->requested_by = $request->requested_by; // Directly assign the value without further processing

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
            'pipeline_utilized' => 'required',
            'pipeline_offered' => 'required',
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
    public function retrieveB2DataForEmail(Request $request)
    {
        $programs = Site::where('is_active', 1)->where('country', 'Philippines')->get();
        $year = 2024;
        $dateRanges = DateRange::select('month_num')->where('year', $year)->groupBy('month_num')->get();
        $groupedClasses = [];
        $grandTotalByWeek = [];
        $grandTotalByWeek2 = [];
        $grandTotalByProgram = [];
        $grandTotalByProgram2 = []; // Declare $b2percentage array

        foreach ($programs as $program) {
            $siteName = $program->name;
            if (!isset($grandTotalByProgram[$siteName])) {
                $grandTotalByProgram[$siteName] = 0;
            }
            if (!isset($grandTotalByProgram2[$siteName])) {
                $grandTotalByProgram2[$siteName] = 0;
            }
            foreach ($dateRanges as $dateRange) {
                $month = $dateRange->month_num;
                $classes = Classes::with('site', 'program', 'dateRange', 'createdByUser', 'updatedByUser')
                    ->whereHas('dateRange', function ($subquery) use ($month, $year) {
                        $subquery->where('month_num', $month)->where('year', $year);
                    })
                    ->whereHas('program', function ($subquery) {
                        $subquery->where('is_active', 1)->where('program_type', 'B2')->orWhere('program_type', 'COMCAST');
                    })
                    ->where('site_id', $program->id)
                    ->where('status', 'Active')
                    ->get();
                $classes2 = Classes::with('site', 'program', 'dateRange', 'createdByUser', 'updatedByUser')
                    ->whereHas('dateRange', function ($subquery) use ($month, $year) {
                        $subquery->where('month_num', $month)->where('year', $year);
                    })
                    ->whereHas('program', function ($subquery) {
                        $subquery->where('is_active', 1);
                    })
                    ->where('site_id', $program->id)
                    ->where('status', 'Active')
                    ->get();
                $totalTarget = $classes->sum('total_target');
                $totalTarget2 = $classes2->sum('total_target');

                if (!isset($grandTotalByWeek[$month])) {
                    $grandTotalByWeek[$month] = 0;
                }
                if (!isset($grandTotalByWeek2[$month])) {
                    $grandTotalByWeek2[$month] = 0;
                }

                $grandTotalByWeek[$month] += $totalTarget;
                $grandTotalByWeek2[$month] += $totalTarget2;
                $grandTotalByProgram[$siteName] += $totalTarget;

                $grandTotalByProgram2[$siteName] += $totalTarget2;

                if (!isset($groupedClasses[$siteName][$month])) {
                    $groupedClasses[$siteName][$month] = ['total_target' => 0];
                }
                $groupedClasses[$siteName][$month]['total_target'] += $totalTarget;
            }
        }
        $grandTotalForAllPrograms = array_sum($grandTotalByProgram);
        $grandTotalForAllPrograms2 = array_sum($grandTotalByProgram2);

        $mappedB2Classes = [];
        $mappedB2Classes[] = [
            'Site' => 'B2 Percentage',
            'January' => $grandTotalByWeek2['1'] != 0 ? round(($grandTotalByWeek['1'] / $grandTotalByWeek2['1']) * 100, 2) . '%' : '0%',
            'February' => $grandTotalByWeek2['2'] != 0 ? round(($grandTotalByWeek['2'] / $grandTotalByWeek2['2']) * 100, 2) . '%' : '0%',
            'March' => $grandTotalByWeek2['3'] != 0 ? round(($grandTotalByWeek['3'] / $grandTotalByWeek2['3']) * 100, 2) . '%' : '0%',
            'April' => $grandTotalByWeek2['4'] != 0 ? round(($grandTotalByWeek['4'] / $grandTotalByWeek2['4']) * 100, 2) . '%' : '0%',
            'May' => $grandTotalByWeek2['5'] != 0 ? round(($grandTotalByWeek['5'] / $grandTotalByWeek2['5']) * 100, 2) . '%' : '0%',
            'June' => $grandTotalByWeek2['6'] != 0 ? round(($grandTotalByWeek['6'] / $grandTotalByWeek2['6']) * 100, 2) . '%' : '0%',
            'July' => $grandTotalByWeek2['7'] != 0 ? round(($grandTotalByWeek['7'] / $grandTotalByWeek2['7']) * 100, 2) . '%' : '0%',
            'August' => $grandTotalByWeek2['8'] != 0 ? round(($grandTotalByWeek['8'] / $grandTotalByWeek2['8']) * 100, 2) . '%' : '0%',
            'September' => $grandTotalByWeek2['9'] != 0 ? round(($grandTotalByWeek['9'] / $grandTotalByWeek2['9']) * 100, 2) . '%' : '0%',
            'October' => $grandTotalByWeek2['10'] != 0 ? round(($grandTotalByWeek['10'] / $grandTotalByWeek2['10']) * 100, 2) . '%' : '0%',
            'November' => $grandTotalByWeek2['11'] != 0 ? round(($grandTotalByWeek['11'] / $grandTotalByWeek2['11']) * 100, 2) . '%' : '0%',
            'December' => $grandTotalByWeek2['12'] != 0 ? round(($grandTotalByWeek['12'] / $grandTotalByWeek2['12']) * 100, 2) . '%' : '0%',
            'GrandTotalByProgram' => $grandTotalForAllPrograms2 != 0 ? round(($grandTotalForAllPrograms / $grandTotalForAllPrograms2) * 100, 2) . '%' : '0%',

        ];
        foreach ($groupedClasses as $siteName => $siteData) {
            $weeklyData = [
                '1' => 0, '2' => 0, '3' => 0, '4' => 0, '5' => 0, '6' => 0, '7' => 0, '8' => 0,
                '9' => 0, '10' => 0, '11' => 0, '12' => 0,
            ];
            foreach ($siteData as $month => $weekData) {
                $weeklyData[$month] = isset($weekData['total_target']) ? $weekData['total_target'] : 0;
            }
            $grandTotal = $grandTotalByProgram[$siteName];
            if ($grandTotal != 0) {
                $mappedB2Classes[] = [
                    'Site' => $siteName,
                    'January' => $weeklyData['1'] ?: '',
                    'February' => $weeklyData['2'] ?: '',
                    'March' => $weeklyData['3'] ?: '',
                    'April' => $weeklyData['4'] ?: '',
                    'May' => $weeklyData['5'] ?: '',
                    'June' => $weeklyData['6'] ?: '',
                    'July' => $weeklyData['7'] ?: '',
                    'August' => $weeklyData['8'] ?: '',
                    'September' => $weeklyData['9'] ?: '',
                    'October' => $weeklyData['10'] ?: '',
                    'November' => $weeklyData['11'] ?: '',
                    'December' => $weeklyData['12'] ?: '',
                    'GrandTotalByProgram' => $grandTotal,
                ];
            }
        }

        $mappedB2Classes[] = [
            'Site' => 'Grand Total',
            'January' => $grandTotalByWeek['1'] ?: '',
            'February' => $grandTotalByWeek['2'] ?: '',
            'March' => $grandTotalByWeek['3'] ?: '',
            'April' => $grandTotalByWeek['4'] ?: '',
            'May' => $grandTotalByWeek['5'] ?: '',
            'June' => $grandTotalByWeek['6'] ?: '',
            'July' => $grandTotalByWeek['7'] ?: '',
            'August' => $grandTotalByWeek['8'] ?: '',
            'September' => $grandTotalByWeek['9'] ?: '',
            'October' => $grandTotalByWeek['10'] ?: '',
            'November' => $grandTotalByWeek['11'] ?: '',
            'December' => $grandTotalByWeek['12'] ?: '',
            'GrandTotalByProgram' => $grandTotalForAllPrograms,
        ];

        $response = ['data' => $mappedB2Classes];

        return response()->json($response);
    }
    public function retrieveB2DataForEmailJamaica(Request $request)
    {
        $programs = Site::where('is_active', 1)->where('country', 'Jamaica')->get();
        $year = 2024;
        $dateRanges = DateRange::select('month_num')->where('year', $year)->groupBy('month_num')->get();
        $groupedClasses = [];
        $grandTotalByWeek = [];
        $grandTotalByWeek2 = [];
        $grandTotalByProgram = [];
        $grandTotalByProgram2 = []; // Declare $b2percentage array

        foreach ($programs as $program) {
            $siteName = $program->name;
            if (!isset($grandTotalByProgram[$siteName])) {
                $grandTotalByProgram[$siteName] = 0;
            }
            if (!isset($grandTotalByProgram2[$siteName])) {
                $grandTotalByProgram2[$siteName] = 0;
            }
            foreach ($dateRanges as $dateRange) {
                $month = $dateRange->month_num;
                $classes = Classes::with('site', 'program', 'dateRange', 'createdByUser', 'updatedByUser')
                    ->whereHas('dateRange', function ($subquery) use ($month, $year) {
                        $subquery->where('month_num', $month)->where('year', $year);
                    })
                    ->whereHas('program', function ($subquery) {
                        $subquery->where('is_active', 1)->where('program_type', 'B2')->orWhere('program_type', 'COMCAST');
                    })
                    ->where('site_id', $program->id)
                    ->where('status', 'Active')
                    ->get();
                $classes2 = Classes::with('site', 'program', 'dateRange', 'createdByUser', 'updatedByUser')
                    ->whereHas('dateRange', function ($subquery) use ($month, $year) {
                        $subquery->where('month_num', $month)->where('year', $year);
                    })
                    ->whereHas('program', function ($subquery) {
                        $subquery->where('is_active', 1);
                    })
                    ->where('site_id', $program->id)
                    ->where('status', 'Active')
                    ->get();
                $totalTarget = $classes->sum('total_target');
                $totalTarget2 = $classes2->sum('total_target');

                if (!isset($grandTotalByWeek[$month])) {
                    $grandTotalByWeek[$month] = 0;
                }
                if (!isset($grandTotalByWeek2[$month])) {
                    $grandTotalByWeek2[$month] = 0;
                }

                $grandTotalByWeek[$month] += $totalTarget;
                $grandTotalByWeek2[$month] += $totalTarget2;
                $grandTotalByProgram[$siteName] += $totalTarget;

                $grandTotalByProgram2[$siteName] += $totalTarget2;

                if (!isset($groupedClasses[$siteName][$month])) {
                    $groupedClasses[$siteName][$month] = ['total_target' => 0];
                }
                $groupedClasses[$siteName][$month]['total_target'] += $totalTarget;
            }
        }
        $grandTotalForAllPrograms = array_sum($grandTotalByProgram);
        $grandTotalForAllPrograms2 = array_sum($grandTotalByProgram2);

        $mappedB2Classes = [];
        $mappedB2Classes[] = [
            'Site' => 'B2 Percentage',
            'January' => $grandTotalByWeek2['1'] != 0 ? round(($grandTotalByWeek['1'] / $grandTotalByWeek2['1']) * 100, 2) . '%' : '0%',
            'February' => $grandTotalByWeek2['2'] != 0 ? round(($grandTotalByWeek['2'] / $grandTotalByWeek2['2']) * 100, 2) . '%' : '0%',
            'March' => $grandTotalByWeek2['3'] != 0 ? round(($grandTotalByWeek['3'] / $grandTotalByWeek2['3']) * 100, 2) . '%' : '0%',
            'April' => $grandTotalByWeek2['4'] != 0 ? round(($grandTotalByWeek['4'] / $grandTotalByWeek2['4']) * 100, 2) . '%' : '0%',
            'May' => $grandTotalByWeek2['5'] != 0 ? round(($grandTotalByWeek['5'] / $grandTotalByWeek2['5']) * 100, 2) . '%' : '0%',
            'June' => $grandTotalByWeek2['6'] != 0 ? round(($grandTotalByWeek['6'] / $grandTotalByWeek2['6']) * 100, 2) . '%' : '0%',
            'July' => $grandTotalByWeek2['7'] != 0 ? round(($grandTotalByWeek['7'] / $grandTotalByWeek2['7']) * 100, 2) . '%' : '0%',
            'August' => $grandTotalByWeek2['8'] != 0 ? round(($grandTotalByWeek['8'] / $grandTotalByWeek2['8']) * 100, 2) . '%' : '0%',
            'September' => $grandTotalByWeek2['9'] != 0 ? round(($grandTotalByWeek['9'] / $grandTotalByWeek2['9']) * 100, 2) . '%' : '0%',
            'October' => $grandTotalByWeek2['10'] != 0 ? round(($grandTotalByWeek['10'] / $grandTotalByWeek2['10']) * 100, 2) . '%' : '0%',
            'November' => $grandTotalByWeek2['11'] != 0 ? round(($grandTotalByWeek['11'] / $grandTotalByWeek2['11']) * 100, 2) . '%' : '0%',
            'December' => $grandTotalByWeek2['12'] != 0 ? round(($grandTotalByWeek['12'] / $grandTotalByWeek2['12']) * 100, 2) . '%' : '0%',
            'GrandTotalByProgram' => $grandTotalForAllPrograms2 != 0 ? round(($grandTotalForAllPrograms / $grandTotalForAllPrograms2) * 100, 2) . '%' : '0%',

        ];
        foreach ($groupedClasses as $siteName => $siteData) {
            $weeklyData = [
                '1' => 0, '2' => 0, '3' => 0, '4' => 0, '5' => 0, '6' => 0, '7' => 0, '8' => 0,
                '9' => 0, '10' => 0, '11' => 0, '12' => 0,
            ];
            foreach ($siteData as $month => $weekData) {
                $weeklyData[$month] = isset($weekData['total_target']) ? $weekData['total_target'] : 0;
            }
            $grandTotal = $grandTotalByProgram[$siteName];
            if ($grandTotal != 0) {
                $mappedB2Classes[] = [
                    'Site' => $siteName,
                    'January' => $weeklyData['1'] ?: '',
                    'February' => $weeklyData['2'] ?: '',
                    'March' => $weeklyData['3'] ?: '',
                    'April' => $weeklyData['4'] ?: '',
                    'May' => $weeklyData['5'] ?: '',
                    'June' => $weeklyData['6'] ?: '',
                    'July' => $weeklyData['7'] ?: '',
                    'August' => $weeklyData['8'] ?: '',
                    'September' => $weeklyData['9'] ?: '',
                    'October' => $weeklyData['10'] ?: '',
                    'November' => $weeklyData['11'] ?: '',
                    'December' => $weeklyData['12'] ?: '',
                    'GrandTotalByProgram' => $grandTotal,
                ];
            }
        }

        $mappedB2Classes[] = [
            'Site' => 'Grand Total',
            'January' => $grandTotalByWeek['1'] ?: '',
            'February' => $grandTotalByWeek['2'] ?: '',
            'March' => $grandTotalByWeek['3'] ?: '',
            'April' => $grandTotalByWeek['4'] ?: '',
            'May' => $grandTotalByWeek['5'] ?: '',
            'June' => $grandTotalByWeek['6'] ?: '',
            'July' => $grandTotalByWeek['7'] ?: '',
            'August' => $grandTotalByWeek['8'] ?: '',
            'September' => $grandTotalByWeek['9'] ?: '',
            'October' => $grandTotalByWeek['10'] ?: '',
            'November' => $grandTotalByWeek['11'] ?: '',
            'December' => $grandTotalByWeek['12'] ?: '',
            'GrandTotalByProgram' => $grandTotalForAllPrograms,
        ];

        $response = ['data' => $mappedB2Classes];

        return response()->json($response);
    }
    public function retrieveB2DataForEmailGuatemala(Request $request)
    {
        $programs = Site::where('is_active', 1)->where('country', 'Guatemala')->get();
        $year = 2024;
        $dateRanges = DateRange::select('month_num')->where('year', $year)->groupBy('month_num')->get();
        $groupedClasses = [];
        $grandTotalByWeek = [];
        $grandTotalByWeek2 = [];
        $grandTotalByProgram = [];
        $grandTotalByProgram2 = []; // Declare $b2percentage array

        foreach ($programs as $program) {
            $siteName = $program->name;
            if (!isset($grandTotalByProgram[$siteName])) {
                $grandTotalByProgram[$siteName] = 0;
            }
            if (!isset($grandTotalByProgram2[$siteName])) {
                $grandTotalByProgram2[$siteName] = 0;
            }
            foreach ($dateRanges as $dateRange) {
                $month = $dateRange->month_num;
                $classes = Classes::with('site', 'program', 'dateRange', 'createdByUser', 'updatedByUser')
                    ->whereHas('dateRange', function ($subquery) use ($month, $year) {
                        $subquery->where('month_num', $month)->where('year', $year);
                    })
                    ->whereHas('program', function ($subquery) {
                        $subquery->where('is_active', 1)->where('program_type', 'B2')->orWhere('program_type', 'COMCAST');
                    })
                    ->where('site_id', $program->id)
                    ->where('status', 'Active')
                    ->get();
                $classes2 = Classes::with('site', 'program', 'dateRange', 'createdByUser', 'updatedByUser')
                    ->whereHas('dateRange', function ($subquery) use ($month, $year) {
                        $subquery->where('month_num', $month)->where('year', $year);
                    })
                    ->whereHas('program', function ($subquery) {
                        $subquery->where('is_active', 1);
                    })
                    ->where('site_id', $program->id)
                    ->where('status', 'Active')
                    ->get();
                $totalTarget = $classes->sum('total_target');
                $totalTarget2 = $classes2->sum('total_target');

                if (!isset($grandTotalByWeek[$month])) {
                    $grandTotalByWeek[$month] = 0;
                }
                if (!isset($grandTotalByWeek2[$month])) {
                    $grandTotalByWeek2[$month] = 0;
                }

                $grandTotalByWeek[$month] += $totalTarget;
                $grandTotalByWeek2[$month] += $totalTarget2;
                $grandTotalByProgram[$siteName] += $totalTarget;

                $grandTotalByProgram2[$siteName] += $totalTarget2;

                if (!isset($groupedClasses[$siteName][$month])) {
                    $groupedClasses[$siteName][$month] = ['total_target' => 0];
                }
                $groupedClasses[$siteName][$month]['total_target'] += $totalTarget;
            }
        }
        $grandTotalForAllPrograms = array_sum($grandTotalByProgram);
        $grandTotalForAllPrograms2 = array_sum($grandTotalByProgram2);

        $mappedB2Classes = [];
        $mappedB2Classes[] = [
            'Site' => 'B2 Percentage',
            'January' => $grandTotalByWeek2['1'] != 0 ? round(($grandTotalByWeek['1'] / $grandTotalByWeek2['1']) * 100, 2) . '%' : '0%',
            'February' => $grandTotalByWeek2['2'] != 0 ? round(($grandTotalByWeek['2'] / $grandTotalByWeek2['2']) * 100, 2) . '%' : '0%',
            'March' => $grandTotalByWeek2['3'] != 0 ? round(($grandTotalByWeek['3'] / $grandTotalByWeek2['3']) * 100, 2) . '%' : '0%',
            'April' => $grandTotalByWeek2['4'] != 0 ? round(($grandTotalByWeek['4'] / $grandTotalByWeek2['4']) * 100, 2) . '%' : '0%',
            'May' => $grandTotalByWeek2['5'] != 0 ? round(($grandTotalByWeek['5'] / $grandTotalByWeek2['5']) * 100, 2) . '%' : '0%',
            'June' => $grandTotalByWeek2['6'] != 0 ? round(($grandTotalByWeek['6'] / $grandTotalByWeek2['6']) * 100, 2) . '%' : '0%',
            'July' => $grandTotalByWeek2['7'] != 0 ? round(($grandTotalByWeek['7'] / $grandTotalByWeek2['7']) * 100, 2) . '%' : '0%',
            'August' => $grandTotalByWeek2['8'] != 0 ? round(($grandTotalByWeek['8'] / $grandTotalByWeek2['8']) * 100, 2) . '%' : '0%',
            'September' => $grandTotalByWeek2['9'] != 0 ? round(($grandTotalByWeek['9'] / $grandTotalByWeek2['9']) * 100, 2) . '%' : '0%',
            'October' => $grandTotalByWeek2['10'] != 0 ? round(($grandTotalByWeek['10'] / $grandTotalByWeek2['10']) * 100, 2) . '%' : '0%',
            'November' => $grandTotalByWeek2['11'] != 0 ? round(($grandTotalByWeek['11'] / $grandTotalByWeek2['11']) * 100, 2) . '%' : '0%',
            'December' => $grandTotalByWeek2['12'] != 0 ? round(($grandTotalByWeek['12'] / $grandTotalByWeek2['12']) * 100, 2) . '%' : '0%',
            'GrandTotalByProgram' => $grandTotalForAllPrograms2 != 0 ? round(($grandTotalForAllPrograms / $grandTotalForAllPrograms2) * 100, 2) . '%' : '0%',

        ];
        foreach ($groupedClasses as $siteName => $siteData) {
            $weeklyData = [
                '1' => 0, '2' => 0, '3' => 0, '4' => 0, '5' => 0, '6' => 0, '7' => 0, '8' => 0,
                '9' => 0, '10' => 0, '11' => 0, '12' => 0,
            ];
            foreach ($siteData as $month => $weekData) {
                $weeklyData[$month] = isset($weekData['total_target']) ? $weekData['total_target'] : 0;
            }
            $grandTotal = $grandTotalByProgram[$siteName];
            if ($grandTotal != 0) {
                $mappedB2Classes[] = [
                    'Site' => $siteName,
                    'January' => $weeklyData['1'] ?: '',
                    'February' => $weeklyData['2'] ?: '',
                    'March' => $weeklyData['3'] ?: '',
                    'April' => $weeklyData['4'] ?: '',
                    'May' => $weeklyData['5'] ?: '',
                    'June' => $weeklyData['6'] ?: '',
                    'July' => $weeklyData['7'] ?: '',
                    'August' => $weeklyData['8'] ?: '',
                    'September' => $weeklyData['9'] ?: '',
                    'October' => $weeklyData['10'] ?: '',
                    'November' => $weeklyData['11'] ?: '',
                    'December' => $weeklyData['12'] ?: '',
                    'GrandTotalByProgram' => $grandTotal,
                ];
            }
        }

        $mappedB2Classes[] = [
            'Site' => 'Grand Total',
            'January' => $grandTotalByWeek['1'] ?: '',
            'February' => $grandTotalByWeek['2'] ?: '',
            'March' => $grandTotalByWeek['3'] ?: '',
            'April' => $grandTotalByWeek['4'] ?: '',
            'May' => $grandTotalByWeek['5'] ?: '',
            'June' => $grandTotalByWeek['6'] ?: '',
            'July' => $grandTotalByWeek['7'] ?: '',
            'August' => $grandTotalByWeek['8'] ?: '',
            'September' => $grandTotalByWeek['9'] ?: '',
            'October' => $grandTotalByWeek['10'] ?: '',
            'November' => $grandTotalByWeek['11'] ?: '',
            'December' => $grandTotalByWeek['12'] ?: '',
            'GrandTotalByProgram' => $grandTotalForAllPrograms,
        ];

        $response = ['data' => $mappedB2Classes];

        return response()->json($response);
    }
    //For Web Routes
    public function WebDashboardSiteClasses(Request $request, CapEmailController $emailController)
    {
        $mappedGroupedClasses = $emailController->retrieveDataForEmail();

        return view('email.view', compact('mappedGroupedClasses'));
    }
    public function WebDashboardExternalClasses(Request $request, CapEmailController $emailController)
    {
        $mappedGroupedClasses = $emailController->retrieveExternalForEmail();

        return view('email.view', compact('mappedExternalClasses'));
    }
    public function WebDashboardInternalClasses(Request $request, CapEmailController $emailController)
    {
        $mappedGroupedClasses = $emailController->retrieveInternalForEmail();

        return view('email.view', compact('mappedInternalClasses'));
    }

    public function WebDashboardClasses(Request $request, CapEmailController $emailController)
    {
        $mappedClasses = $emailController->retrieveDataForClassesEmail();

        return view('email.view', compact('mappedClasses'));
    }
    public function WebDashboardB2Classes(Request $request, CapEmailController $emailController)
    {
        $mappedB2Classes = $emailController->retrieveB2DataForEmail();

        return view('email.view', compact('mappedB2Classes'));
    }

    public function AutomatedSr(Request $request, CapEmailController $emailController)
    {
        $mappedResult = $emailController->srComplianceExport();

        return view('sr_pending_email.view', compact('mappedResult'));
    }
    public function AutomatedSrExportData(Request $request, CapEmailController $emailController)
    {

        $formattedResult = $emailController->AutomatedSrExport();

        return view('sr_pending_email.view', compact('formattedResult'));
    }
}
