<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DonationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'charity_project_id' => $this->charity_project_id,
            'donation_date' => $this->donation_date?->format('Y-m-d H:i:s'),
            'amount' => MoneyResource::make($this->amount),
            'comment' => $this->comment,
        ];
    }
}
