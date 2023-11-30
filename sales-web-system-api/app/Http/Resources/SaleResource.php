<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SaleResource extends JsonResource
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
            'value' => $this->value,
            'date' => $this->date->format('Y-m-d'),
            'commission' => $this->commission,
            'seller' => [
                'id' => $this->seller->id,
                'name' => $this->seller->name,
            ],
        ];
    }
}
