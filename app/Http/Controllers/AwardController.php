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
        return $this->getAwardedItemsByCategory('Normal');
    }

    public function awardedPremium()
    {
        return $this->getAwardedItemsByCategory('Premium');
    }

    private function getAwardedItemsByCategory($category)
    {
        $awarded = Award::with(['site', 'items', 'processedBy', 'releasedBy'])
            ->where('award_status', 'Awarded')
            ->whereHas('items', function ($query) use ($category) {
                $query->whereIn('category', [$category]);
            })
            ->get();

        $awarded->each(function ($award) {
            $award->image_path = $this->getImagePath($award);
        });

        return response()->json(['awarded' => $awarded]);
    }


    public function awardedBoth()
    {
        $awarded = $this->getAwardedItems();

        $awarded->each(function ($award) {
            $award->image_path = $this->getImagePath($award);
        });

        return response()->json(['awarded' => $awarded]);
    }

    private function getAwardedItems()
    {
        return Award::with(['site', 'items', 'processedBy', 'releasedBy'])
            ->where('award_status', 'Awarded')
            ->whereHas('items', function ($query) {
                $query->whereIn('category', ['Normal', 'Premium']);
            })
            ->get();
    }

    private function getImagePath($award)
    {
        return asset('storage/' . $award->path);
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
        $validator = Validator::make(
            $request->all(),
            [
                'awardee_name' => 'sometimes',
                'awardee_hrid' => 'sometimes',
                'remarks' => 'sometimes',
                'file_name' => $request->hasFile('file_name') ? 'image|mimes:jpeg,png,jpg,gif' : '',
            ]
        );


        if ($validator->fails()) {
            return $validator->errors();
        }

        try {
            DB::beginTransaction();

            $award = Award::find($id);
            $award->fill($request->all());

            if ($request->hasFile('file_name')) {
                $imagePath = $request->file('file_name')->store('storage', 'public');
                $award->path = $imagePath;
            }

            $award->save();

            DB::commit();

            return response()->json([
                'message' => 'Item updated successfully.',
                'award' => $award,
            ]);
        } catch (\Exception $e) {
            DB::rollback();

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
