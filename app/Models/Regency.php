<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Regency extends Model
{
    /** @use HasFactory<\Database\Factories\RegencyFactory> */
    use HasFactory;

    public $timestamps = false;

    public $incrementing = false;

    protected $keyType = 'string';

    protected $primaryKey = 'code';

    protected $fillable = [
        'code',
        'name',
    ];

    public function prayerTimes(): HasMany
    {
        return $this->hasMany(PrayerTime::class, 'regency_code', 'code');
    }
}
