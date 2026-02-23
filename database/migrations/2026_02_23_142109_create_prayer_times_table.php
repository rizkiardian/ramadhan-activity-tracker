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
        Schema::create('prayer_times', function (Blueprint $table) {
            $table->id();
            $table->string('regency_code', 10);
            $table->string('regency_name');
            $table->tinyInteger('gmt');
            $table->date('date');
            $table->unsignedSmallInteger('year');
            $table->unsignedTinyInteger('month');
            $table->unsignedTinyInteger('day');
            $table->string('imsyak', 5);
            $table->string('shubuh', 5);
            $table->string('terbit', 5);
            $table->string('dhuha', 5);
            $table->string('dzuhur', 5);
            $table->string('ashr', 5);
            $table->string('maghrib', 5);
            $table->string('isya', 5);
            $table->timestamps();

            $table->index(['regency_code', 'date']);
            $table->index(['year', 'month']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prayer_times');
    }
};
