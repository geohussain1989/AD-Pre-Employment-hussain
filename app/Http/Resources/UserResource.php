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
            'id'          => $this->id,
            'first_name'       => $this->first_name,
            'last_name' => $this->last_name,
            'address' => $this->address,
            'date_of_birth' => $this->date_of_birth,
            'verified' => $this->verified,
            'email' => $this->email,
            'access_token' => $this->when($this->access_token, $this->access_token),
            'created_at'  => $this->created_at->format('Y-m-d'),
            'updated_at'  => $this->created_at->format('Y-m-d'),
            'interests' => $this->when($this->interests, InterestResource::collection($this->interests)),
        ];
    }
}
