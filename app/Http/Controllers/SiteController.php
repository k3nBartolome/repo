<?php

namespace App\Http\Controllers;

use App\Http\Resources\SiteResource;
use App\Models\Site;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        $sites = Site::with('createdBy')->get();

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
    public function show(Site $site)
    {
        return new SiteResource($site);
    }

    /**
     * Update the specified resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Site $site)
    {
        $validatedData = $request->validate([
            'name' => 'sometimes|unique:sites,name,'.$site->id,
            'description' => 'sometimes',
            'site_director' => 'sometimes',
            'region' => 'sometimes',
            'is_active' => Rule::in(['0', '1']),
        ]);

        $validatedData['updated_by'] = auth()->user()->id;

        $site->update($validatedData);

        return new SiteResource($site);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Site $site)
    {
        $site->delete();

        return response()->json(null, 204);
    }
}
