<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Policy;
use App\Models\Report;
use Illuminate\Support\Str;
use Carbon\Carbon;

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
        $lastReport = Report::latest('id')->first();

        $defaultSequenceNo = 148;
        $defaultJobSubmissionNo = 168;
        $sequenceNo = $lastReport ? $lastReport->sequence_no+1 : $defaultSequenceNo;
        $jobSubmissionNo = $lastReport ? $lastReport->job_submission_no+1 : $defaultJobSubmissionNo;
        $startDate = get_start_date()->format('Ymd');
        $endDate = now()->format('Ymd');
        $firstLine = "C{$startDate}{$endDate}I880G{$jobSubmissionNo}";

        $contents = $firstLine."\n";

        foreach ($policies as $policy) {
            $rowCount = str_pad($policy->RowCount, 7);
            $vehiclePlateNumber = str_pad($policy->VehiclePlateNumber, 12);
            $status = str_pad($policy->Status, 6);
            $commencementDate = str_pad($policy->CommencementDate, 8);
            $policyEndDate = str_pad($policy->PolicyEndDate, 8);
            $policyHolderFullName = str_pad($policy->PolicyHolderFullName, 66);
            $policyIssuedDate = str_pad($policy->PolicyIssuedDate, 8);
            $policyNo = str_pad($policy->PolicyNo, 30);
            $policyHolderNRIC = str_pad($policy->PolicyHolderNRIC, 21);
            $vehicleChasisNumber = str_pad($policy->VehicleChasisNumber, 25);

            $contents .= $rowCount.$vehiclePlateNumber.$status.$commencementDate.$policyEndDate.$policyHolderFullName.$policyIssuedDate.$policyNo.$policyHolderNRIC.$vehicleChasisNumber."\n";
        }

        $nCount = $policies->filter(function ($policy) {
            return Str::startsWith($policy->Status, 'N');
        })->count();
        $cCount = $policies->filter(function ($policy) {
            return Str::startsWith($policy->Status, 'C');
        })->count();
        $eCount = $policies->filter(function ($policy) {
            return Str::startsWith($policy->Status, 'E');
        })->count();
        $nCountPad = str_pad($nCount, 6, 0, STR_PAD_LEFT);
        $cCountPad = str_pad($cCount, 6, 0, STR_PAD_LEFT);
        $eCountPad = str_pad($eCount, 6, 0, STR_PAD_LEFT);

        $lastLine = "T{$startDate}{$endDate}I880G{$jobSubmissionNo}{$nCountPad}{$cCountPad}{$eCountPad}";
        $contents .= $lastLine;

        $fileName = "CC_HLA.LTA.VRL_DB.{$sequenceNo}.txt";
        $path = "reports/{$fileName}";
        \Storage::put($path, $contents);

        Report::create([
            'sequence_no'       => $sequenceNo,
            'job_submission_no' => $jobSubmissionNo,
            'file_path'         => $path,
            'start_date'        => Carbon::parse($startDate)->toDateString(),
            'end_date'          => Carbon::parse($endDate)->toDateString()
        ]);

        $this->info('Complete.');
    }
}
