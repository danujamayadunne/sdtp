@extends('layouts.admin')

@section('title', 'Create Alert Threshold')

@section('content')
<div class="container-fluid">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">
        <div class="mb-0">
            <h1 class="h3 fw-bold text-black mb-1">Create Alert Threshold</h1>
            <p class="text-muted">Define a new air quality category and its alert parameters</p>
        </div>
    </div>

    @if ($errors->any() || session('error') || session('success'))
    <div class="row mb-4">
        <div class="col-12">
            @if ($errors->any())
                <div class="alert alert-danger d-flex align-items-center" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2 fs-5"></i>
                    <div>
                        <ul class="mb-0 ps-3">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger d-flex align-items-center" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2 fs-5"></i>
                    <div>{{ session('error') }}</div>
                </div>
            @endif

            @if (session('success'))
                <div class="alert alert-success d-flex align-items-center" role="alert">
                    <i class="bi bi-check-circle-fill me-2 fs-5"></i>
                    <div>{{ session('success') }}</div>
                </div>
            @endif
        </div>
    </div>
    @endif

    <div class="row">
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold text-dark"><i class="bi bi-sliders me-2 text-primary"></i>Threshold Parameters</h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('admin.alert-thresholds.store') }}" method="POST" id="thresholdForm">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label fw-semibold">Name</label>
                                    <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                                    @error('name')
                                        <div class="text-danger mt-1 small">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="category" class="form-label fw-semibold">Category</label>
                                    <input type="text" name="category" id="category" class="form-control @error('category') is-invalid @enderror" value="{{ old('category') }}" required>
                                    @error('category')
                                        <div class="text-danger mt-1 small">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">Public label (e.g., "Good", "Moderate", "Unhealthy")</div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="card bg-light border-0 rounded-3 mb-4">
                            <div class="card-body">
                                <h6 class="fw-bold mb-3"><i class="bi bi-graph-up me-2"></i>Value Range</h6>
                                
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <label for="min_value" class="form-label">Min Value</label>
                                        <input type="number" step="0.01" name="min_value" id="min_value" class="form-control @error('min_value') is-invalid @enderror" value="{{ old('min_value') }}" required>
                                        @error('min_value')
                                            <div class="text-danger mt-1 small">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-4">
                                        <label for="max_value" class="form-label">Max Value</label>
                                        <input type="number" step="0.01" name="max_value" id="max_value" class="form-control @error('max_value') is-invalid @enderror" value="{{ old('max_value') }}" required>
                                        @error('max_value')
                                            <div class="text-danger mt-1 small">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-4">
                                        <label for="threshold_value" class="form-label">Threshold Value</label>
                                        <input type="number" step="0.01" name="threshold_value" id="threshold_value" class="form-control @error('threshold_value') is-invalid @enderror" value="{{ old('threshold_value') }}" required>
                                        @error('threshold_value')
                                            <div class="text-danger mt-1 small">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <label for="color_code" class="form-label fw-semibold">Color</label>
                        <div class="input-group">
                            <span class="input-group-text color-preview" id="colorPreview">&nbsp;</span>
                            <input type="text" name="color_code" id="color_code" class="form-control @error('color_code') is-invalid @enderror" value="{{ old('color_code', '#3498db') }}" placeholder="#RRGGBB" required>
                        </div>
                         @error('color_code')
                            <div class="text-danger mt-1 small">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Hexadecimal color code (e.g., #00FF00 for green)</div>
                    </form>
                </div>
            </div>
        </div>
      
        <div class="col-lg-4">
            <div class="d-grid gap-3">
                <button type="submit" form="thresholdForm" class="btn btn-primary btn-lg py-3 fw-semibold">
                    <i class="bi bi-check2-circle me-2"></i> Save Threshold
                </button>
                <a href="{{ route('admin.alert-thresholds.index') }}" class="btn btn-outline-secondary">
                    Cancel
                </a>
            </div>
        </div>
    </div>
</div>

<style>
    .color-preview {
        width: 40px;
        border-radius: 0.25rem 0 0 0.25rem;
    }
    
    .threshold-preview {
        transition: all 0.3s ease;
    }
    
    .form-label {
        font-size: 0.95rem;
    }
    
    @media (max-width: 992px) {
        .sticky-top {
            position: relative;
            top: 0;
        }
    }
</style>
@endsection
