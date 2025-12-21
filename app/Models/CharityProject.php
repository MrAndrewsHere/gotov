<?php

declare(strict_types=1);

namespace App\Models;

use App\Casts\MoneyCast;
use App\Enums\ProjectStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;

class CharityProject extends Model
{
    use HasFactory;

    protected $table = 'charity_projects';

    protected $fillable = [
        'name',
        'slug',
        'short_description',
        'status',
        'launch_date',
        'additional_description',
        'donation_amount',
        'sort_order',
    ];

    protected $casts = [
        'launch_date' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'donation_amount' => MoneyCast::class,
        'status' => ProjectStatus::class,
    ];

    public function donations(): HasMany
    {
        return $this->hasMany(Donation::class);
    }

    public function isActive(): bool
    {
        return $this->status === ProjectStatus::ACTIVE;
    }

    public function isClosed(): bool
    {
        return $this->status === ProjectStatus::CLOSED;
    }

    public function isDraft(): bool
    {
        return $this->status === ProjectStatus::DRAFT;
    }

    public static function isSlugExists(string $slug): bool
    {
        return static::query()->where('slug', $slug)->exists();
    }

    public function scopeSlugIs(Builder $query, string $slug): Builder
    {
        return $query->where('slug', $slug);
    }

    public function scopeStatus(Builder $query, ProjectStatus ...$status): Builder
    {
        return $query->whereIn('status', array_column($status, 'value'));
    }

    public function scopeActiveOrClosed(Builder $query): Builder
    {
        return $query->status(ProjectStatus::ACTIVE, ProjectStatus::CLOSED);
    }

    /**
     * Check for discrepancies between stored donation_amount and calculated sum of donations
     *
     * @return array<int, object{ id: int, name: string, stored_total: int, calculated_total: int }>
     */
    public static function diff(): array
    {
        return DB::select('
        SELECT p.id, p.name, p.donation_amount as stored_total,
                   COALESCE(SUM(d.amount), 0) as calculated_total
            FROM charity_projects p
            LEFT JOIN donations d ON p.id = d.charity_project_id
            GROUP BY p.id, p.name, p.donation_amount
            HAVING p.donation_amount != COALESCE(SUM(d.amount), 0)
        ');
    }
    // TODO console command to fix discrepancies
}
