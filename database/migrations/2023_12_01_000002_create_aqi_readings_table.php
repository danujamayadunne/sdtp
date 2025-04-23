<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('aqi_readings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sensor_id')->constrained('sensors')->onDelete('cascade');
            $table->decimal('aqi_value', 8, 2);
            $table->string('category'); // Good, Moderate, Unhealthy, etc.
            $table->decimal('pm25', 8, 2)->nullable();
            $table->decimal('pm10', 8, 2)->nullable();
            $table->decimal('ozone', 8, 2)->nullable();
            $table->timestamp('reading_time');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('aqi_readings');
    }
};
