<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Sensor;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SensorController extends Controller
{
    public function index()
    {
        $sensors = Sensor::all();
        return view('admin.sensors.index', compact('sensors'));
    }
    
    public function create()
    {
        return view('admin.sensors.create');
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'location_name' => 'required|string|max:255',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'is_active' => 'boolean',
        ]);
        
        $validated['sensor_id'] = 'SN-' . Str::random(8);
        $validated['is_active'] = $request->has('is_active');
        
        Sensor::create($validated);
        
        return redirect()->route('admin.sensors.index')
            ->with('success', 'Sensor created successfully');
    }
    
    public function edit(Sensor $sensor)
    {
        return view('admin.sensors.edit', compact('sensor'));
    }
    
    public function update(Request $request, Sensor $sensor)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'location_name' => 'required|string|max:255',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'is_active' => 'boolean',
        ]);
        
        $validated['is_active'] = $request->has('is_active');
        
        $sensor->update($validated);
        
        return redirect()->route('admin.sensors.index')
            ->with('success', 'Sensor updated successfully');
    }
    
    public function destroy(Sensor $sensor)
    {
        $sensor->delete();
        
        return redirect()->route('admin.sensors.index')
            ->with('success', 'Sensor deleted successfully');
    }
}
