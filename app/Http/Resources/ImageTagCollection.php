<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ImageTagCollection extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'coords' => $this->coords,
            'label' => $this->label,
            'description' => $this->description,
            'href' => [
                'edit' => route('image.tag.edit', ['image_id' => $this->image->id, 'tag_id' => $this->id])
            ]
        ];
    }
}
