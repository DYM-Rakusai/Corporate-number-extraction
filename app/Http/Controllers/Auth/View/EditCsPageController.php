<?php

namespace App\Http\Controllers\Auth\View;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use packages\Consumer\UseCase\Read\GetCsDataServiceInterface;
use packages\Schedule\UseCase\Read\GetScheduleServiceInterface;
use packages\User\UseCase\Read\GetUserServiceInterface;
use packages\Worksheet\UseCase\Read\GetWsDataServiceInterface;

class EditCsPageController extends Controller
{
    private $GetCsDataService;
    private $GetScheduleService;
    private $GetUserService;
    private $GetWsDataService;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        GetCsDataServiceInterface   $GetCsDataService,
        GetScheduleServiceInterface $GetScheduleService,
        GetUserServiceInterface     $GetUserService,
        GetWsDataServiceInterface   $GetWsDataService
    )
    {
        $this->middleware('auth');
        $this->GetCsDataService   = $GetCsDataService;
        $this->GetScheduleService = $GetScheduleService;
        $this->GetUserService     = $GetUserService;
        $this->GetWsDataService   = $GetWsDataService;
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

        $cskeys         = config('Consumer.consumerDataTitles');
        $decideSchedule = $this->GetScheduleService->getDecideSchedule($atsCsId, 'japanese');
        $decideSchedule = !empty($decideSchedule) ? $decideSchedule : '-';
        $answerData     = $this->GetWsDataService->getWsAnswer($atsCsId, true);
        $userName       =  $this->GetUserService->getUserValById($getCsData['user_id'], 'name');
        $birthday       = $getCsData['birthday'];

        if (preg_match('/^\d{4}[\/\-\.]?\d{1,2}[\/\-\.]?\d{1,2}$/', $birthday)) {
            if (!strpos($birthday, '/') && !strpos($birthday, '-') && !strpos($birthday, '.')) {
                $birthday = substr($birthday, 0, 4) . '/' . substr($birthday, 4, 2) . '/' . substr($birthday, 6, 2);
            }
            $birthDate   = Carbon::createFromFormat('Y/m/d', str_replace(['-', '.'], '/', $birthday));
            $currentDate = Carbon::now();
            $age         = $birthDate->diffInYears($currentDate);
            $getCsData['birthday'] = $birthDate->format('Y年n月j日');
        } else {
            $age = '';
        }

        $data = [
            'versionParam'   => config('app.versionParam'),
            'csEditConf'     => config('Consumer.csEditConf'),
            'statusList'     => config('Consumer.statusList.statusList'),
            'atsCsId'        => $atsCsId,
            'apiToken'       => $user->api_token,
            'authority'      => $user->authority,
            'getCsData'      => $getCsData,
            'cskeys'         => $cskeys,
            'decideSchedule' => $decideSchedule,
            'answerDataHtml' => $answerData,
            'userName'       => $userName,
            'age'            => $age,
        ];

        return view('auth.company.consumerEdit', $data);
    }
}



