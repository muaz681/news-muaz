<?php

namespace App\Http\Resources\Api\v0;

use App\Models\Category;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
   
    public function toArray($request)
    {
        return [
            'id'                    => $this->id,
            'title'                 => $this->title,
            'slug'                  => $this->slug,
            'status'                => $this->status,
            'isMenu'                => $this->isMenu,
            'child'                 => CategoryResource::collection($this->children),
            'created_at'            => $this->created_at,
            'updated_at'            => $this->updated_at,
            'seo'                   => new SeoResource($this->seo),
        ];
    }
}
