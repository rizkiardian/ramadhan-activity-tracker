<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ActivityType extends Model
{
    /** @use HasFactory<\Database\Factories\ActivityTypeFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'created_by',
    ];

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function userActivities(): HasMany
    {
        return $this->hasMany(UserActivity::class);
    }
}
