<?php

namespace App\Console\Commands;

use App\Services\DataSimulationService;
use Illuminate\Console\Command;

class GenerateAqiData extends Command
{
    protected $signature = 'aqi:generate';
    protected $description = 'Generate simulated AQI data for all active sensors';

    public function handle(DataSimulationService $simulationService)
    {
        $this->info('Starting AQI data generation...');
        
        $simulationService->generateReadings();
        
        $this->info('AQI data generation completed.');
        
        return Command::SUCCESS;
    }
}
