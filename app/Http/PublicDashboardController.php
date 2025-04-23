<?php

namespace App\Http\Controllers;

use App\Models\AlertThreshold;
use App\Models\Sensor;
use Illuminate\Http\Request;

class PublicDashboardController extends Controller
{
    public function index()
    {
        $thresholds = AlertThreshold::all();
        return view('public.dashboard', compact('thresholds'));
    }
    
    public function getSensors()
    {
        $sensors = Sensor::where('is_active', true)->get();
        
        $sensorData = $sensors->map(function ($sensor) {
            $latestReading = $sensor->latestReading();
            
            return [
                'id' => $sensor->id,
                'sensor_id' => $sensor->sensor_id,
                'name' => $sensor->name,
                'location' => $sensor->location_name,
                'latitude' => $sensor->latitude,
                'longitude' => $sensor->longitude,
                'latest_reading' => $latestReading ? [
                    'aqi_value' => $latestReading->aqi_value,
                    'category' => $latestReading->category,
                    'color' => AlertThreshold::getColorForValue($latestReading->aqi_value),
                    'time' => $latestReading->reading_time->format('Y-m-d H:i:s'),
                ] : null,
            ];
        });
        
        return response()->json($sensorData);
    }
    
    public function getSensorHistory($id, Request $request)
    {
        $period = $request->input('period', 'day');
        $sensor = Sensor::findOrFail($id);
        
        $timeConstraint = now();
        switch ($period) {
            case 'day':
                $timeConstraint = now()->subDay();
                break;
            case 'week':
                $timeConstraint = now()->subWeek();
                break;
            case 'month':
                $timeConstraint = now()->subMonth();
                break;
            default:
                $timeConstraint = now()->subDay();
        }
        
        $readings = $sensor->readings()
            ->where('reading_time', '>=', $timeConstraint)
            ->orderBy('reading_time')
            ->get()
            ->map(function ($reading) {
                return [
                    'time' => $reading->reading_time->format('Y-m-d H:i:s'),
                    'aqi_value' => $reading->aqi_value,
                    'category' => $reading->category,
                ];
            });
            
        return response()->json([
            'sensor' => [
                'id' => $sensor->id,
                'name' => $sensor->name,
                'location' => $sensor->location_name,
            ],
            'readings' => $readings,
            'period' => $period,
        ]);
    }
}
