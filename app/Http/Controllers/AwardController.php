<?php

namespace App\Http\Controllers;

use App\Models\Award;

class AwardController extends Controller
{
    private function getAwardedItemsByCategory($category)
    {
        $awarded = Award::with(['site', 'items', 'processedBy', 'releasedBy'])
            ->where('award_status', 'Awarded')
            ->whereHas('items', function ($query) use ($category) {
                $query->whereIn('category', [$category]);
            })
            ->get();
        $awarded = $awarded->map(function ($award) {
            return [
                'id' => $award->id,
                'award_status' => $award->award_status,
                'remarks' => $award->remarks,
                'date_released' => $award->date_released,
                'awardee_name' => $award->awardee_name,
                'awardee_hrid' => $award->awardee_hrid,
                'file_name' => $award->file_name,
                'path' => $award->path,
                'item_name' => $award->items->item_name,
                'budget_code' => $award->items->budget_code,
               'released_by' => $award->released_by ? $award->releasedBy->name : 'N/A',
                'site_name' => $award->site->name,
                'processed_by' => $award->processedBy ? $award->processedBy->name : 'N/A',
                'created_at' => $award->created_at,
                'updated_at' => $award->updated_at,
                'awarded_quantity' => $award->awarded_quantity,
                'image_path' => $this->getImagePath($award),
            ];
        });

        return response()->json(['awarded' => $awarded]);
    }

    private function getAwardedItems()
    {
        $awarded = Award::with(['site', 'items', 'processedBy', 'releasedBy'])
            ->where('award_status', 'Awarded')
            ->whereHas('items', function ($query) {
                $query->whereIn('category', ['Normal', 'Premium']);
            })
            ->get();
        $awarded = $awarded->map(function ($award) {
            return [
                'id' => $award->id,
                'award_status' => $award->award_status,
                'remarks' => $award->remarks,
                'date_released' => $award->date_released,
                'awardee_name' => $award->awardee_name,
                'awardee_hrid' => $award->awardee_hrid,
                'file_name' => $award->file_name,
                'path' => $award->path,
                'item_name' => $award->items->item_name,
                'budget_code' => $award->items->budget_code,
                'released_by' => $award->released_by ? $award->releasedBy->name : 'N/A',
                'site_name' => $award->site->name,
                'processed_by' => $award->processedBy ? $award->processedBy->name : 'N/A',
                'created_at' => $award->created_at,
                'updated_at' => $award->updated_at,
                'awarded_quantity' => $award->awarded_quantity,
                'image_path' => $this->getImagePath($award),
            ];
        });

        return response()->json(['awarded' => $awarded]);
    }

    public function awardedNormal()
    {
        return $this->getAwardedItemsByCategory('Normal');
    }

    public function awardedPremium()
    {
        return $this->getAwardedItemsByCategory('Premium');
    }

    public function awardedBoth()
    {
        return $this->getAwardedItems();
    }

    private function getImagePath($award)
    {
        return asset('storage/'.$award->path);
    }
}
