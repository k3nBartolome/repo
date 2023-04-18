<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ClassesResource extends JsonResource
{
    public function toArray($request)
    {

        return [
            'id' => $this->id,
            'pushedback_id' => $this->pushedback_id,
            'site_name' => $this->site->name,
            'program_name' => $this->program->name,
            'type_of_hiring' => $this->type_of_hiring,
            'external_target' => $this->external_target,
            'internal_target' => $this->internal_target,
            'total_target' => $this->total_target,
            'original_start_date' => $this->original_start_date,
            'wfm_date_requested' => $this->wfm_date_requested,
            'notice_days' => $this->notice_days,
            'notice_weeks' => $this->notice_weeks,
            'date_range' => $dateRange,
            'growth' => $this->growth,
            'backfill' => $this->backfill,
            'with_erf' => $this->with_erf,
            'category' => $this->category,
            'remarks' => $this->remarks,
            'within_sla' => $this->within_sla,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'created_by' => $this->createdByUser ? $this->createdByUser->name : null,
            'updated_by' => $this->updatedByUser ? $this->createdByUser->name : null,
            'is_active' => $this->is_active,
            'approved_status' => $this->approved_status,
            'status' => $this->status,
            'update_status' => $this->update_status,
        ];
    }
}
