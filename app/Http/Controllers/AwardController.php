<?php

namespace App\Http\Controllers;

use App\Models\Award;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

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

        foreach ($awarded as $award) {
            $award->image_path = asset('storage/' . $award->path);
        }

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

        foreach ($awarded as $award) {
            $award->image_path = asset('storage/' . $award->path);
        }

        return response()->json(['awarded' => $awarded]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    /**
     * Display the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $awarded = Award::with(['site', 'items', 'processedBy', 'releasedBy'])->find($id);
    
        if (!$awarded) {
            return response()->json(['error' => 'Item not found'], 404);
        }
    
        $awarded->image_path = asset('storage/' . $awarded->path);
    
        return response()->json([
            'item' => $awarded,
        ]);
    }
    public function update(Request $request, $id)
    {
        // Validate the incoming request data
        $validator = Validator::make($request->all(), [
            'awardee_name' => 'required',
            'awardee_hrid' => 'required',
            'remarks' => 'required',
            'file_name' => 'required|image|mimes:jpeg,png,jpg,gif,jfif',
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        try {
            // Begin a database transaction
            DB::beginTransaction();

            // Find the award by ID
            $award = Award::find($id);

            // Check if the award with the provided ID exists
            if (!$award) {
                return response()->json(['error' => 'Item not found'], 404);
            }

            // Store the uploaded image
            $imagePath = $request->file('file_name')->store('storage', 'public');

            // Update the award attributes
            $award->fill($request->all());
            $award->path = $imagePath; // Update the image path
            $award->save();

            // Commit the transaction
            DB::commit();

            // Return a success response
            return response()->json([
                'message' => 'Item updated successfully.',
                'award' => $award,
            ]);
        } catch (\Exception $e) {
            // If an error occurs, rollback the transaction
            DB::rollback();

            // Return an error response
            return response()->json(['error' => 'An error occurred while updating the item.'], 500);
        }
    }



    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */


    /**
     * Update the specified resource in storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */


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
