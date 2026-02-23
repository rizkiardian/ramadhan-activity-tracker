<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SyncLog extends Model
{
    /** @use HasFactory<\Database\Factories\SyncLogFactory> */
    use HasFactory;

    protected $fillable = [
        'sync_type',
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
        ];
    }

    public function syncedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'synced_by');
    }
}
