<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SubSpektekResource extends JsonResource
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
            'qty_recived' => (int) $this->qty_recived ?? 0,
            'total_nominal' => (int) $this->total_nominal ?? 0,
            'qty_nominal' => (int) $this->qty_nominal ?? 0,
            'progress_percentage' => (float) $this->progress_percentage ?? 0,
            'detail' => $this->detail ?? null,
            'note' => $this->note ?? null,
            'spektek_id' => $this->spektek_id ?? null,
            'spektek_name' => $this->spektek->name ?? null,
            'qty_updated_at' => $this->qty_updated_at ?? null,
            'progress_updated_at' => $this->progress_updated_at ?? null,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
