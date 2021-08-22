<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SchoolResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'school_name' => $this->school_name,
            'school_address' => $this->school_address,
            'bvn' => $this->bvn,
            'city' => $this->city,
            'lga' => $this->lga,
            'state' => $this->state,
            'school_email_address' => $this->school_email_address,
            'school_telephone_address' => $this->school_telephone_address,
            'cac_document' => $this->cac_document,
            'created_at'    => $this->created_at,
            'updated_at'    => $this->updated_at,
            'user' => new UserResource($this->whenLoaded('user')),
            'school_type' => new SchoolTypeResource($this->whenLoaded('school_type')),
            'owners' => SchoolOwnerResource::collection($this->whenLoaded('directors')),
            'modules'   => ModuleResource::collection($this->whenLoaded('modules'))
        ];
    }
}
