<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Carbon\CarbonPeriod;
use App\Models\Calendar;

class CalendarSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $from = now()->startOfYear();
        $to = now()->addYear()->endOfYear();

        $period = CarbonPeriod::create($from, $to);

        foreach ($period as $date) {
            Calendar::updateOrCreate([
                'date' => $date->toDateString(),
            ], [
                'date' => $date->toDateString(),
                'weekend' => $date->isWeekend()
            ]);
        }
    }
}
