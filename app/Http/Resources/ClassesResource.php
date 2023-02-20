<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;


class ClassesResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'notice_weeks' => $this->notice_weeks,
            'notice_days' => $this->notice_days,
            'external_target' => $this->external_target,
            'internal_target' => $this->internal_target,
            'total_target' => $this->total_target,
            'type_of_hiring' => $this->type_of_hiring,
            'within_sla' => $this->within_sla,
            'with_erf' => $this->with_erf,
            'remarks' => $this->remarks,
            'status' => $this->status,
            'approved_status' => $this->approved_status,
            'original_start_date' => $this->original_start_date,
            'wfm_date_requested' => $this->wfm_date_requested,
            'program_id' => $this->program_id,
            'site_id' => $this->site_id,
            'created_by' => $this->created_by,
            'is_active' => $this->is_active,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'reason' => $this->whenLoaded('reason', function () {
                return $this->sla_reason->where('class_id', $this->id)->first()->reason;
            }),
        ];
    }
}
