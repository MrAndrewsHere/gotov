<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\DTOs\CreateDonationDTO;
use App\Http\Requests\StoreDonationRequest;
use App\Http\Resources\DonationResource;
use App\Services\Interfaces\DonationServiceInterface;
use Illuminate\Http\JsonResponse;

class DonationController
{
    public function __construct(
        private readonly DonationServiceInterface $donationService,
    ) {}

    public function store(StoreDonationRequest $request): JsonResponse
    {
        $donation = $this->donationService->createDonation(
            CreateDonationDTO::fromArray($request->validated())
        );

        return response()->json(
            new DonationResource($donation),
            201
        );
    }
}
