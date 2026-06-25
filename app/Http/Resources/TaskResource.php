<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Log;

class TaskResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'created_at' =>  $this->created_at,
            'due_date' => $this->due_date,
            'status' => [
                'id' => $this->status_id,
                'title' => $this->status()->first()->title,
            ],
            'priority' => [
                'id' => $this->priority_id,
                'title' => $this->priority()->first()->title,
            ],
            'category' => [
                'id' => $this->category_id,
                'title' => $this->category()->first()->title,
            ],

        ];
    }
}
