<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Policy;

class GenerateReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'report:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate report.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $policies = Policy::get();

        foreach ($policies as $policy) {
            $spaces = str_repeat(' ', 10);
            $contents .= $policy->col_1.$spaces.$policy->col_2.$spaces.$policy->col_3.$spaces.$policy->col_4.$spaces.$policy->col_5."\n";
        }

        // dd($contents);

        $fileName = "CC_HLA.LTA.VRL_DB.148.txt";
        $path = "reports/{$fileName}";

        \Storage::put($path, $contents);

        $this->info('Complete.');
    }
}
