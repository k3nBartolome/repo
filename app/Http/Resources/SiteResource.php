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
            'site_director'=> $this->site_director,
            'is_active' => $this->is_active,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}

