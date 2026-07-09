<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SpektekResourceWithSub extends JsonResource
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
            'type' => $this->type,
            'project_id' => $this->project->id ?? 0,
            'project_name' => $this->project->name ?? null,
            'sub_spekteks' => $this->subSpekteks->map(function ($sub) {
                return [
                    'id' => $sub->id,
                    'name' => $sub->name,
                    'type' => $sub->type,
                    'qty_total' => (int) $sub->qty_total ?? 0,
                    'qty_recived' => (int) $sub->qty_recived ?? 0,
                    'total_nominal' => (int) $sub->total_nominal ?? 0,
                    'qty_nominal' => (int) $sub->qty_nominal ?? 0,
                    'progress_percentage' => $sub->arrivalPercentage,
                    'detail' => $sub->detail ?? null,
                    'note' => $sub->note ?? null
                ];
            }),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
