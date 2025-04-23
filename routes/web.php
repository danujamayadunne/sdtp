<?php

use App\Http\Controllers\Admin\AlertThresholdController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\SensorController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\AdminRegisterController;
use App\Http\Controllers\PublicDashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PublicDashboardController::class, 'index'])->name('home');

Route::prefix('api')->group(function () {
    Route::get('/sensors', [PublicDashboardController::class, 'getSensors']);
    Route::get('/sensors/{id}/history', [PublicDashboardController::class, 'getSensorHistory']);
});

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('admin/register', [AdminRegisterController::class, 'showRegistrationForm'])
    ->middleware('guest')
    ->name('admin.register');
    
Route::post('admin/register', [AdminRegisterController::class, 'register'])
    ->middleware('guest');

Route::prefix('admin')->middleware(['auth'])->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    
    Route::resource('sensors', SensorController::class);
    
    Route::resource('alert-thresholds', AlertThresholdController::class);
    
    Route::post('/simulation/start', [DashboardController::class, 'startSimulation'])->name('simulation.start');
    Route::post('/simulation/stop', [DashboardController::class, 'stopSimulation'])->name('simulation.stop');
});
