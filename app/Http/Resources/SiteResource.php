<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\UserResource;
class SiteResource extends JsonResource {
    /**
    * Transform the resource into an array.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return array
    */

    public function toArray( $request ) {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'region' => $this->region,
            'is_active' => $this->is_active,
            'created_by' => new UserResource( $this->createdBy ),
            'updated_by' => new UserResource( $this->updatedBy ),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}

