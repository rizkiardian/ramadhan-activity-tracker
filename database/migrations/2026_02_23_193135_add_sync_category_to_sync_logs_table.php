<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sync_logs', function (Blueprint $table) {
            $table->enum('sync_category', ['regency', 'prayer_time'])
                ->default('prayer_time')
                ->after('sync_type')
                ->comment('Kategori sinkronisasi: kota atau jadwal sholat');
        });

        // Populate existing rows
        DB::table('sync_logs')->where('sync_type', 'regencies')->update(['sync_category' => 'regency']);
        DB::table('sync_logs')->where('sync_type', 'prayer_times')->update(['sync_category' => 'prayer_time']);
    }

    public function down(): void
    {
        Schema::table('sync_logs', function (Blueprint $table) {
            $table->dropColumn('sync_category');
        });
    }
};
