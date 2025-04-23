@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-12">
            <h1 class="h3 fw-bold text-dark">System Dashboard</h1>
            <p class="text-muted">Air quality monitoring system overview and controls</p>
        </div>
    </div>

    <div class="row">

    <div class="col-md-4">
            <div class="card mb-4 h-100">
                <div class="card-header bg-white border-bottom-0 pt-4">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-broadcast text-primary me-2"></i>
                        <h5 class="mb-0 fw-bold">Sensor Network Status</h5>
                    </div>
                </div>
                <div class="card-body py-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="text-muted"><i class="bi bi-hdd-rack me-2"></i> Total Sensors:</span>
                        <span class="badge bg-light text-dark rounded-pill px-3 py-2 fw-bold">{{ $totalSensors }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="text-muted"><i class="bi bi-check-circle text-success me-2"></i> Active Sensors:</span>
                        <span class="badge bg-success text-white rounded-pill px-3 py-2 fw-bold">{{ $activeSensors }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-muted"><i class="bi bi-x-circle text-danger me-2"></i> Inactive Sensors:</span>
                        <span class="badge bg-danger text-white rounded-pill px-3 py-2 fw-bold">{{ $inactiveSensors }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card mb-4 h-100">
                <div class="card-header bg-white border-bottom-0 pt-4">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-graph-up text-info me-2"></i>
                        <h5 class="mb-0 fw-bold">AQI Data Statistics</h5>
                    </div>
                </div>
                <div class="card-body py-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="text-muted"><i class="bi bi-database me-2"></i> Total Readings:</span>
                        <span class="badge bg-light text-dark rounded-pill px-3 py-2 fw-bold">{{ $totalReadings }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="text-muted"><i class="bi bi-clock me-2"></i> Last 24 Hours:</span>
                        <span class="badge bg-info text-white rounded-pill px-3 py-2 fw-bold">{{ $last24HourReadings }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-muted"><i class="bi bi-hourglass me-2"></i> Latest Reading:</span>
                        <span class="badge bg-light text-dark rounded-pill px-3 py-2 fw-bold">{{ $latestReadingTime }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card mb-4 h-100">
                <div class="card-header bg-white border-bottom-0 pt-4">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-gear-fill text-warning me-2"></i>
                        <h5 class="mb-0 fw-bold">Simulation Control</h5>
                    </div>
                </div>
                <div class="card-body py-4">
                    <div class="text-center mb-4">
                        <div class="simulation-indicator mb-3">
                            <div class="status-circle {{ $simulationActive ? 'bg-success' : 'bg-danger' }}">
                                <i style="font-size: 1.9em" class="bi {{ $simulationActive ? 'bi-play-fill' : 'bi-stop-fill' }} text-white"></i>
                            </div>
                            <div class="status-pulse {{ $simulationActive ? 'pulse-animation' : '' }}"></div>
                        </div>
                        <h5 class="fw-bold mb-0">
                            <span class="badge {{ $simulationActive ? 'bg-success' : 'bg-danger' }} rounded-pill px-3 py-2">
                                {{ $simulationActive ? 'Simulation Running' : 'Simulation Stopped' }}
                            </span>
                        </h5>
                        <p class="text-muted mt-2">
                            <i class="bi bi-clock me-1"></i> Data generation: Every 15 minutes
                        </p>
                    </div>
                    
                    <div class="d-flex justify-content-center gap-3 mt-4">
                        <form action="{{ route('admin.simulation.start') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-success rounded-pill px-4 {{ $simulationActive ? 'disabled' : '' }}">
                                <i class="bi bi-play-fill"></i> Start
                            </button>
                        </form>
                        
                        <form action="{{ route('admin.simulation.stop') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-danger rounded-pill px-4 {{ !$simulationActive ? 'disabled' : '' }}">
                                <i class="bi bi-stop-fill"></i> Stop
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="overflow-hidden">
                <div class="card-header bg-white border-bottom-0 pt-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-geo-alt-fill text-success me-2"></i>
                            <h5 class="mb-0 fw-bold">Current Air Quality Map</h5>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div id="admin-map" style="height: 450px;"></div>
                </div>
                <div class="card-footer bg-white py-3">
                    <div class="d-flex justify-content-center">
                        <div class="legend d-flex gap-4">
                            <div class="d-flex align-items-center">
                                <div class="legend-color" style="background-color: #00e400; width: 15px; height: 15px; border-radius: 50%; margin-right: 5px;"></div>
                                <span class="small">Good</span>
                            </div>
                            <div class="d-flex align-items-center">
                                <div class="legend-color" style="background-color: #ffff00; width: 15px; height: 15px; border-radius: 50%; margin-right: 5px;"></div>
                                <span class="small">Moderate</span>
                            </div>
                            <div class="d-flex align-items-center">
                                <div class="legend-color" style="background-color: #ff0000; width: 15px; height: 15px; border-radius: 50%; margin-right: 5px;"></div>
                                <span class="small">Unhealthy</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    #admin-map{
        border-radius: 9px;
        margin: 9px;
    }
    .simulation-indicator {
        position: relative;
        width: 80px;
        height: 80px;
        margin: 0 auto;
    }
    .status-circle {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 60px;
        height: 60px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 2;
    }
    .status-pulse {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        border-radius: 50%;
        background-color: rgba(40, 167, 69, 0.2);
        z-index: 1;
    }
    .pulse-animation {
        animation: pulse 2s infinite;
    }
    @keyframes pulse {
        0% {
            transform: scale(0.95);
            box-shadow: 0 0 0 0 rgba(40, 167, 69, 0.5);
        }
        70% {
            transform: scale(1);
            box-shadow: 0 0 0 20px rgba(40, 167, 69, 0);
        }
        100% {
            transform: scale(0.95);
            box-shadow: 0 0 0 0 rgba(40, 167, 69, 0);
        }
    }
</style>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {

        const map = L.map('admin-map').setView([6.9271, 79.8612], 12);
        
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);
        
        fetch('/api/sensors')
            .then(response => response.json())
            .then(data => {
                data.forEach(sensor => {
                    if (sensor.latest_reading) {
                        const marker = L.circleMarker([sensor.latitude, sensor.longitude], {
                            radius: 10,
                            fillColor: sensor.latest_reading.color,
                            color: '#000',
                            weight: 1,
                            opacity: 1,
                            fillOpacity: 0.8
                        });
                        
                        marker.bindPopup(`
                            <div class="p-2">
                                <h6 class="fw-bold mb-1">${sensor.name}</h6>
                                <p class="text-muted small mb-2">${sensor.location}</p>
                                <div class="d-flex align-items-center mb-1">
                                    <span class="fw-bold me-2">AQI:</span>
                                    <span class="badge" style="background-color:${sensor.latest_reading.color}">${sensor.latest_reading.aqi_value}</span>
                                </div>
                                <span style="color:${sensor.latest_reading.color}">${sensor.latest_reading.category}</span><br>
                                <small class="text-muted"><i class="bi bi-clock me-1"></i>${sensor.latest_reading.time}</small>
                            </div>
                        `);
                        
                        marker.addTo(map);
                    }
                });
            });
    });
</script>
@endsection
