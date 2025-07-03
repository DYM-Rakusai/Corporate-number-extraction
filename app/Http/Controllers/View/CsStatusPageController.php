<?php

namespace App\Http\Controllers\View;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use packages\Consumer\Domain\Service\CsStatusPageService;
use packages\Consumer\UseCase\Read\GetCsDataServiceInterface;
use packages\Resend\UseCase\Update\ResendUpdateServiceInterface;
use packages\Url\UseCase\Validate\UrlParamServiceInterface;
use packages\Consumer\UseCase\Validate\ValidateConsumerDataServiceInterface;

class CsStatusPageController extends Controller
{
    private $CsStatusPageService;
    private $GetCsDataService;
    private $ResendUpdateService;
    private $UrlParamService;
    private $ValidateConsumerDataService;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        CsStatusPageService                  $CsStatusPageService,
        GetCsDataServiceInterface            $GetCsDataService,
        ResendUpdateServiceInterface         $ResendUpdateService,
        UrlParamServiceInterface             $UrlParamService,
        ValidateConsumerDataServiceInterface $ValidateConsumerDataService
    )
    {
        $this->CsStatusPageService         = $CsStatusPageService;
        $this->GetCsDataService            = $GetCsDataService;
        $this->ResendUpdateService         = $ResendUpdateService;
        $this->UrlParamService             = $UrlParamService;
        $this->ValidateConsumerDataService = $ValidateConsumerDataService;
    }

    public function index(Request $request)
    {
        \Log::info($request);
        $consumerId = $request->get('consumerId');
        $hashCs     = $request->get('hashCs');
        $csStatus   = $request->get('csStatus');

        $isConsumerExist = $this->ValidateConsumerDataService->isExistConsumer('id', $consumerId);
        if ($isConsumerExist == false) {
            return;
        }
        $consumerData  = $this->GetCsDataService->getConsumerData('id', $consumerId);
        $atsConsumerId = $consumerData['ats_consumer_id'];
        $isValidUrl    = $this->UrlParamService->isValidUrl([$atsConsumerId => $hashCs]);

        if ($isValidUrl === false) {
            \Log::error('パラメータに不備');
            return;
        }

        $resendStatusList = [
            'decide'  => 'decideInterview',
            'remind'  => 'remind',
            'failure' => 'failure'
        ];
        
        $this->ResendUpdateService->updateConfirmTime($atsCsId, $resendStatusList[$csStatus]);
        
        if($csStatus == 'decide' || $csStatus == 'remind') {
            $csStatusPageData = $this->CsStatusPageService->getCsStatusPageData($consumerData);

            if (empty($csStatusPageData['decideSchedule'])) {
                \Log::error('確定日程がないエラー： ' . $request->fullUrl());
            }
            return view("csStatusPage.{$csStatus}Page", $csStatusPageData);
        } elseif ($csStatus == 'failure') {
            return view('csStatusPage.failurePage', []);
        } else {
            \Log::error('ステータス想定外エラー');
            return;
        }
    }
}


