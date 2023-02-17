<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ClassesResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'program_id' => $this->program_id,
            'site_id' => $this->site_id,
            'type_of_hiring' => $this->type_of_hiring,
            'external_target' => $this->external_target,
            'internal_target' => $this->internal_target,
            'total_target' => $this->total_target,
            'delivery_date' => $this->delivery_date,
            'notice_days' => $this->notice_days,
            'notice_weeks' => $this->notice_weeks,
            'pipeline_utilized' => $this->pipeline_utilized,
            'with_erf' => $this->with_erf,
            'original_start_date' => $this->original_start_date,
            'supposed_start_date' => $this->supposed_start_date,
            'wf_requested_start_date' => $this->wf_requested_start_date,
            'ta_committed_start_date' => $this->ta_committed_start_date,
            'wfm_requested_date' => $this->wfm_requested_date,
            'within_sla' => $this->within_sla,
            'reason_for_counter_proposal' => $this->reason_for_counter_proposal,
            'remarks' => $this->remarks,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}
