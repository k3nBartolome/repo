<?php

namespace App\Http\Controllers;

use App\Exports\MyExport;
use App\Http\Resources\ClassesResource;
use App\Models\Classes;
use App\Models\ClassStaffing;
use App\Models\DateRange;
use App\Models\Program;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
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
                $query->where('LastName', 'LIKE', '%'.$filterLastName.'%');
            }
        }

        if ($request->has('filter_firstname')) {
            $filterFirstName = $request->input('filter_firstname');

            if (!empty($filterFirstName)) {
                $query->where('FirstName', 'LIKE', '%'.$filterFirstName.'%');
            }
        }

        if ($request->has('filter_middlename')) {
            $filterMiddleName = $request->input('filter_middlename');

            if (!empty($filterMiddleName)) {
                $query->where('MiddleName', 'LIKE', '%'.$filterMiddleName.'%');
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

        if ($request->has('filter_middlename')) {
            $filterMiddleName = $request->input('filter_middlename');

            if (!empty($filterMiddleName)) {
                $query->where('MiddleName', 'LIKE', '%'.$filterMiddleName.'%');
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

    public function dashboardClasses()
{
    $programs = Program::with('site')->get();
    $dateRanges = DateRange::all();

    $groupedClasses = [];
    $grandTotalByMonth = [];
    $grandTotalByProgram = [];

    foreach ($programs as $program) {
        $siteName = $program->site->name;
        $programName = $program->name;

        // Initialize grand total for the program
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

    logger('Grouped Classes:', $groupedClasses);
    logger('Grand Total By Month:', $grandTotalByMonth);
    logger('Grand Total By Program:', $grandTotalByProgram);

    $response = [
        'classes' => $groupedClasses,
        'grandTotal' => $grandTotalByMonth,
        'grandTotal2' => $grandTotalByProgram,
    ];

    return response()->json($response);
}

    

    public function classesallInd()
    {
        $classes = Classes::whereHas('site', function ($query) {
            $query->where('country', '=', 'India');
        })
            ->with('site', 'program', 'dateRange', 'createdByUser', 'updatedByUser')
            ->where('status', 'Active')
            ->get();

        return response()->json([
            'classes' => $classes,
        ]);
    }

    public function classesallJam()
    {
        $classes = Classes::whereHas('site', function ($query) {
            $query->where('country', '=', 'Jamaica');
        })
            ->with('site', 'program', 'dateRange', 'createdByUser', 'updatedByUser')
            ->where('status', 'Active')
            ->get();

        return response()->json([
            'classes' => $classes,
        ]);
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
        $class->status = 'Cancelled';
        $class->save();
        $newClass = $class->replicate();
        $newClass->cancelled_by = json_encode($cancelled_by);
        $newClass->changes = 'Cancellation';
        $newClass->cancelled_date = $request->input('cancelled_date');
        $newClass->save();

        return new ClassesResource($class);
    }
}
