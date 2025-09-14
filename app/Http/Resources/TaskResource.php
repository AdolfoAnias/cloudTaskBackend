<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'is_done' => $this->is_done,
            'keywords' => $this->keywords->map(function ($keyword) {
                return [
                    'id' => $keyword->id,
                    'name' => $keyword->name,
                ];
            }),            
        ];
    }
}

