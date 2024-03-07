<?php

namespace App\Http\Controllers;

use App\Models\Program;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ProgramController extends Controller
{
    public function index()
    {
        $programs = Program::with(['site', 'user', 'createdByUser', 'updatedByUser'])
            ->whereHas('site', function ($query) {
                $query->where('country', 'Philippines');
            })
            ->where('is_active', 1)
            ->get();

        return response()->json(['data' => $programs]);
    }

    public function index2()
    {
        $programs = Program::with(['site', 'user', 'createdByUser', 'updatedByUser'])
            ->whereHas('site', function ($query) {
                $query->where('country', 'Philippines');
            })
            ->where('is_active', 0)
            ->get();

        return response()->json(['data' => $programs]);
    }

    public function index3()
    {
        $programs = Program::with(['site', 'user', 'createdByUser', 'updatedByUser'])
            ->whereHas('site', function ($query) {
                $query->where('country', 'India');
            })
            ->where('is_active', 1)
            ->get();

        return response()->json(['data' => $programs]);
    }

    public function index4()
    {
        $programs = Program::with(['site', 'user', 'createdByUser', 'updatedByUser'])
            ->whereHas('site', function ($query) {
                $query->where('country', 'India');
            })
            ->where('is_active', 0)
            ->get();

        return response()->json(['data' => $programs]);
    }

    public function index5()
    {
        $programs = Program::with(['site', 'user', 'createdByUser', 'updatedByUser'])
            ->whereHas('site', function ($query) {
                $query->where('country', 'Jamaica');
            })
            ->where('is_active', 1)
            ->get();

        return response()->json(['data' => $programs]);
    }

    public function index6()
    {
        $programs = Program::with(['site', 'user', 'createdByUser', 'updatedByUser'])
            ->whereHas('site', function ($query) {
                $query->where('country', 'Jamaica');
            })
            ->where('is_active', 0)
            ->get();

        return response()->json(['data' => $programs]);
    }

    public function index7()
    {
        $programs = Program::with(['site', 'user', 'createdByUser', 'updatedByUser'])
            ->whereHas('site', function ($query) {
                $query->where('country', 'Guatemala');
            })
            ->where('is_active', 1)
            ->get();

        return response()->json(['data' => $programs]);
    }

    public function index8()
    {
        $programs = Program::with(['site', 'user', 'createdByUser', 'updatedByUser'])
            ->whereHas('site', function ($query) {
                $query->where('country', 'Guatemala');
            })
            ->where('is_active', 0)
            ->get();

        return response()->json(['data' => $programs]);
    }

    public function show($id)
    {
        $program = Program::FindOrFail($id);

        return response()->json(['data' => $program]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => [
                'required',
                Rule::unique('programs')->where(function ($query) use ($request) {
                    return $query->where('site_id', $request->input('site_id'));
                }),
            ],
            'description' => 'required',
            'program_group' => 'sometimes',
            'b2' => 'sometimes|boolean',
            'site_id' => 'required|exists:sites,id',
            'is_active' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $program = Program::create($request->all());

        return response()->json(['data' => $program], 201);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|unique:programs,name,' . $id . ',id,site_id,' . $request->input('site_id'),
            'description' => 'sometimes',
            'program_group' => 'sometimes',
            'b2' => 'sometimes|boolean',
            'site_id' => 'sometimes',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $program = Program::find($id);
        if (!$program) {
            return response()->json(['error' => 'Program not found'], 404);
        }

        $program->fill($request->all());
        $program->save();

        return response()->json(['data' => $program]);
    }

    public function destroy($id)
    {
        $program = Program::find($id);

        if (!$program) {
            return response()->json(['error' => 'Program not found'], 404);
        }

        $program->delete();

        return response()->json(null, 204);
    }

    public function indexBySite($siteId)
    {
        $programs = Program::where('site_id', $siteId)
            ->where('is_active', 1)
            ->get()->sortByDesc('name');

        return response()->json(['data' => $programs]);
    }

    public function perSite(Request $request, $siteId)
    {
        $siteIdsArray = explode(',', $siteId);

        $allPrograms = [];

        foreach ($siteIdsArray as $siteId) {
            $programs = Program::where('site_id', $siteId)
                ->where('is_active', 1)
                ->get()
                ->sortByDesc('name');

            $allPrograms = array_merge($allPrograms, $programs->toArray());
        }

        return response()->json(['data' => $allPrograms]);
    }

    public function deactivate(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'is_active' => 'sometimes|boolean', // Ensure is_active is a boolean field
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        // Find the program by ID
        $program = Program::find($id);

        if (!$program) {
            return response()->json(['error' => 'Program not found'], 404);
        }

        // Update program status to deactivate
        $program->is_active = false; // Assuming is_active is a boolean field

        // Save the updated program
        $program->save();

        return response()->json(['data' => $program]);
    }

    public function activate(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'is_active' => 'sometimes',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $program = Program::find($id);
        if (!$program) {
            return response()->json(['error' => 'Program not found'], 404);
        }

        $program->fill($request->all());
        $program->save();

        return response()->json(['data' => $program]);
    }
}
