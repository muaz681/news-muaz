<?php

namespace App\Http\Resources\Api\v0;

use Illuminate\Http\Resources\Json\JsonResource;

class MenuResource extends JsonResource
{

    public function toArray($request)
    {

         return [
             'id' => $this->id,
             'name' => $this->name,
             'type' => $this->type,
             'items' => MenuItemsResource::collection($this->items),
         ];
    }
}
