<?php

namespace App\Models;

use App\Enums\SyncCategory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class SyncLog extends Model
{
    /** @use HasFactory<\Database\Factories\SyncLogFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'sync_type',
        'sync_category',
        'start_date',
        'end_date',
        'sync_time',
        'status',
        'notes',
        'synced_by',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
            'sync_time' => 'datetime',
            'sync_category' => SyncCategory::class,
        ];
    }

    public function syncedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'synced_by');
    }
}
