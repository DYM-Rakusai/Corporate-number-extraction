<?php
declare(strict_types=1);
namespace packages\Schedule\Domain\Get;

use Illuminate\Support\Carbon;

class GetAnotherScheduleService
{
    private $dayList;
    private $nowCarbon;

    public function __construct(
    )
    {
        $nowStamp = Carbon::now('Asia/Tokyo');
        $this->nowCarbon = Carbon::parse($nowStamp);
        $this->dayList = [
            '日',
            '月',
            '火',
            '水',
            '木',
            '金',
            '土'
        ];
    }

    public function getAnotherDates($startDateCarbon, $endDateCarbon)
    {
        $anotherDates = [];
        $holidays     = config('Calender.holidays');
        for($index = 0; $index <= 30; $index++) {
            if($endDateCarbon->gt($startDateCarbon)) {
                if ($startDateCarbon->isWeekend() || in_array($startDateCarbon->format('Y-m-d'), $holidays)) {
                    $startDateCarbon->addDays(1);
                    continue;
                }
                $anotherDate = [
                    'year'      => $startDateCarbon->format('Y'),
                    'month'     => $startDateCarbon->format('m'),
                    'day'       => $startDateCarbon->format('d'),
                    'dayOfWeek' => $this->dayList[$startDateCarbon->dayOfWeek]
                ];
                $anotherDates[] = $anotherDate;
                $startDateCarbon->addDays(1);
            } else {
            	break;
            }
        }
        return $anotherDates;
    }
}

