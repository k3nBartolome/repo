<?php

namespace App\Http\Controllers;

use App\Models\Program;
use Illuminate\Http\Request;

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
        $program = Program::create($request->all());

        return response()->json($program, 201);
    }

    public function update(Request $request, Program $program)
    {
        $program->update($request->all());

        return response()->json($program, 200);
    }

    public function delete(Program $program)
    {
        $program->delete();

        return response()->json(null, 204);
    }
}
