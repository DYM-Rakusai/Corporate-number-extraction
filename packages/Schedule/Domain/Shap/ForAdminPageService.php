<?php
declare(strict_types=1);
namespace packages\Schedule\Domain\Shap;

use Illuminate\Support\Carbon;
use packages\Consumer\UseCase\Read\GetCsDataServiceInterface;

class ForAdminPageService
{
    private $GetCsDataService;

    public function __construct(
        GetCsDataServiceInterface $GetCsDataService
    )
    {
        $this->GetCsDataService = $GetCsDataService;
    }

    public function shapForAdminPage($nowScheduleDatas)
    {
        $scheduleInfoArray = [];
        foreach($nowScheduleDatas as $nowScheduleData) {
            $scheduleId      = $nowScheduleData->id;
            $startDate       = $nowScheduleData->schedule;
            $isFilled        = $nowScheduleData->is_filled;
            $atsConsumerId   = $nowScheduleData->ats_consumer_id;
            $getConsumerData = $this->GetCsDataService->getConsumerData('ats_consumer_id', $atsConsumerId);
            $forEndCarbon    = new Carbon($startDate);
            $endCarbon       = $forEndCarbon->addMinutes(30);
            $endDate         = $endCarbon->format('Y-m-d H:i');
            $scheduleInfo    = [
                'title' => $getConsumerData['name'] ?? '',
                'start' => $startDate,
                'end'   => $endDate
            ];
            if( $isFilled != 0 ) {
                $id                              = 'buried-' . $scheduleId;
                $scheduleInfo['editable']        = false;
                $scheduleInfo['backgroundColor'] = '#ff1c1c';
                $scheduleInfo['borderColor']     = '#ff1c1c';
                $scheduleInfo['groupId']         = 'buried';
            } else {
                $id                              = 'free-' . $nowScheduleData->schedule;
                $scheduleInfo['backgroundColor'] = '#3490dc';
                $scheduleInfo['borderColor']     = '#3490dc';
                $scheduleInfo['groupId']         = $startDate;
            }
            $scheduleInfo['id']  = $id;
            $scheduleInfoArray[] = $scheduleInfo;
        }
        return $scheduleInfoArray;
    }
}




