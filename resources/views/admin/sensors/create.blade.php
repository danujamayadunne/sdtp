@extends('layouts.admin')

@section('content')
<div class="container-fluid">
        <div class="mb-5">
            <h1 class="h3 fw-bold text-black mb-1">Add New Sensor</h1>
            <p class="text-muted">Configure and deploy a new air quality sensor</p>
        </div>


    <div class="row">
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold text-dark"><i class="bi bi-info-circle me-2 text-primary"></i>Sensor Details</h5>
                </div>
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('admin.sensors.store') }}" id="sensorForm">
                        @csrf
                        
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label fw-semibold">Sensor Name</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" placeholder="Enter sensor name" required>
                                    </div>
                                    @error('name')
                                    <div class="text-danger mt-1 small">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="location_name" class="form-label fw-semibold">Location Name</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control @error('location_name') is-invalid @enderror" id="location_name" name="location_name" value="{{ old('location_name') }}" placeholder="Location" required>
                                    </div>
                                    @error('location_name')
                                    <div class="text-danger mt-1 small">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="card bg-light border-0 rounded-3 mb-4">
                            <div class="card-body">
                                <h6 class="fw-bold mb-3"><i class="bi bi-geo me-2"></i>Geographic Coordinates</h6>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="latitude" class="form-label">Latitude</label>
                                            <input type="number" step="0.0000001" class="form-control @error('latitude') is-invalid @enderror" id="latitude" name="latitude" value="{{ old('latitude') }}" required>
                                            @error('latitude')
                                            <div class="text-danger mt-1 small">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="longitude" class="form-label">Longitude</label>
                                            <input type="number" step="0.0000001" class="form-control @error('longitude') is-invalid @enderror" id="longitude" name="longitude" value="{{ old('longitude') }}" required>
                                            @error('longitude')
                                            <div class="text-danger mt-1 small">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div id="map-container" class="position-relative">
                                    <div id="map" class="rounded-3 shadow-sm" style="height: 400px;"></div>
                                    <div class="map-overlay position-absolute top-0 end-0 m-3">
                                        <button type="button" id="centerMap" class="btn btn-light btn-sm shadow-sm" title="Center Map">
                                            <i class="bi bi-arrows-move"></i>
                                        </button>
                                    </div>
                                    <div class="position-absolute bottom-0 start-0 m-3 bg-white p-2 rounded shadow-sm">
                                        <small><i class="bi bi-info-circle me-1"></i> Click on the map to set the sensor location</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="form-check form-switch">
                                <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1" {{ old('is_active') ? 'checked' : '' }}>
                                <label class="form-check-label fw-semibold" for="is_active">Activate Sensor Immediately</label>
                                <div class="form-text">The sensor will start collecting data once deployed</div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
                    <div class="d-grid gap-3">
                        <button type="submit" form="sensorForm" class="btn btn-primary btn-lg py-3 fw-semibold">
                            <i class="bi bi-check2-circle me-2"></i> Save Sensor
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
        const map = L.map('map').setView([6.9271, 79.8612], 12);
        
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);
        
        let marker;
        const initialLat = document.getElementById('latitude').value || 6.9271;
        const initialLng = document.getElementById('longitude').value || 79.8612;
        
        if (initialLat && initialLng) {
            marker = L.marker([initialLat, initialLng], {draggable: true}).addTo(map);
            updateFormCoordinates(initialLat, initialLng);
        }
        
        function onMarkerDrag(e) {
            const position = e.target.getLatLng();
            updateFormCoordinates(position.lat, position.lng);
        }
        
        map.on('click', function(e) {
            const position = e.latlng;
            
            if (marker) {
                marker.setLatLng(position);
            } else {
                marker = L.marker(position, {draggable: true}).addTo(map);
                marker.on('dragend', onMarkerDrag);
            }
            
            updateFormCoordinates(position.lat, position.lng);
        });
        
        function updateFormCoordinates(lat, lng) {
            document.getElementById('latitude').value = lat.toFixed(7);
            document.getElementById('longitude').value = lng.toFixed(7);
        }
        
        document.getElementById('centerMap').addEventListener('click', function() {
            map.setView([initialLat, initialLng], 12);
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
