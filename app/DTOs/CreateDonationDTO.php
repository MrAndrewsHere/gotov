<?php

declare(strict_types=1);

namespace App\DTOs;

use App\Casts\MoneyTransform;
use Carbon\Carbon;
use Money\Money;
use Spatie\LaravelData\Data;

class CreateDonationDTO extends Data
{
    public function __construct(
        public int $charityProjectId,
        public Money $amount,
        public ?Carbon $donationDate = null,
        public ?string $comment = null,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            charityProjectId: $data['charity_project_id'],
            amount: MoneyTransform::parse($data['amount']),
            donationDate: isset($data['donation_date']) ? Carbon::parse($data['donation_date']) : null,
            comment: $data['comment'] ?? null,
        );
    }
}
