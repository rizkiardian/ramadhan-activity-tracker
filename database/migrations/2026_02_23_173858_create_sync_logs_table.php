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
        Schema::create('sync_logs', function (Blueprint $table) {
            $table->id();
            $table->string('sync_type');
            $table->date('start_date');
            $table->date('end_date');
            $table->dateTime('sync_time');
            $table->enum('status', ['Success', 'Failed', 'Pending'])->default('Pending');
            $table->text('notes')->nullable();
            $table->foreignId('synced_by')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sync_logs');
    }
};
