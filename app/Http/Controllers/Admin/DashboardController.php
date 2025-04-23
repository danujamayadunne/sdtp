<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AqiReading;
use App\Models\Sensor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;

class DashboardController extends Controller
{
    public function index()
    {
        $totalSensors = Sensor::count();
        $activeSensors = Sensor::where('is_active', true)->count();
        $inactiveSensors = $totalSensors - $activeSensors;
        
        $totalReadings = AqiReading::count();
        $last24HourReadings = AqiReading::where('reading_time', '>=', now()->subDay())->count();
        
        $latestReading = AqiReading::latest('reading_time')->first();
        $latestReadingTime = $latestReading ? $latestReading->reading_time->format('Y-m-d H:i:s') : 'No readings yet';
        
        $simulationActive = Cache::get('simulation_active', false);
        
        return view('admin.dashboard', compact(
            'totalSensors',
            'activeSensors',
            'inactiveSensors',
            'totalReadings',
            'last24HourReadings',
            'latestReadingTime',
            'simulationActive'
        ));
    }
    
    public function startSimulation()
    {
        Cache::put('simulation_active', true);
        
        Artisan::call('aqi:generate');
        
        return redirect()->route('admin.dashboard')
            ->with('success', 'Simulation started successfully. Data will be generated every 15 minutes.');
    }
    
    public function stopSimulation()
    {
        Cache::forget('simulation_active');
        
        return redirect()->route('admin.dashboard')
            ->with('success', 'Simulation stopped successfully.');
    }
}
