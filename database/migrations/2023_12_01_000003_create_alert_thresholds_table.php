<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('alert_thresholds', function (Blueprint $table) {
            $table->id();
            $table->string('category'); // Good, Moderate, Unhealthy, etc.
            $table->decimal('min_value', 8, 2);
            $table->decimal('max_value', 8, 2);
            $table->string('color_code'); // Hex color code for map display
            $table->string('description')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('alert_thresholds');
    }
};
