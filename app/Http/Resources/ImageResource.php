<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class ImageResource extends JsonResource
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
            'id' => $this->id,
            'image' => $this->name,
            'uploaded_by' => $this->user->name,
            'href' => [
                'image' => url(Storage::url($this->path)),
                'view_tags' => route('image.tags', $this->id),
                'add_tags' => route('image.tag.add', $this->id),
            ]
        ];
    }
}
