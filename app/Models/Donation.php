<?php

declare(strict_types=1);

namespace App\Models;

use App\Casts\MoneyCast;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Donation extends Model
{
    use HasFactory;

    protected $table = 'donations';

    protected $fillable = [
        'charity_project_id',
        'donation_date',
        'amount',
        'comment',
    ];

    protected $casts = [
        'donation_date' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'amount' => MoneyCast::class,
    ];

    public function charityProject(): BelongsTo
    {
        return $this->belongsTo(CharityProject::class);
    }
}
