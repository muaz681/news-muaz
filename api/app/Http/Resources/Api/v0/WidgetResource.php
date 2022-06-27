<?php

namespace App\Http\Resources\Api\v0;

use Illuminate\Http\Resources\Json\JsonResource;

class WidgetResource extends JsonResource
{

    public function toArray($request)
    {
        return [
            'id'                    => $this->id,
            'name'                  => $this->name,
            'path'                  => $this->path,
            'widget_type'           => $this->widget_type,
            'taxonomy_selectable'   => $this->taxonomy_selectable,
            'taxonomy'              => $this->taxonomy,
            'selected_category'     => $this->selected_category,
            'category_taxonomy'     => $this->category_taxonomy,
            'limit_required'        => $this->limit_required,
            'title_required'        => $this->title_required,
            'status'                => $this->status,
            'created_at'            => $this->created_at,
            'updated_at'            => $this->updated_at,
        ];
    }
}
