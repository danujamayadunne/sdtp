@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="mb-5">
        <h1 class="h3 fw-bold text-black mb-1">Edit Sensor</h1>
        <p class="text-muted">Update sensor configuration for {{ $sensor->name }}</p>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold text-dark"><i class="bi bi-info-circle me-2 text-primary"></i>Sensor Details</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.sensors.update', $sensor) }}" id="sensorForm">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Sensor ID</label>
                                    <input type="text" class="form-control bg-light" value="{{ $sensor->sensor_id }}" disabled>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label fw-semibold">Sensor Name</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $sensor->name) }}" required>
                                    @error('name')
                                    <div class="text-danger mt-1 small">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <label for="location_name" class="form-label fw-semibold">Location Name</label>
                            <input type="text" class="form-control @error('location_name') is-invalid @enderror" id="location_name" name="location_name" value="{{ old('location_name', $sensor->location_name) }}" required>
                            @error('location_name')
                            <div class="text-danger mt-1 small">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="card bg-light border-0 rounded-3 mb-4">
                            <div class="card-body">
                                <h6 class="fw-bold mb-3"><i class="bi bi-geo me-2"></i>Geographic Coordinates</h6>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="latitude" class="form-label">Latitude</label>
                                            <input type="number" step="0.0000001" class="form-control @error('latitude') is-invalid @enderror" id="latitude" name="latitude" value="{{ old('latitude', $sensor->latitude) }}" required>
                                            @error('latitude')
                                            <div class="text-danger mt-1 small">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="longitude" class="form-label">Longitude</label>
                                            <input type="number" step="0.0000001" class="form-control @error('longitude') is-invalid @enderror" id="longitude" name="longitude" value="{{ old('longitude', $sensor->longitude) }}" required>
                                            @error('longitude')
                                            <div class="text-danger mt-1 small">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div id="map-container" class="position-relative">
                                    <div id="map" class="rounded-3 shadow-sm" style="height: 400px;"></div>
                                    <div class="map-overlay position-absolute top-0 end-0 m-3">
                                        <button type="button" id="centerMap" class="btn btn-light btn-sm shadow-sm" title="Reset Map View">
                                            <i class="bi bi-arrows-move"></i>
                                        </button>
                                    </div>
                                    <div class="position-absolute bottom-0 start-0 m-3 bg-white p-2 rounded shadow-sm">
                                        <small><i class="bi bi-info-circle me-1"></i> Click on the map to change the sensor location</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="form-check form-switch">
                                <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1" {{ old('is_active', $sensor->is_active) ? 'checked' : '' }}>
                                <label class="form-check-label fw-semibold" for="is_active">Sensor Active Status</label>
                                <div class="form-text">Toggle to enable or disable data collection</div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="d-grid gap-3">
                <button type="submit" form="sensorForm" class="btn btn-primary btn-lg py-3 fw-semibold">
                    <i class="bi bi-check2-circle me-2"></i> Update Sensor
                </button>
                <a href="{{ route('admin.sensors.index') }}" class="btn btn-outline-secondary">
                    Cancel
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const sensorLat = {{ $sensor->latitude }};
        const sensorLng = {{ $sensor->longitude }};
        
        const map = L.map('map').setView([sensorLat, sensorLng], 14);
        
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);
        
        let marker = L.marker([sensorLat, sensorLng], {draggable: true}).addTo(map);
        
        marker.on('dragend', function(e) {
            const position = e.target.getLatLng();
            updateFormCoordinates(position.lat, position.lng);
        });
        
        map.on('click', function(e) {
            const position = e.latlng;
            marker.setLatLng(position);
            updateFormCoordinates(position.lat, position.lng);
        });
        
        function updateFormCoordinates(lat, lng) {
            document.getElementById('latitude').value = lat.toFixed(7);
            document.getElementById('longitude').value = lng.toFixed(7);
        }
        
        document.getElementById('centerMap').addEventListener('click', function() {
            map.setView([sensorLat, sensorLng], 14);
        });
    });
</script>
@endsection

@section('styles')
<style>
    #map {
        width: 100%;
        margin-bottom: 15px;
        border: 1px solid #eee;
        z-index: 1;
    }
    
    .map-overlay {
        z-index: 2;
    }
    
    .form-label {
        font-size: 0.95rem;
    }
</style>
@endsection
