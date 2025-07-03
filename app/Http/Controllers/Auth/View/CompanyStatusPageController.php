<?php

namespace App\Http\Controllers\Auth\View;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use packages\Consumer\Domain\Service\CompanyStatusPageService;
use packages\Consumer\UseCase\Read\GetCsDataServiceInterface;
use packages\Worksheet\UseCase\Validate\IsAnswerServiceInterface;
use packages\Url\UseCase\Validate\UrlParamServiceInterface;
use packages\Consumer\UseCase\Validate\ValidateConsumerDataServiceInterface;

class CompanyStatusPageController extends Controller
{
    private $CompanyStatusPageService;
    private $GetCsDataService;
    private $IsAnswerService;
    private $UrlParamService;
    private $ValidateConsumerDataService;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        CompanyStatusPageService             $CompanyStatusPageService,
        GetCsDataServiceInterface            $GetCsDataService,
        IsAnswerServiceInterface             $IsAnswerService,
        UrlParamServiceInterface             $UrlParamService,
        ValidateConsumerDataServiceInterface $ValidateConsumerDataService
    )
    {
        $this->middleware('auth');
        $this->CompanyStatusPageService    = $CompanyStatusPageService;
        $this->GetCsDataService            = $GetCsDataService;
        $this->IsAnswerService             = $IsAnswerService;
        $this->UrlParamService             = $UrlParamService;
        $this->ValidateConsumerDataService = $ValidateConsumerDataService;
    }

    public function index(Request $request)
    {
        \Log::info($request);
        $consumerId = $request->get('consumerId');
        $cpStatus   = $request->get('status');

        $isConsumerExist = $this->ValidateConsumerDataService->isExistConsumer('id', $consumerId);
        if ($isConsumerExist == false) {
            return;
        }

        $consumerData   = $this->GetCsDataService->getConsumerData('id', $consumerId);
        $atsConsumerId  = $consumerData['ats_consumer_id'];
        $hashCs         = $request->get('hashCs');
        $isValidUrl     = $this->UrlParamService->isValidUrl([$atsConsumerId => $hashCs]);

        if ($isValidUrl === false) {
            \Log::error('パラメータに不備');
            return;
        }

        $companyStatusPageData = $this->CompanyStatusPageService->getCompanyStatusPageData($consumerData, $cpStatus);

        if ($cpStatus == 'decideForCompany') {
            if (empty($companyStatusPageData['decideSchedule'])) {
                \Log::error('確定日程がないエラー： ' . $request->fullUrl());
            }
            return view('cpStatusPage.cpDecidePage', $companyStatusPageData);
        } elseif ($cpStatus == 'adjustForCompany') {
            $isNotAnswer = $this->IsAnswerService->checkCompanyAnswer($atsConsumerId);
            if ($isNotAnswer === false) {
                return view('public.freePage', [
                    'pageTitle'   => config('Text.freePageTitle.companyStatusPageTitle'),
                    'pageContent' => config('Text.freePageMessage.companyStatusPageContent')
                ]);
            }
            return view('cpStatusPage.cpAdjustPage', $companyStatusPageData);
        } else {
            \Log::error('Companyステータス想定外エラー');
            return;
        }

    }
}


