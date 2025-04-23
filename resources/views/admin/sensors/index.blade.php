@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">
        <div class="mb-3 mb-md-0">
            <h1 class="h3 fw-bold text-dark">Sensors Management</h1>
            <p class="text-muted">Monitor and manage your sensor network</p>
        </div>
        <a href="{{ route('admin.sensors.create') }}" class="btn btn-primary d-flex align-items-center rounded-5">
            <i class="bi bi-plus-circle me-2"></i> Add New Sensor
        </a>
    </div>

    <div class="card shadow-sm overflow-hidden">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-bold text-dark">Sensor List</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-3">ID</th>
                            <th>Name</th>
                            <th>Location</th>
                            <th>Coordinates</th>
                            <th>Status</th>
                            <th>Latest Reading</th>
                            <th class="text-end pe-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($sensors as $sensor)
                        <tr>
                            <td class="ps-3 fw-bold">{{ $sensor->sensor_id }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="sensor-icon bg-light rounded-circle p-2 me-2 d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                                        <i class="bi bi-broadcast-pin"></i>
                                    </div>
                                    <span>{{ $sensor->name }}</span>
                                </div>
                            </td>
                            <td>{{ $sensor->location_name }}</td>
                            <td>
                                <small class="text-muted">
                                    <i class="bi bi-geo-alt me-1"></i>{{ $sensor->latitude }}, {{ $sensor->longitude }}
                                </small>
                            </td>
                            <td>
                                <span class="badge rounded-pill {{ $sensor->is_active ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger' }} px-3 py-2">
                                    <i class="bi {{ $sensor->is_active ? 'bi-check-circle-fill' : 'bi-x-circle-fill' }} me-1"></i>
                                    {{ $sensor->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td>
                                @if($latestReading = $sensor->latestReading())
                                <div class="d-flex flex-column">
                                    <div class="d-flex align-items-center">
                                        <div class="rounded-circle me-2" style="width: 12px; height: 12px; background-color: {{ \App\Models\AlertThreshold::getColorForValue($latestReading->aqi_value) }}"></div>
                                        <span class="fw-semibold" style="color: {{ \App\Models\AlertThreshold::getColorForValue($latestReading->aqi_value) }}">
                                            {{ $latestReading->aqi_value }}
                                        </span>
                                        <span class="ms-1">({{ $latestReading->category }})</span>
                                    </div>
                                    <small class="text-muted">
                                        <i class="bi bi-clock me-1"></i>{{ $latestReading->reading_time->diffForHumans() }}
                                    </small>
                                </div>
                                @else
                                <span class="badge bg-secondary-subtle text-secondary">No readings yet</span>
                                @endif
                            </td>
                            <td class="text-end pe-3">
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-light border-0" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="bi bi-three-dots-vertical"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                                        <li><a class="dropdown-item" href="{{ route('admin.sensors.edit', $sensor) }}"><i class="bi bi-pencil-square me-2"></i>Edit</a></li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li>
                                            <form method="POST" action="{{ route('admin.sensors.destroy', $sensor) }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="dropdown-item text-danger" onclick="return confirm('Are you sure you want to delete this sensor?');">
                                                    <i class="bi bi-trash me-2"></i>Delete
                                                </button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <div class="d-flex flex-column align-items-center">
                                    <i class="bi bi-radar text-muted" style="font-size: 3rem;"></i>
                                    <h5 class="mt-3">No sensors found</h5>
                                    <p class="text-muted">Add a new sensor to get started</p>
                                    <a href="{{ route('admin.sensors.create') }}" class="btn btn-primary mt-2">
                                        <i class="bi bi-plus-circle me-1"></i> Add Sensor
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-white border-top py-3 d-flex justify-content-between align-items-center">
            <div class="text-muted small">Showing {{ $sensors->count() }} sensors</div>
            <nav aria-label="Sensors pagination">
            </nav>
        </div>
    </div>
</div>

@endsection
