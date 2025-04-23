@extends('layouts.admin')

@section('content')
<div class="container-fluid">

<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">
        <div class="mb-3 mb-md-0">
            <h1 class="h3 fw-bold text-black mb-1">Alert Thresholds</h1>
            <p class="text-muted">Configure air quality categories and trigger levels</p>
        </div>
        <a href="{{ route('admin.alert-thresholds.create') }}" class="btn btn-primary d-flex align-items-center rounded-5">
            <i class="bi bi-plus-circle me-2"></i> Add New Threshold
        </a>
    </div>

    <div class="card overflow-hidden">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-bold text-dark">Thresholds Configuration</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-3">Category</th>
                            <th>Range</th>
                            <th>Color</th>
                            <th class="text-end pe-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($thresholds as $threshold)
                        <tr>
                            <td class="ps-3 fw-semibold">{{ $threshold->category }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <span class="badge bg-light text-dark me-2">{{ $threshold->min_value }}</span>
                                    <i class="bi bi-arrow-right text-muted mx-1"></i>
                                    <span class="badge bg-light text-dark">{{ $threshold->max_value }}</span>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div style="width: 36px; height: 36px; background-color: {{ $threshold->color_code }}; border-radius: 6px; margin-right: 10px; border: 1px solid rgba(0,0,0,0.1);"></div>
                                    <code>{{ $threshold->color_code }}</code>
                                </div>
                            </td>
                            <td class="text-end pe-3">
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-light border-0" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="bi bi-three-dots-vertical"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                                        <li>
                                            <form method="POST" action="{{ route('admin.alert-thresholds.destroy', $threshold) }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="dropdown-item text-danger" onclick="return confirm('Are you sure you want to delete this threshold?');">
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
                            <td colspan="6" class="text-center py-5">
                                <div class="d-flex flex-column align-items-center">
                                    <i class="bi bi-exclamation-diamond text-muted" style="font-size: 3rem;"></i>
                                    <h5 class="mt-3">No alert thresholds found</h5>
                                    <p class="text-muted">Create thresholds to categorize air quality levels</p>
                                    <a href="{{ route('admin.alert-thresholds.create') }}" class="btn btn-primary mt-2">
                                        <i class="bi bi-plus-circle me-1"></i> Add Threshold
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-white py-3">
            <div class="d-flex justify-content-between align-items-center">
                <div class="text-muted small">Showing {{ $thresholds->count() }} thresholds</div>
            </div>
        </div>
    </div>
</div>
@endsection
