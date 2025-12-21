<?php

declare(strict_types=1);

namespace App\Services;

use App\DTOs\CreateDonationDTO;
use App\Models\CharityProject;
use App\Models\Donation;
use App\Services\Exceptions\CannotDonateToProjectException;
use App\Services\Exceptions\FutureDonationDateException;
use App\Services\Interfaces\DonationServiceInterface;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Stevebauman\Purify\Facades\Purify;

class DonationService implements DonationServiceInterface
{
    public function createDonation(
        CreateDonationDTO $dto
    ): Donation {
        $project = CharityProject::findOrFail($dto->charityProjectId);

        if ($project->isDraft()) {
            throw CannotDonateToProjectException::make();
        }

        $date = $dto->donationDate instanceof Carbon ? $dto->donationDate : Carbon::now();

        if ($date->isFuture()) {
            throw FutureDonationDateException::make();
        }

        return DB::transaction(function () use ($dto, $date): Donation {
            DB::statement('SET TRANSACTION ISOLATION LEVEL SERIALIZABLE');

            return Donation::create([
                'charity_project_id' => $dto->charityProjectId,
                'amount' => $dto->amount,
                'donation_date' => $date,
                'comment' => Purify::clean($dto->comment),
            ]);
        }, 3);
    }
}
