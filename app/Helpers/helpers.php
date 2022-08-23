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

        return $calendar ? $calendar->date : now()->subDay()->toDateString();
    }
}

if (! function_exists('today_is_working_day')) {

    function today_is_non_working_day($date = null)
    {
        $date = $date ?? now()->toDateString();
        
        $calendar = Calendar::where('date', $date)->first();
        
        return $calendar->weekend != true || $calendar->holiday != true;
    }
}