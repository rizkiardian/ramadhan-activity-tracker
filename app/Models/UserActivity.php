<?php

namespace App\Models;

use App\Models\ActivityType;
use App\Models\RamadhanPeriod;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

class UserActivity extends Model
{
    /** @use HasFactory<\Database\Factories\UserActivityFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'activity_type_id',
        'date',
        'start_time',
        'end_time',
        'status',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'date' => 'date',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function activityType(): BelongsTo
    {
        return $this->belongsTo(ActivityType::class);
    }

    public function getRamadhanDayAttribute()
    {
        if (!$this->date) return null;

        $date = Carbon::parse($this->date);
        $year = $date->year;

        $period = RamadhanPeriod::where('year', $year)->first();
        if (!$period) return null;

        $start = Carbon::parse($period->start_date);

        return $start->diffInDays($date) + 1;
    }
}
