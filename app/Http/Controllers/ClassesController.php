<?php

namespace App\Http\Controllers;

use App\Http\Resources\ClassesResource;
use App\Models\Classes;
use App\Models\ClassStaffing;
use App\Models\DateRange;
use App\Models\Program;
use App\Notifications\ClassNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Validator;

class ClassesController extends Controller
{
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

        $customEmail = 'padillakryss@gmail.com';

        Notification::route('mail', $customEmail)->notify(new ClassNotification($customEmail));

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
        $programs = Program::all();
        $dateRanges = DateRange::all();

        $classes = [];
        foreach ($programs as $program) {
            $programClasses = [];
            foreach ($dateRanges as $dateRange) {
                $class = Classes::where('site_id', $program->site_id)
                ->where('program_id', $program->id)
                ->where('date_range_id', $dateRange->id)
                ->where('status', 'Active')
                ->first();
                $totalTarget = $class ? $class->total_target : 0;
                $programClasses[] = [
                'date_range_id' => $dateRange->id,
                'class_id' => $class ? $class->id : 0,
                'date_range' => $dateRange->date_range,
                'month' => $dateRange->month,
                'total_target' => $totalTarget,
            ];
            }
            $classes[] = [
            'site_id' => $program->site_id,
            'site_name' => $program->site->name,
            'program_name' => $program->name,
            'program_id' => $program->id,
            'classes' => $programClasses,
        ];
        }

        $groupedClasses = [];

        foreach ($classes as $class) {
            $siteId = $class['site_name'];
            $siteName = $class['site_id'];
            $programName = $class['program_name'];
            $programId = $class['program_id'];

            if (!isset($groupedClasses[$siteId][$programName])) {
                $groupedClasses[$siteId][$programName] = [
                'date_ranges' => [],
            ];
            }

            $dateRanges = $class['classes'];

            foreach ($dateRanges as $dateRange) {
                $dateRangeName = $dateRange['date_range'];
                $dateRangeMonth = $dateRange['month'];
                $totalTarget = $dateRange['total_target'];
                $dateRangeId = $dateRange['date_range_id'];
                $classId = $dateRange['class_id'];

                if (!isset($groupedClasses[$siteId][$programName]['date_ranges'][$dateRangeMonth])) {
                    $groupedClasses[$siteId][$programName]['date_ranges'][$dateRangeMonth] = [
                    'total_target' => 0,
                    'program_id' => $programId,
                    'month' => $dateRangeMonth,
                    'date_ranges' => [], // Initialize date_ranges array
                ];
                }

                $groupedClasses[$siteId][$programName]['date_ranges'][$dateRangeMonth]['total_target'] += $totalTarget;

                // Add the date_range data to the date_ranges array
                $groupedClasses[$siteId][$programName]['date_ranges'][$dateRangeMonth]['date_ranges'][] = [
                'date_range_id' => $dateRangeId,
                'class_id' => $classId,
                'date_range' => $dateRangeName,
                'total_target' => $totalTarget,
            ];
            }
        }

        // Calculate the total target for each month in a program at a site
        foreach ($groupedClasses as &$siteData) {
            foreach ($siteData as &$programData) {
                foreach ($programData['date_ranges'] as $month => &$monthData) {
                    $totalTarget = 0;
                    foreach ($monthData['date_ranges'] as $dateRange) {
                        $totalTarget += $dateRange['total_target'];
                    }
                    $monthData['total_target'] = $totalTarget;
                }
            }
        }

        return response()->json([
        'classes' => $groupedClasses,
    ]);
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
