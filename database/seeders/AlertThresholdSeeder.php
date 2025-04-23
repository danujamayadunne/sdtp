<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AlertThresholdSeeder extends Seeder
{
    public function run(): void
    {
        $thresholds = [
            [
                'category' => 'Good',
                'min_value' => 0,
                'max_value' => 50,
                'color_code' => '#00e400',
                'description' => 'Air quality is considered satisfactory, and air pollution poses little or no risk.'
            ],
            [
                'category' => 'Moderate',
                'min_value' => 51,
                'max_value' => 100,
                'color_code' => '#ffff00',
                'description' => 'Air quality is acceptable; however, for some pollutants there may be a moderate health concern.'
            ],
            [
                'category' => 'Unhealthy',
                'min_value' => 151,
                'max_value' => 200,
                'color_code' => '#ff0000',
                'description' => 'Everyone may begin to experience health effects.'
            ],
        ];

        DB::table('alert_thresholds')->insert($thresholds);
    }
}
