<?php

namespace App\Http\Resources\MasterData\BranchOffice;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BranchResource extends JsonResource
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
            'code' => $this->code,
            'name' => $this->name,
            'area_id' => $this->area_id,
            'area' => $this->whenLoaded('area', $this->area?->only(['id', 'code', 'name'])),
            'unit_count' => $this->whenLoaded('units', $this->units->count()),
            'created_by' => $this->whenLoaded('user', $this->user->name),
            'created_at' => $this->when($this->created_at, $this->created_at->format('d-m-Y H:i:s')),
        ];
    }
}
