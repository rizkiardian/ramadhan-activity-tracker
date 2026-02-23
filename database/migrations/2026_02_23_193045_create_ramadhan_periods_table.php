<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ramadhan_periods', function (Blueprint $table) {
            $table->id();
            $table->unsignedSmallInteger('year')->unique()->comment('Tahun Masehi');
            $table->date('start_date')->comment('Tanggal awal Ramadhan');
            $table->date('end_date')->comment('Tanggal akhir Ramadhan');
            $table->string('hijri_year', 10)->nullable()->comment('Tahun Hijriyah, mis. 1447H');
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('year');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ramadhan_periods');
    }
};
