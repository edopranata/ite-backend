<?php

namespace App\Http\Resources\MasterData\SubBranchOffice;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SubBranchResource extends JsonResource
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
            'branch_id' => $this->branch_id,
            'branch' => $this->whenLoaded('branch', $this->branch?->only(['id', 'name'])),
            'type' => $this->type,
            'created_by' => $this->whenLoaded('user', $this->user->name),
            'created_at' => $this->when($this->created_at, $this->created_at->format('d-m-Y H:i:s')),
        ];
    }
}
