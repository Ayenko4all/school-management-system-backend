<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TermResource extends JsonResource
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
            "name"          => $this->name,
            "start_date"    => $this->start_date,
            "end_date"      => $this->end_date,
            "status"        => $this->status,
            "deleted_at"    => $this->deleted_at,
            "created_at"    => $this->created_at,
            "updated_at"    => $this->updated_at,
            'sessions'       => SessionResource::collection($this->whenLoaded('sessions')),
            'classroom'    => new ClassroomResource($this->whenLoaded('classrooms')),
            'subjects'    => SubjectResource::collection($this->whenLoaded('subjects')),
        ];
    }
}
