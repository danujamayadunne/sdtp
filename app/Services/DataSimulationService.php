<?php

namespace App\Services;

use App\Models\AqiReading;
use App\Models\Sensor;
use Carbon\Carbon;

class DataSimulationService
{
    protected $baseAqi = [
        'downtown' => ['base' => 120, 'variation' => 30],
        'industrial' => ['base' => 150, 'variation' => 40],
        'residential' => ['base' => 80, 'variation' => 20],
        'suburban' => ['base' => 60, 'variation' => 15],
        'park' => ['base' => 40, 'variation' => 10],
    ];
    
    protected $timeFactors = [
        '00' => 0.7, '01' => 0.6, '02' => 0.5, '03' => 0.4, '04' => 0.4, '05' => 0.5,
        '06' => 0.7, '07' => 0.9, '08' => 1.1, '09' => 1.2, '10' => 1.1, '11' => 1.0,
        '12' => 1.1, '13' => 1.2, '14' => 1.3, '15' => 1.2, '16' => 1.3, '17' => 1.4,
        '18' => 1.3, '19' => 1.2, '20' => 1.1, '21' => 1.0, '22' => 0.9, '23' => 0.8,
    ];
    
    public function generateReadings()
    {
        $sensors = Sensor::where('is_active', true)->get();
        $now = Carbon::now();
        
        foreach ($sensors as $sensor) {
            $areaType = $this->determineAreaType($sensor->location_name);
            $baseConfig = $this->baseAqi[$areaType];
            
            $hourFactor = $this->timeFactors[$now->format('H')] ?? 1.0;
            $weekdayFactor = ($now->isWeekday()) ? 1.2 : 0.8;
            
            $randomVariation = rand(-$baseConfig['variation'], $baseConfig['variation']);
            $aqi = $baseConfig['base'] + $randomVariation;
            $aqi = $aqi * $hourFactor * $weekdayFactor;
            
            $aqi = $aqi * 0.7;
            $aqi = round($aqi, 2);
            
            $aqi = max(0, min(500, $aqi));
            
            $category = AqiReading::getCategoryForValue($aqi);
            
            AqiReading::create([
                'sensor_id' => $sensor->id,
                'aqi_value' => $aqi,
                'category' => $category,
                'pm25' => round($aqi * 0.7, 2),
                'pm10' => round($aqi * 0.5, 2),
                'ozone' => round($aqi * 0.3, 2),
                'reading_time' => $now,
            ]);
        }
    }
    
    protected function determineAreaType($locationName)
    {
        $locationName = strtolower($locationName);
        
        if (strpos($locationName, 'industrial') !== false) {
            return 'industrial';
        }
        
        if (strpos($locationName, 'park') !== false || strpos($locationName, 'garden') !== false) {
            return 'park';
        }
        
        if (strpos($locationName, 'downtown') !== false || strpos($locationName, 'fort') !== false || strpos($locationName, 'pettah') !== false) {
            return 'downtown';
        }
        
        if (strpos($locationName, 'suburb') !== false || strpos($locationName, 'outskirt') !== false) {
            return 'suburban';
        }
        
        return 'residential';
    }
}
