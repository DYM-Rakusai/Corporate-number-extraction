<?php

namespace packages\Consumer\Domain\Service;

use Illuminate\Support\Facades\Auth;
use packages\Schedule\UseCase\Read\GetScheduleServiceInterface;
use packages\User\UseCase\Read\GetUserServiceInterface;
use packages\Worksheet\UseCase\Read\GetWsDataServiceInterface;

class CompanyStatusPageService
{
    private $GetScheduleService;
    private $GetUserService;
    private $GetWsDataService;

    public function __construct(
        GetScheduleServiceInterface $GetScheduleService,
        GetUserServiceInterface     $GetUserService,
        GetWsDataServiceInterface   $GetWsDataService
    )
    {
        $this->GetScheduleService = $GetScheduleService;
        $this->GetUserService     = $GetUserService;
        $this->GetWsDataService   = $GetWsDataService;
    }

    public function getCompanyStatusPageData($consumerData, $cpStatus)
    {
        $atsCsId       = $consumerData['ats_consumer_id'];
        $answerData    = $this->GetWsDataService->getWsAnswer($atsCsId, true);

        $companyStatusPageData = [
            'versionParam'  => config('app.versionParam'),
            'csName'        => $consumerData['name'],
            'nameKana'      => $consumerData['kana'],
            'csTel'         => $consumerData['tel' ],
            'csMail'        => $consumerData['mail'],
            'interviewWay'  => $consumerData['interview_way'],
            'getAnswerData' => $answerData,
            'atsCsId'       => $atsCsId
        ];

        if ($cpStatus == 'decideForCompany') {
            $companyStatusPageData['decideSchedule'] = $this->GetScheduleService->getDecideSchedule($atsCsId, 'japanese');
            $companyStatusPageData['userName']       = $this->GetUserService->getUserValById($consumerData['user_id'], 'name');
            $companyStatusPageData['interviewUrl']   = $this->GetUserService->getUserValById($consumerData['user_id'], 'interview_url');
        }else{
            $companyStatusPageData['userData']       = $this->GetUserService->getUserDataWithKeyValue('name', 'id');
        }

        return $companyStatusPageData;
    }
}