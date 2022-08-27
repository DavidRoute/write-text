<?php

use App\Models\Calendar;


if (! function_exists('get_start_date')) {

    function get_start_date($date = null)
    {
        $date = $date ?? now()->toDateString();

        $calendar = Calendar::where('date', '<', $date)
                ->where('weekend', false)
                ->where('holiday', false)
                ->latest('id')
                ->first();

        return $calendar ? $calendar->date : now()->subDay();
    }
}

if (! function_exists('today_is_working_day')) {

    function today_is_working_day($date = null)
    {
        $date = $date ?? now()->toDateString();
        
        $calendar = Calendar::where('date', $date)->first();
        
        return $calendar->weekend != true && $calendar->holiday != true;
    }
}

/**
 * CSV to array
 */
if (! function_exists('csv_to_array')) {

    function csv_to_array($CSVFile)
    {
        if (! file_exists($CSVFile) || ! is_readable($CSVFile))
            return false;

        $header = null;
        $data = [];

        if (($handle = fopen($CSVFile,'r')) !== false) {
            while (($row = fgetcsv($handle, 1000, ',')) !== false) {
                if (! $header)
                    $header = $row;
                else
                    $data[] = array_combine($header, $row);
            }
            fclose($handle);
        }

        return $data;
    }
}
