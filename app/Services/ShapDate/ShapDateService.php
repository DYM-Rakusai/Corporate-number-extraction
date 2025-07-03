<?php
namespace App\Services\ShapDate;

use Illuminate\Support\Carbon;

class ShapDateService
{
    public function __construct(
    )
    {
    }

    /**
     * string $dateObj
     * string $format 
     */
    public function shapDate($dateObj, $format, $addDays = 0)
    {
        if(empty($dateObj) || $dateObj == '-') {
            return '-';
        }
        $dateCarbon = new Carbon($dateObj);
        if($addDays != 0) {
            $dateCarbon->addDays($addDays);
        }
        return $dateCarbon->format($format);
    }

    public function shapTime($hour, $minute = 0)
    {
        $hourTime   = str_pad($hour, 2, '0', STR_PAD_LEFT);
        $minuteTime = str_pad($minute, 2, '0', STR_PAD_LEFT);
        $timeStr    = $hourTime . ':' . $minuteTime;
        return $timeStr;
    }

}





