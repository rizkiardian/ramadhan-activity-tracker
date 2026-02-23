<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PrayerTime extends Model
{
    /** @use HasFactory<\Database\Factories\PrayerTimeFactory> */
    use HasFactory;

    protected $table = 'prayer_times';

    protected $fillable = [
        'regency_code',
        'regency_name',
        'gmt',
        'date',
        'year',
        'month',
        'day',
        'imsyak',
        'shubuh',
        'terbit',
        'dhuha',
        'dzuhur',
        'ashr',
        'maghrib',
        'isya',
    ];

    protected function casts(): array
    {
        return [
            'date' => 'date',
            'year' => 'integer',
            'month' => 'integer',
            'day' => 'integer',
            'gmt' => 'integer',
        ];
    }

    /**
     * Scope to filter by regency code.
     *
     * @param  \Illuminate\Database\Eloquent\Builder<PrayerTime>  $query
     */
    public function scopeByRegency(\Illuminate\Database\Eloquent\Builder $query, string $regencyCode): \Illuminate\Database\Eloquent\Builder
    {
        return $query->where('regency_code', $regencyCode);
    }

    /**
     * Scope to filter by year and month.
     *
     * @param  \Illuminate\Database\Eloquent\Builder<PrayerTime>  $query
     */
    public function scopeByMonth(\Illuminate\Database\Eloquent\Builder $query, int $year, int $month): \Illuminate\Database\Eloquent\Builder
    {
        return $query->where('year', $year)->where('month', $month);
    }

    public function regency(): BelongsTo
    {
        return $this->belongsTo(Regency::class, 'regency_code', 'code');
    }
}
