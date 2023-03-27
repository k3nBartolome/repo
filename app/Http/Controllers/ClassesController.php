<?php

namespace App\Http\Controllers;

use App\Http\Resources\ClassesResource;
use App\Models\Classes;
use App\Models\Program;
use App\Models\Site;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ClassesController extends Controller
{
    public function index()
    {
        $classes = Classes::with(['sla_reason', 'site', 'program', 'dateRange', 'createdByUser', 'updatedByUser'])->get();
        $classesData = ClassesResource::collection($classes);

        return response()->json([
            'classes' => $classesData,
        ]);
    }

    public function classesAll()
    {
        $siteIds = Classes::distinct()->pluck('site_id')->toArray();
        $programIds = Classes::distinct()->pluck('program_id')->toArray();

        $response = [];

        foreach ($siteIds as $siteId) {
            foreach ($programIds as $programId) {
                $site = Site::find($siteId)->name;
                $program = Program::find($programId)->name;
                $key = 'classes_'.$site.'_'.$program;

                $classes = Classes::with(['sla_reason', 'site', 'program', 'dateRange'])
                     ->where('site_id', $siteId)
                     ->where('program_id', $programId)
                     ->where('status', 'Active')
                     ->selectRaw('MAX(id) as id')
                     ->groupBy('pushedback_id')
                     ->get();

                $classIds = $classes->pluck('id');

                $groupedData = Classes::with(['sla_reason', 'site', 'program', 'dateRange'])
                     ->whereIn('id', $classIds)
                     ->get()
                     ->groupBy(function ($class) {
                         return $class->dateRange->month;
                     })
                     ->map(function ($monthClasses) {
                         return $monthClasses->sortBy('date_range_id')->map(function ($class) {
                             return [
                                 'id' => $class->id,
                                 'site_name' => $class->site->name,
                                 'program_name' => $class->program->name,
                                 'date_range' => $class->dateRange->date_range,
                                 'total_target' => $class->total_target,
                             ];
                         })->values();
                     })
                     ->toArray();

                $response[$key] = $groupedData;
            }
        }

        return response()->json($response);
    }

    public function store(Request $request, $id)
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
            'is_active' => 'required',
            'date_range_id' => 'required',
            'category' => 'required',
            'condition' => 'required',
            'approved_by' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        // Validate the ID parameter.
        if (!is_numeric($id) || $id < 0) {
            return response()->json(['error' => 'Invalid ID.'], 400);
        }

        // Get the class by ID.
        $class = Classes::find($id);
        if (!$class) {
            return response()->json(['error' => 'Record not found.'], 404);
        }
        $condition = [$request->condition];

        // Update the class with the request data.
        $class->fill($request->all());
        $class->condition = json_encode($condition);
        $class->agreed_start_date = $request->input('original_start_date');
        $class->changes = 'Add Class';
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
        $class = Classes::with(['sla_reason', 'site', 'program', 'dateRange', 'createdByUser', 'updatedByUser'])->find($id);

        if (!$class) {
            return response()->json(['error' => 'Class not found'], 404);
        }

        return response()->json([
        'class' => $class,
    ]);
    }

    public function transaction($id)
    {
        $class = Classes::with(['sla_reason', 'site', 'program', 'dateRange', 'createdByUser', 'updatedByUser'])->find($id);

        if (!$class) {
            return response()->json(['error' => 'Class not found'], 404);
        }

        $classes = Classes::with(['sla_reason', 'site', 'program', 'dateRange', 'createdByUser', 'updatedByUser'])
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
        'is_active' => 'required',
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

        $class = Classes::find($id);

        $newClass = $class->replicate();
        $newClass->site_id = $class->site_id;
        $newClass->program_id = $class->program_id;
        $newClass->date_range_id = $class->date_range_id;
        $newClass->fill($request->all());
        $newClass->notice_weeks = null;
        $newClass->notice_days = null;
        $newClass->external_target = null;
        $newClass->internal_target = null;
        $newClass->total_target = 0;
        $newClass->type_of_hiring = null;
        $newClass->within_sla = null;
        $newClass->with_erf = null;
        $newClass->erf_number = null;
        $newClass->remarks = null;
        $newClass->condition = null;
        $newClass->approved_by = null;
        $newClass->requested_by = null;
        $newClass->status = 1;
        $newClass->approved_status = null;
        $newClass->original_start_date = null;
        $newClass->wfm_date_requested = null;
        $newClass->is_active = null;
        $newClass->category = null;
        $newClass->agreed_start_date = null;
        $newClass->updated_by = null;
        $newClass->changes = null;
        $newClass->updated_at = null;
        $newClass->created_by = null;
        $newClass->created_at = null;
        $newClass->cancelled_by = null;
        $newClass->cancelled_date = null;
        $newClass->update_status = null;
        $newClass->changes = null;
        $newClass->wf = null;
        $newClass->ta = null;
        $newClass->op = null;
        $newClass->cl = null;
        $newClass->tr = null;
        $newClass->save();

        $newClass->pushedback_id = $newClass->id;
        $newClass->save();

        return new ClassesResource($newClass);
    }
}
