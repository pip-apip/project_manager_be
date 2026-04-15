<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectResourceDetail extends JsonResource
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
            'code' => $this->code,
            'contract_number' => $this->contract_number,
            'contract_date' => $this->contract_date,
            'client' => $this->client,
            'ppk' => $this->ppk,
            'support_teams' => $this->support_teams,
            'support_team_internals' => ProjectTeamResource::collection(
                $this->whenLoaded('supportTeams')
            ),
            'value' => $this->value,
            'status' => $this->status,
            'progress' => $this->progress,
            'company_id' => optional($this->company)->id,
            'company_name' => optional($this->company)->name,
            'company_address' => optional($this->company)->address,
            'company_director_name' => optional($this->company)->director_name,
            'company_director_phone' => optional($this->company)->director_phone,
            'company_director_signature' => $this->company->director_signature
                ? '/storage/' . $this->company->director_signature
                : '',
            'project_leader_id' => optional($this->projectLeader)->id,
            'project_leader_name' => optional($this->projectLeader)->name,
            'specktech' => $this->activityCategories->map(function ($category) {
                return [
                    'id' => $category->id,
                    'name' => $category->name,
                    'qty_total' => $category->qty_total,
                    'qty_recived' => $category->qty_recived,
                    'total_nominal' => $category->total_nominal,
                    'qty_nominal' => $category->qty_nominal,
                    'percentage' => $category->value,
                    'note' => $category->note,
                    'images' => $category->images
                ];
            }),
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'maintenance_date' => $this->maintenance_date
        ];
    }
}
