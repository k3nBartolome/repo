<?php

namespace App\Http\Controllers;

use App\Http\Resources\ClassesAllResource;
use App\Http\Resources\ClassesResource;
use App\Models\Classes;
use App\Models\DateRange;
use App\Models\Program;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;

class ClassesController extends Controller
{
    public function classesAll()
    {
        $cacheKey = 'classesAll';
        $cacheTime = 60;

        if (Cache::has($cacheKey)) {
            $classes = Cache::get($cacheKey);
        } else {
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
            Cache::put($cacheKey, $classes, $cacheTime);
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
                    'total_target' => 0,
                ];
            }

            $dateRanges = $class['classes'];

            foreach ($dateRanges as $dateRange) {
                $dateRangeName = $dateRange['date_range'];
                $totalTarget = $dateRange['total_target'];
                $dateRangeId = $dateRange['date_range_id'];
                $classId = $dateRange['class_id'];

                if (!isset($groupedClasses[$siteId][$programName]['date_ranges'][$dateRangeName])) {
                    $groupedClasses[$siteId][$programName]['date_ranges'][$dateRangeName] = [
                        'total_target' => 0,
                        'program_id' => $programId,
                        'date_range_id' => $dateRangeId,
                        'date_range' => $dateRangeName,
                        'site_id' => $siteName,
                        'class_id' => $classId,
                    ];
                }

                $groupedClasses[$siteId][$programName]['date_ranges'][$dateRangeName]['total_target'] += $totalTarget;
            }
        }

        return new ClassesAllResource($groupedClasses);
    }

    public function store(Request $request)
    {
        // Validate the request.
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
            'condition' => 'required',
            'approved_by' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $class = new Classes(); // replace `YourClass` with the actual name of your class
        $condition = [$request->condition];
        $class->fill($request->all());
        $class->condition = json_encode($condition);
        $class->agreed_start_date = $request->input('original_start_date');
        $class->changes = 'Add Class';
        $class->save();
        $class->pushedback_id = $class->id;
        $class->save();

        // Return the updated class as a resource.
        return new ClassesResource($class);
    }

    public function destroy(Classes $class)
    {
        $class->delete();

        return response(null, 204);
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
        'condition' => 'required',
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
        $condition = [$request->condition];
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $class = Classes::find($id);
        $class->status = 'Moved';
        $class->save();
        $newClass = $class->replicate();
        $newClass->update_status = $class->update_status + 1;
        $newClass->requested_by = json_encode($requested_by);
        $newClass->condition = json_encode($condition);
        $newClass->fill($request->all());
        $newClass->save();

        return new ClassesResource($newClass);
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
        $condition = [$request->condition];

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $class = Classes::find($id);

        // Update old class data
        $class->status = 'Cancelled';
        $class->changes = 'Cancelled';
        $class->cancelled_by = json_encode($cancelled_by);
        $class->condition = json_encode($condition);
        $class->cancelled_date = $request->input('cancelled_date');
        $class->save();

        return new ClassesResource($class);
    }
}
