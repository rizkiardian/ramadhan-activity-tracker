<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class RamadhanPeriod extends Model
{
    /** @use HasFactory<\Database\Factories\RamadhanPeriodFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'year',
        'start_date',
        'end_date',
        'hijri_year',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'year' => 'integer',
            'start_date' => 'date',
            'end_date' => 'date',
        ];
    }

    /**
     * Get total days in this Ramadhan period.
     */
    public function getTotalDaysAttribute(): int
    {
        return $this->start_date->diffInDays($this->end_date) + 1;
    }

    /**
     * Scope to get the active Ramadhan period for a given year.
     *
     * @param  Builder<RamadhanPeriod>  $query
     */
    public function scopeForYear(Builder $query, int $year): Builder
    {
        return $query->where('year', $year);
    }

    /**
     * Scope to get the currently active Ramadhan period.
     *
     * @param  Builder<RamadhanPeriod>  $query
     */
    public function scopeActive(Builder $query): Builder
    {
        $today = now()->toDateString();

        return $query->where('start_date', '<=', $today)->where('end_date', '>=', $today);
    }

    public function prayerTimes(): HasMany
    {
        return $this->hasMany(PrayerTime::class, 'year', 'year');
    }

    public function scopeCurrent($query)
    {
        return $query->where('year', now()->year)->first();
    }
}
