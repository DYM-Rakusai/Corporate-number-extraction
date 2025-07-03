<?php

namespace packages\Consumer\Domain\Service;

use packages\Schedule\UseCase\Read\GetScheduleServiceInterface;
use packages\User\UseCase\Read\GetUserServiceInterface;

class CsStatusPageService
{
    private $GetScheduleService;
    private $GetUserService;

    public function __construct(
        GetScheduleServiceInterface $GetScheduleService,
        GetUserServiceInterface     $GetUserService
    )
    {
        $this->GetScheduleService = $GetScheduleService;
        $this->GetUserService     = $GetUserService;
    }

    public function getCsStatusPageData($consumerData)
    {
        $decideSchedule = $this->GetScheduleService->getDecideSchedule($consumerData['ats_consumer_id'], 'japanese');
        $interviewUrl   = $this->GetUserService->getUserValById($consumerData['user_id'], 'interview_url');
        
        return [
            'decideSchedule' => $decideSchedule,
            'interviewUrl'   => $interviewUrl,
            'csName'         => $consumerData['name'],
            'interviewWay'   => $consumerData['interview_way']
        ];
    }
}