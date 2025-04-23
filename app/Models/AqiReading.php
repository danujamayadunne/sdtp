<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AqiReading extends Model
{
    use HasFactory;

    protected $fillable = [
        'sensor_id',
        'aqi_value',
        'category',
        'pm25',
        'pm10',
        'ozone',
        'reading_time',
    ];

    protected $casts = [
        'reading_time' => 'datetime',
    ];

    public function sensor(): BelongsTo
    {
        return $this->belongsTo(Sensor::class);
    }

    public static function getCategoryForValue($value)
    {
        if ($value <= 51) {
            return 'Good';
        } elseif ($value <= 100) {
            return 'Moderate';
        } elseif ($value <= 190) {
            return 'Bad';
        } else {
            return 'Hazardous';
        }
    }
}
