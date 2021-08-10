<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'gender' => $this->gender,
            'telephone' => $this->telephone,
            'date_of_bith' => $this->date_of_bith,
            'address'   => $this->address,
            'status'        => $this->status,
            'deleted_at'    => $this->deleted_at,
            'email_verified_at' => $this->email_verified_at,
            'telephone_verified_at' => $this->telephone_verified_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
