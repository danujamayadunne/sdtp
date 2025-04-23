<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Colombo Air Quality Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8f9fa;
            color: #212529;
        }
        
        .navbar {
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            background-color: black;
            height: 3.9em;
        }
        
        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 6px 18px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            margin-bottom: 24px;
        }
        
        .card:hover {
            box-shadow: 0 10px 24px rgba(0,0,0,0.15);
            transform: translateY(-3px);
        }
        
        .card-header {
            background-color: #ffffff;
            border-bottom: 1px solid rgba(0,0,0,0.08);
            border-radius: 12px 12px 0 0 !important;
            padding: 20px 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
        }
        
        .card-header h4 {
            margin: 0;
            font-weight: 600;
            color: #333;
            font-size: 1.25rem;
        }
        
        .card-body {
            padding: 9px;
        }
        
        #map {
            height: 600px;
            width: 100%;
            border-radius: 8px;
            box-shadow: inset 0 0 5px rgba(0,0,0,0.1);
        }
        
        .aqi-legend {
            padding: 16px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 15px rgba(0,0,0,0.15);
        }
        
        .legend-item {
            display: flex;
            align-items: center;
            margin-bottom: 8px;
        }
        
        .color-box {
            width: 24px;
            height: 24px;
            margin-right: 12px;
            border-radius: 4px;
        }
        
        .btn-group {
            gap: 5px;
        }
        
        .period-btn {
            border-radius: 8px !important;
            padding: 8px 16px;
            font-weight: 500;
            transition: all 0.2s ease;
        }
        
        .period-btn.active, .period-btn:hover {
            background-color: #4361ee;
            color: white;
            border-color: #4361ee;
            box-shadow: 0 3px 10px rgba(67, 97, 238, 0.3);
        }
        
        .sensor-card {
            background: #fff;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        }
        
        .sensor-info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 16px;
            margin-bottom: 24px;
        }
        
        .sensor-stat {
            padding: 16px;
            border-radius: 10px;
            background-color: #f8f9fa;
            text-align: center;
        }
        
        .sensor-stat h3 {
            font-size: 24px;
            font-weight: 700;
            margin: 8px 0;
        }
        
        .sensor-stat p {
            color: #6c757d;
            margin: 0;
        }
        
        #chart-container {
            height: 400px;
            position: relative;
            padding: 16px;
            background-color: #fff;
            border-radius: 10px;
            margin-top: 24px;
        }
        
        .chart-controls {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 16px;
        }
        
        .container {
            max-width: 1280px;
            padding: 24px;
        }
        
        .data-point {
            display: flex;
            align-items: center;
            margin-bottom: 8px;
        }
        
        .data-point i {
            margin-right: 8px;
            color: #4361ee;
        }
        
        @media (max-width: 768px) {
            .card-header {
                flex-direction: column;
                align-items: flex-start;
            }
            
            .btn-group {
                margin-top: 12px;
                width: 100%;
                justify-content: space-between;
            }
            
            .period-btn {
                flex: 1;
            }
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="/">
                <i class="fas fa-wind me-2"></i>
                Colombo Air Quality Dashboard
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">
                            <i class="fas fa-user-shield me-1"></i> Admin Login
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4><i class="fas fa-map-marked-alt me-2"></i>Real-time Air Quality Map - Colombo Metropolitan Area</h4>
                    </div>
                    <div class="card-body">
                        <div id="map"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4><i class="fas fa-chart-line me-2"></i>Sensor Analytics</h4>
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-outline-primary period-btn active" data-period="day">
                                <i class="far fa-clock me-1"></i> Last 24 Hours
                            </button>
                            <button type="button" class="btn btn-outline-primary period-btn" data-period="week">
                                <i class="far fa-calendar-alt me-1"></i> Last Week
                            </button>
                            <button type="button" class="btn btn-outline-primary period-btn" data-period="month">
                                <i class="far fa-calendar-check me-1"></i> Last Month
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="sensor-info">
                            <p class="text-center text-muted">
                                <i class="fas fa-info-circle me-2"></i>
                                Select a sensor from the map to view detailed analytics
                            </p>
                        </div>
                        <div id="chart-container" style="display: none;">
                            <div class="chart-controls">
                                <div class="form-check form-switch me-3">
                                    <input class="form-check-input" type="checkbox" id="toggle-line-type" hidden>
                                </div>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="toggle-fill" checked hidden>
                                </div>
                            </div>
                            <canvas id="aqi-chart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-gradient"></script>
    <script src="https://cdn.jsdelivr.net/npm/luxon@3.0.1/build/global/luxon.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-luxon@1.2.1/dist/chartjs-adapter-luxon.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const map = L.map('map').setView([6.9271, 79.8612], 12);
            
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map);
            
            const legend = L.control({position: 'bottomright'});
            legend.onAdd = function() {
                const div = L.DomUtil.create('div', 'aqi-legend');
                div.innerHTML = '<h6 class="fw-bold mb-2">Air Quality Index</h6>';
                
                @foreach($thresholds as $threshold)
                div.innerHTML += `
                    <div class="legend-item">
                        <div class="color-box" style="background: {{ $threshold->color_code }}"></div>
                        <span>{{ $threshold->category }} ({{ $threshold->min_value }}-{{ $threshold->max_value }})</span>
                    </div>`;
                @endforeach
                
                return div;
            };
            legend.addTo(map);
            
            const markers = {};
            let currentSensorId = null;
            let currentPeriod = 'day';
            let aqiChart = null;
            
            let chartOptions = {
                tension: 0.4,
                fill: true
            };
            
            document.getElementById('toggle-line-type').addEventListener('change', function() {
                chartOptions.tension = this.checked ? 0.4 : 0;
                if (currentSensorId) fetchSensorHistory(currentSensorId, currentPeriod);
            });
            
            document.getElementById('toggle-fill').addEventListener('change', function() {
                chartOptions.fill = this.checked;
                if (currentSensorId) fetchSensorHistory(currentSensorId, currentPeriod);
            });
            
            fetchSensors();
            
            document.querySelectorAll('.period-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    currentPeriod = this.dataset.period;
                    document.querySelectorAll('.period-btn').forEach(b => b.classList.remove('active'));
                    this.classList.add('active');
                    
                    if (currentSensorId) {
                        fetchSensorHistory(currentSensorId, currentPeriod);
                    }
                });
            });
            
            function fetchSensors() {
                fetch('/api/sensors')
                    .then(response => response.json())
                    .then(data => {
                        data.forEach(sensor => {
                            if (sensor.latest_reading) {
                                const marker = L.circleMarker([sensor.latitude, sensor.longitude], {
                                    radius: 12,
                                    fillColor: sensor.latest_reading.color,
                                    color: '#000',
                                    weight: 1,
                                    opacity: 1,
                                    fillOpacity: 0.8
                                });
                                
                                marker.bindPopup(`
                                    <div class="text-center">
                                        <h6 class="fw-bold">${sensor.name}</h6>
                                        <p class="mb-2">${sensor.location}</p>
                                        <div style="font-size: 24px; font-weight: bold;">${sensor.latest_reading.aqi_value}</div>
                                        <div style="color:${sensor.latest_reading.color}; font-weight: 600;">${sensor.latest_reading.category}</div>
                                        <small class="text-muted">${sensor.latest_reading.time}</small>
                                    </div>
                                `);
                                
                                marker.on('click', function() {
                                    currentSensorId = sensor.id;
                                    fetchSensorHistory(sensor.id, currentPeriod);
                                });
                                
                                marker.addTo(map);
                                markers[sensor.id] = marker;
                            }
                        });
                    });
            }
            
            function fetchSensorHistory(sensorId, period) {
                document.getElementById('sensor-info').innerHTML = `
                    <div class="d-flex justify-content-center">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                `;
                
                fetch(`/api/sensors/${sensorId}/history?period=${period}`)
                    .then(response => response.json())
                    .then(data => {
                        displaySensorInfo(data.sensor, data.readings);
                        createOrUpdateChart(data.readings);
                        document.getElementById('chart-container').style.display = 'block';
                    });
            }
            
            function getAqiCategoryInfo(aqi) {
                if (aqi <= 50) return {color: '#00e400', category: 'Good'};
                if (aqi <= 100) return {color: 'orange', category: 'Moderate'};
                if (aqi <= 200) return {color: 'red', category: 'Unhealthy'};
                return {color: '#7e0023', category: 'Hazardous'};
            }
            
            function displaySensorInfo(sensor, readings) {
                const currentAqi = readings.length > 0 ? readings[readings.length - 1].aqi_value : 'N/A';
                const aqiValues = readings.map(r => r.aqi_value);
                const maxAqi = Math.max(...aqiValues);
                const minAqi = Math.min(...aqiValues);
                
                const categoryInfo = getAqiCategoryInfo(currentAqi);
                
                document.getElementById('sensor-info').innerHTML = `
                    <div class="sensor-card">
                        <h5 class="mb-3 fw-bold"><i class="fas fa-broadcast-tower me-2"></i>${sensor.name}</h5>
                        
                        <div class="data-point">
                            <i class="fas fa-map-marker-alt"></i>
                            <span>${sensor.location}</span>
                        </div>
                        
                        <div class="sensor-info-grid mt-4">
                            <div class="sensor-stat" style="border-left: 4px solid ${categoryInfo.color}">
                                <p>Current AQI</p>
                                <h3>${currentAqi}</h3>
                                <span style="color:${categoryInfo.color}; font-weight: 600;">${categoryInfo.category}</span>
                            </div>
                            
                            <div class="sensor-stat">
                                <p>Maximum</p>
                                <h3>${maxAqi}</h3>
                                <span class="text-muted">Peak value</span>
                            </div>
                            
                            <div class="sensor-stat">
                                <p>Minimum</p>
                                <h3>${minAqi}</h3>
                                <span class="text-muted">Lowest value</span>
                            </div>
                        </div>
                    </div>
                `;
            }
            
            function createOrUpdateChart(readings) {
                const ctx = document.getElementById('aqi-chart').getContext('2d');
                
                const data = readings.map(r => ({
                    x: new Date(r.time),
                    y: r.aqi_value
                }));
                
                if (aqiChart) {
                    aqiChart.data.datasets[0].data = data;
                    aqiChart.data.datasets[0].tension = chartOptions.tension;
                    aqiChart.data.datasets[0].fill = chartOptions.fill;
                    aqiChart.update();
                } else {
                    aqiChart = new Chart(ctx, {
                        type: 'line',
                        data: {
                            datasets: [{
                                label: 'AQI Value',
                                data: data,
                                borderColor: '#4361ee',
                                backgroundColor: 'rgba(67, 97, 238, 0.2)',
                                borderWidth: 3,
                                pointBackgroundColor: '#4361ee',
                                pointBorderColor: '#fff',
                                pointRadius: 4,
                                pointHoverRadius: 6,
                                tension: chartOptions.tension,
                                fill: chartOptions.fill
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    display: false
                                },
                                tooltip: {
                                    backgroundColor: 'rgba(0,0,0,0.8)',
                                    titleFont: {
                                        size: 14,
                                        weight: 'bold'
                                    },
                                    bodyFont: {
                                        size: 13
                                    },
                                    padding: 12,
                                    displayColors: false,
                                    callbacks: {
                                        title: function(tooltipItems) {
                                            const date = new Date(tooltipItems[0].parsed.x);
                                            return date.toLocaleString();
                                        }
                                    }
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    title: {
                                        display: true,
                                        text: 'AQI Value',
                                        font: {
                                            weight: 'bold'
                                        }
                                    },
                                    grid: {
                                        color: 'rgba(0,0,0,0.05)'
                                    }
                                },
                                x: {
                                    type: 'time',
                                    time: {
                                        unit: currentPeriod === 'day' ? 'hour' : 
                                              currentPeriod === 'week' ? 'day' : 'week'
                                    },
                                    title: {
                                        display: true,
                                        text: 'Time',
                                        font: {
                                            weight: 'bold'
                                        }
                                    },
                                    grid: {
                                        color: 'rgba(0,0,0,0.05)'
                                    }
                                }
                            },
                            interaction: {
                                intersect: false,
                                mode: 'index'
                            },
                            animations: {
                                tension: {
                                    duration: 1000,
                                    easing: 'linear'
                                }
                            }
                        }
                    });
                }
            }
        });
    </script>
</body>
</html>
