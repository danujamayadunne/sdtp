<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AlertThreshold;
use Illuminate\Http\Request;

class AlertThresholdController extends Controller
{
    public function index()
    {
        $thresholds = AlertThreshold::orderBy('min_value')->get();
        return view('admin.alert-thresholds.index', compact('thresholds'));
    }
    
    public function create()
    {
        return view('admin.alert-thresholds.create');
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'category' => 'required|string|max:255',
            'min_value' => 'required|numeric|min:0',
            'max_value' => 'required|numeric|gt:min_value',
            'color_code' => 'required|string|max:7|regex:/#[0-9A-Fa-f]{6}/',
            'description' => 'nullable|string',
        ]);
        
        AlertThreshold::create($validated);
        
        return redirect()->route('admin.alert-thresholds.index')
            ->with('success', 'Alert threshold created successfully');
    }
    
    public function edit(AlertThreshold $alertThreshold)
    {
        return view('admin.alert-thresholds.edit', compact('alertThreshold'));
    }
    
    public function update(Request $request, AlertThreshold $alertThreshold)
    {
        $validated = $request->validate([
            'category' => 'required|string|max:255',
            'min_value' => 'required|numeric|min:0',
            'max_value' => 'required|numeric|gt:min_value',
            'color_code' => 'required|string|max:7|regex:/#[0-9A-Fa-f]{6}/',
            'description' => 'nullable|string',
        ]);
        
        $alertThreshold->update($validated);
        
        return redirect()->route('admin.alert-thresholds.index')
            ->with('success', 'Alert threshold updated successfully');
    }
    
    public function destroy(AlertThreshold $alertThreshold)
    {
        $alertThreshold->delete();
        
        return redirect()->route('admin.alert-thresholds.index')
            ->with('success', 'Alert threshold deleted successfully');
    }
}
