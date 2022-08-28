<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\Calendar;

class SyncHolidays extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:holidays';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync Singapore holidays date from online API.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->warn('Holidays syncing.');

        Calendar::where('holiday', true)->update(['holiday' => false]);

        $client = new \GuzzleHttp\Client();

        $res = $client->request('GET', "https://notes.rjchow.com/singapore_public_holidays/api/2022/data.json");
        $response = json_decode($res->getBody(), true);

        // $responseHolidays = Http::get("https://notes.rjchow.com/singapore_public_holidays/api/2022/data.json");
        // $responseHolidays

            collect($response)
                ->each(function ($response) {
                    $date = $response['Date'];
                    $name = $response['Name'];

                    Calendar::where('date', $date)->update([
                        'holiday' => true,
                        'holiday_name' => $name,
                    ]);
                });

        $this->info('Holidays sync successfully.');
    }
}
