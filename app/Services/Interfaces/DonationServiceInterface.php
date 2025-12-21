<?php

declare(strict_types=1);

namespace App\Services\Interfaces;

use App\DTOs\CreateDonationDTO;
use App\Models\Donation;

interface DonationServiceInterface
{
    public function createDonation(CreateDonationDTO $dto): Donation;
}
