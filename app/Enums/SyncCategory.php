<?php

namespace App\Enums;

enum SyncCategory: string
{
    case Regency = 'regency';
    case PrayerTime = 'prayer_time';

    public function label(): string
    {
        return match ($this) {
            self::Regency => 'Sinkronisasi Kota',
            self::PrayerTime => 'Sinkronisasi Jadwal Sholat',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Regency => 'info',
            self::PrayerTime => 'warning',
        };
    }
}
