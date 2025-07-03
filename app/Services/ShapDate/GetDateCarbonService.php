<?php
namespace App\Services\ShapDate;

use Illuminate\Support\Carbon;

class GetDateCarbonService
{
    public function __construct(
    )
    {
    }

    public function getDateCarbon($year, $month, $day, $time)
    {
        $dateStr    = $year . '-' . $month . '-' . $day . ' ' . $time;
        $dateCarbon = new Carbon($dateStr);
        return $dateCarbon;
    }
}


