<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SpektekResource extends JsonResource
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
            'qty_total' => (int) $this->qty_total ?? 0,
            'total_nominal' => (int) $this->total_nominal ?? 0,
            'qty_nominal' => (int) $this->qty_nominal ?? 0,
            'qty_received' => (int) $this->qty_received ?? 0,
            'qty_updated_at' => $this->qty_updated_at ?? null,
            'progress_percentage' => $this->arrivalPercentage,
            'progress_updated_at' => $this->progress_updated_at ?? null,
            'detail' => $this->detail ?? null,
            'note' => $this->note ?? null,
            'project_id' => $this->project->id ?? 0,
            'project_name' => $this->project->name ?? null,
            // 'sub_spekteks' => SubSpektekResource::collection($this->subSpekteks),
            // 'sub_spekteks' => $this->subSpekteks->map(function ($sub) {
            //     return [
            //         'id' => $sub->id,
            //         'name' => $sub->name,
            //         'type' => $sub->type,
            //         'qty_total' => (int) $sub->qty_total ?? 0,
            //         'qty_recived' => (int) $sub->qty_recived ?? 0,
            //         'total_nominal' => (int) $sub->total_nominal ?? 0,
            //         'qty_nominal' => (int) $sub->qty_nominal ?? 0,
            //         'progress_percentage' => $sub->arrivalPercentage,
            //         'detail' => $sub->detail ?? null,
            //         'note' => $sub->note ?? null
            //     ];
            // }),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
