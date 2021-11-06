<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SessionResource extends JsonResource
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
            'id'            => $this->id,
            'name'          => $this->name,
            'duration'      => $this->duration,
            'status'        => $this->status,
            'start_date'    => $this->start_date,
            'end_date'      => $this->end_date,
            'deleted_at'    => $this->deleted_at,
            'created_at'    => $this->created_at,
            'updated_at'    => $this->updated_at,
            'terms'         => TermResource::collection($this->whenLoaded('terms')),
            'classrooms'    => ClassroomResource::collection($this->whenLoaded('classrooms')),
        ];
    }
}
