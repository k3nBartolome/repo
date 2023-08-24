<?php

namespace App\Http\Controllers;

use App\Models\Award;
use Illuminate\Http\Request;

class AwardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function awardedNormal()
    {
        $awarded = Award::with(['site', 'items', 'processedBy', 'releasedBy'])
            ->where('award_status', 'Awarded')
            ->whereHas('items', function ($query) {
                $query->whereIn('category', ['Normal']);
            })
            ->get();

        foreach ($awarded as $award) {
            $award->image_path = asset('storage/' . $award->path);
        }

        return response()->json(['awarded' => $awarded]);
    }

    public function awardedPremium()
    {
        $awarded = Award::with(['site', 'items', 'processedBy', 'releasedBy'])
            ->where('award_status', 'Awarded')
            ->whereHas('items', function ($query) {
                $query->whereIn('category', ['Premium']);
            })
            ->get();

        return response()->json(['awarded' => $awarded]);
    }
    public function awardedBoth()
    {
        $awarded = Award::with(['site', 'items', 'processedBy', 'releasedBy'])
            ->where('award_status', 'Awarded')
            ->whereHas('items', function ($query) {
                $query->whereIn('category', ['Normal', 'Premium']);
            })
            ->get();

        return response()->json(['awarded' => $awarded]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
    }

    /**
     * Update the specified resource in storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
    }
}
