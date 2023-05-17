<?php

namespace App\Http\Controllers;

use App\Http\Resources\SiteResource;
use App\Models\Site;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class SiteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sites = Site::with(['created_by', 'updatedByUser'])->get();

        return response()->json(['data' => $sites]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|unique:sites',
            'description' => 'required',
            'site_director' => 'required',
            'region' => 'required',
            'is_active' => Rule::in(['0', '1']),
            'created_by' => 'nullable',
        ]);

        $site = Site::create($validatedData);

        return new SiteResource($site);
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $site = Site::FindOrFail($id);
        return new SiteResource($site);
    }

    /**
     * Update the specified resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|unique:sites,name,' . $id,
            'description' => 'sometimes',
            'site_director' => 'sometimes',
            'region' => 'sometimes',
            'updated_by' => 'nullable',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $site = Site::find($id);
        if (!$site) {
            return response()->json(['error' => 'Site not found'], 404);
        }

        $site->fill($request->all());
        $site->save();
        return new SiteResource($site);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $site = Site::find($id);

        if (!$site) {
            return response()->json(['error' => 'Site not found'], 404);
        }

        $site->delete();

        return response()->json(null, 204);
    }

}
