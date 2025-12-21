<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CharityProjectResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'short_description' => $this->short_description,
            'status' => $this->status->toArray(),
            'launch_date' => $this->launch_date?->format('Y-m-d H:i:s'),
            'donation_amount' => MoneyResource::make($this->donation_amount),
        ];
    }
}
