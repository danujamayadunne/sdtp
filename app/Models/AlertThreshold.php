<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AlertThreshold extends Model
{
    use HasFactory;

    protected $fillable = [
        'category',
        'min_value',
        'max_value',
        'color_code',
        'description',
    ];

    public static function getColorForValue($value)
    {
        $threshold = self::where('min_value', '<=', $value)
            ->where('max_value', '>=', $value)
            ->first();
            
        return $threshold ? $threshold->color_code : '#000000';
    }
}
