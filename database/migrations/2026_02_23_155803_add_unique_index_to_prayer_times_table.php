<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('prayer_times', function (Blueprint $table) {
            $table->dropIndex(['regency_code', 'date']);
            $table->unique(['regency_code', 'date']);
        });
    }

    public function down(): void
    {
        Schema::table('prayer_times', function (Blueprint $table) {
            $table->dropUnique(['regency_code', 'date']);
            $table->index(['regency_code', 'date']);
        });
    }
};
