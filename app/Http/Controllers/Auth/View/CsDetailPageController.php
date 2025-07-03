<?php

namespace App\Http\Controllers\Auth\View;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use packages\BlackList\UseCase\Validate\CheckBlackListServiceInterface;
use packages\Consumer\UseCase\Read\GetCsDataServiceInterface;
use packages\Schedule\UseCase\Read\GetScheduleServiceInterface;
use packages\User\UseCase\Read\GetUserServiceInterface;
use packages\Worksheet\UseCase\Read\GetWsDataServiceInterface;
use packages\Consumer\UseCase\Validate\ValidateConsumerDataServiceInterface;

class CsDetailPageController extends Controller
{
    private $CheckBlackListService;
    private $GetCsDataService;
    private $GetScheduleService;
    private $GetUserService;
    private $GetWsDataService;
    private $ValidateConsumerDataService;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        CheckBlackListServiceInterface       $CheckBlackListService,
        GetCsDataServiceInterface            $GetCsDataService,
        GetScheduleServiceInterface          $GetScheduleService,
        GetUserServiceInterface              $GetUserService,
        GetWsDataServiceInterface            $GetWsDataService,
        ValidateConsumerDataServiceInterface $ValidateConsumerDataService
    )
    {
        $this->middleware('auth');
        $this->CheckBlackListService       = $CheckBlackListService;
        $this->GetCsDataService            = $GetCsDataService;
        $this->GetScheduleService          = $GetScheduleService;
        $this->GetUserService              = $GetUserService;
        $this->GetWsDataService            = $GetWsDataService;
        $this->ValidateConsumerDataService = $ValidateConsumerDataService;
    }

    public function index(Request $request)
    {
        $user      = Auth::user();
        $atsCsId   = $request->get('id');
        $atsCsId   = urldecode($atsCsId);
        $getCsData = $this->GetCsDataService->getConsumerData('ats_consumer_id', $atsCsId, true);
        if (empty($getCsData)) {
            return view('auth.404');
        }
        $cskeys             = config('Consumer.consumerDataTitles');
        $decideSchedule     = $this->GetScheduleService->getDecideSchedule($atsCsId, 'japanese');
        $decideSchedule     = !empty($decideSchedule) ? $decideSchedule : '-';
        $decideScheduleDate = $this->GetScheduleService->getDecideSchedule($atsCsId, 'None');
        $decideSchedule     = !empty($decideSchedule) ? $decideScheduleDate : null;
        $answerData         = $this->GetWsDataService->getWsAnswer($atsCsId, true);
        $freeScheduleDatas  = $this->GetScheduleService->getFreeScheduleDatas(0, $getCsData['user_id']);
        $userName           = $this->GetUserService->getUserValById($getCsData['user_id'], 'name');

        
        $isValidList = $this->ValidateConsumerDataService->getIsValidList([
                'tel'  => $getCsData['tel'],
                'mail' => $getCsData['mail']
        ]);

        $isBlackList = $this->CheckBlackListService->checkBlackList(
            $getCsData['tel' ],
            $getCsData['mail'],
            $isValidList
        );

        $birthday = $getCsData['birthday'];
        if (preg_match('/^\d{4}[\/\-\.]?\d{1,2}[\/\-\.]?\d{1,2}$/', $birthday)) {
            if (!strpos($birthday, '/') && !strpos($birthday, '-') && !strpos($birthday, '.')) {
                $birthday = substr($birthday, 0, 4) . '/' . substr($birthday, 4, 2) . '/' . substr($birthday, 6, 2);
            }
            $birthDate             = Carbon::createFromFormat('Y/m/d', str_replace(['-', '.'], '/', $birthday));
            $currentDate           = Carbon::now();
            $age                   = $birthDate->diffInYears($currentDate);
            $getCsData['birthday'] = $birthDate->format('Y年n月j日');
        } else {
            $age = '';
        }

       
        $data = [
            'atsCsId'           => $atsCsId,
            'versionParam'      => config('app.versionParam'),
            'apiToken'          => $user->api_token,
            'getCsData'         => $getCsData,
            'authority'         => $user->authority,
            'cskeys'            => $cskeys,
            'decideSchedule'    => $decideSchedule,
            'answerDataHtml'    => $answerData,
            'statusColors'      => config('Consumer.statusList.statusColors'),
            'freeScheduleDatas' => $freeScheduleDatas,
            'isBlackList'       => $isBlackList,
            'userName'          => $userName,
            'age'               => $age,
        ];

        return view(
            'auth.company.consumerDetail', $data
        );
    }
}



