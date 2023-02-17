<?php

namespace App\Http\Controllers;
use App\Http\Resources\ProgramResource;
use App\Models\Program;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProgramController extends Controller
{
    public function index()
    {
        return ProgramResource::collection(Program::all());
    }

    public function show(Program $program)
    {
        return $program;
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'description' => 'required',
            'program_group' => 'required',
            'site_id' => 'required|exists:sites,id',
            'is_active' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $program = Program::create($request->all());
        return new ProgramResource($program);
    }

    public function update(Request $request, Program $program)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|max:255',
            'description' => 'sometimes|required',
            'program_group' => 'sometimes|required',
            'site_id' => 'sometimes|required|exists:sites,id',
            'is_active' => Rule::in(['0', '1']),
            'created_by'=> 'nullable'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $program->update($request->all());
        return new ProgramResource($program);
    }

    public function delete(Program $program)
    {
        $program->delete();

        return response()->json(null, 204);
    }
}
