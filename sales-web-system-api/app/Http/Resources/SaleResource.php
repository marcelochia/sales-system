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
            'id' => $this->getId(),
            'value' => $this->getValue(),
            'date' => $this->getDate()->format('Y-m-d'),
            'seller_id' => $this->getSeller()->getId(),
        ];
    }
}