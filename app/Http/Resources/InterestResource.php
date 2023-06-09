<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class InterestResource extends JsonResource
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
            'title'       => ucwords($this->title),
            'created_at'  => $this->created_at->format('Y-m-d'),
            'updated_at'  => $this->created_at->format('Y-m-d'),
        ];
    }
}
