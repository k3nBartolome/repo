<?php

namespace App\Http\Controllers;

use App\Models\Program;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProgramController extends Controller
{
    public function index()
    {
        return Program::all();
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
            'att_tagging' => 'required',
            'user_id' => 'required|exists:users,id',
            'site_id' => 'required|exists:sites,id',
            'is_active' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $program = Program::create($request->all());
        return response()->json($program, 201);
    }

    public function update(Request $request, Program $program)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|max:255',
            'description' => 'sometimes|required',
            'att_tagging' => 'sometimes|required',
            'user_id' => 'sometimes|required|exists:users,id',
            'site_id' => 'sometimes|required|exists:sites,id',
            'is_active' => 'sometimes|required|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $program->update($request->all());
        return response()->json($program);
    }

    public function delete(Program $program)
    {
        $program->delete();

        return response()->json(null, 204);
    }
}
