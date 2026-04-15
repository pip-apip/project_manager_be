<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class AdminDocResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $file = $this->file ? '/storage/' . $this->file : '';
        $path = $this->file;

        $sizeBytes = $path && Storage::disk('public')->exists($path)
            ? Storage::disk('public')->size($path)
            : 0;

        if ($sizeBytes >= 1000000) {
            $size = round($sizeBytes / 1_000_000, 2) . ' MB';
        } else {
            $size = round($sizeBytes / 1_000, 2) . ' KB';
        }

        return [
            'id' => $this->id,
            'title' => $this->title,
            // 'file' => $this->file ? '/storage/'.$this->file : '',
            'files' => [
                'url' => $file,
                'size' => $size,
            ],
            'project_id' => optional($this->project)->id,
            'project_name' => optional($this->project)->name,
            'admin_doc_category_id' => optional($this->adminDocCategory)->id,
            'admin_doc_category_name' => optional($this->adminDocCategory)->name,
            'created_at' => $this->created_at
        ];
    }
}
