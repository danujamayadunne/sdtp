<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Sensor extends Model
{
    use HasFactory;

    protected $fillable = [
        'sensor_id',
        'name',
        'location_name',
        'latitude',
        'longitude',
        'is_active',
    ];

    public function readings(): HasMany
    {
        return $this->hasMany(AqiReading::class);
    }

    public function latestReading()
    {
        return $this->readings()->latest('reading_time')->first();
    }
}
