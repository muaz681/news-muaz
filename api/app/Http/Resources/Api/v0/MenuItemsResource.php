<?php

namespace App\Http\Resources\Api\v0;

use Illuminate\Http\Resources\Json\JsonResource;

class MenuItemsResource extends JsonResource
{

    public function toArray($request)
    {

        return [

            'id' => $this->id,
            'title' => $this->title,
            'menu_id' => $this->menu_id,
            'model_id' => ($this->menuable_type)? $this->menuable->id:null,
            'model_title' => ($this->menuable_type)? $this->menuable->title:null,
            'model_slug' => ($this->menuable_type)? $this->menuable->slug:null,
            'menuable_type' => $this->menuable_type,
            'taxonomy' => $this->menuable_type ? class_basename($this->menuable_type) : 'Link',
            'link' => $this->link,
            'order' => $this->order,
            'parameters' => $this->parameters,
            'remarks' => $this->remarks,
            'parent' => $this->parent,
            'children' => MenuItemsResource::collection($this->children),

        ];
    }
}
