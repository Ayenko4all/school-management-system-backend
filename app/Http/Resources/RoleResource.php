<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RoleResource extends JsonResource
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
            'id'             => $this->id,
            'name'           => $this->name,
            'description'    => $this->description,
            'can_be_renamed' => $this->can_be_renamed,
            'deleted_at' => $this->deleted_at,
            'permissions'    =>  PermissionResource::collection($this->whenLoaded('permissions')),
        ];
    }
}
