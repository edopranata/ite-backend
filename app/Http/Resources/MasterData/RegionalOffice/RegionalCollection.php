<?php

namespace App\Http\Resources\MasterData\RegionalOffice;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class RegionalCollection extends ResourceCollection
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'data' => RegionalResource::collection($this->collection->all())
        ];
    }
}
