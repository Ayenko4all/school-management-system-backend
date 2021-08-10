<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class LgaAreaResource extends JsonResource
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
            'id'            => $this->id,
            'lga'           => $this->lga,
            'status'        => $this->status,
            'deleted_at'    => $this->deleted_at,
            'created_at'    => $this->created_at,
            'updated_at'    => $this->updated_at,
            'state'         => new StateResource($this->whenLoaded('stateType')),
        ];
    }
}
