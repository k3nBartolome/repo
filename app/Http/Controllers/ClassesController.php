<?php

namespace App\Http\Controllers;

use App\Http\Resources\ClassesResource;
use App\Models\Classes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ClassesController extends Controller
{
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

        // Return the updated class as a resource.
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
        $classes = Classes::with([
            'site' => function ($query) {
                $query->where('country', 'Philippines');
            },
            'program',
            'dateRange',
            'createdByUser',
            'updatedByUser',
        ])
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
