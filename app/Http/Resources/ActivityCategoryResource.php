<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ActivityCategoryResource extends JsonResource
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
            'name' => $this->name,
            'qty_total' => (int) $this->qty_total ?? 0,
            'qty_recived' => (int) $this->qty_recived ?? 0,
            'total_nominal' => (int) $this->total_nominal ?? 0,
            'qty_nominal' => (int) $this->qty_nominal ?? 0,
            'percentage' => (int) $this->value ?? 0,
            'note' => $this->note ?? '',
            'images' => is_array($this->images)
                ? array_map(fn($image) => $image, $this->images)
                : [],
            'project_id' => $this->project->id ?? 0,
            'project_name' => $this->project->name ?? '',
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
