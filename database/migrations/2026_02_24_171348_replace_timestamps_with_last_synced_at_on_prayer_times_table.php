<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('prayer_times', function (Blueprint $table) {
            $table->dropColumn(['created_at', 'updated_at']);
            $table->timestamp('last_synced_at')->nullable()->after('isya');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('prayer_times', function (Blueprint $table) {
            $table->dropColumn('last_synced_at');
            $table->timestamps();
        });
    }
};
