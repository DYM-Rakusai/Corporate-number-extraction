<?php

namespace App\Http\Controllers\Auth\Api\Schedule;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use packages\Consumer\UseCase\Read\GetCsDataServiceInterface;
use packages\Schedule\UseCase\Read\GetScheduleServiceInterface;
use packages\Worksheet\UseCase\Read\GetWsDataServiceInterface;


class GetScheduleDataController extends Controller
{
    private $GetCsDataService;
    private $GetScheduleService;
    private $GetWsDataService;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        GetCsDataServiceInterface   $GetCsDataService,
        GetScheduleServiceInterface $GetScheduleService,
        GetWsDataServiceInterface   $GetWsDataService
    )
    {
        $this->middleware('auth');
        $this->GetCsDataService   = $GetCsDataService;
        $this->GetScheduleService = $GetScheduleService;
        $this->GetWsDataService   = $GetWsDataService;
    }

    public function store(Request $request)
    {
        \Log::info($request);

        if ($request->get('apiToken') !== config('app.api_token')) {
            throw new \Exception('APIトークンが不正です。');
            return;
        }
        $response   = ['status' => '500'];

        $scheduleId = $request->get('scheduleId');
        $user       = Auth::user();
        $userId     = $user->id;
        
        if ($user->authority == 'master') {
            $userId = $request['userId'] ?? 0;
        }

        $whereInfos = [
            'id'      => $scheduleId,
            'user_id' => $userId
        ];

        $scheduleObjs = $this->GetScheduleService->getScheduleDataByWhere($whereInfos);
        if($scheduleObjs->isEmpty()) {
            return $response;
        }
        $atsConsumerId = $scheduleObjs[0]->ats_consumer_id;
        $consumerData  = $this->GetCsDataService->getConsumerData('ats_consumer_id', $atsConsumerId, false);
      
        $wsAnswerHtml  = $this->GetWsDataService->getWsAnswer($atsConsumerId, true);
        $scheduleObjs[0]->schedule;
        $scheduleCarbon = new Carbon($scheduleObjs[0]->schedule);

        $response = [
            'status'        => '200',
            'consumerData'  => $consumerData,
            'interviewDate' => $scheduleCarbon->format('Y-m-d H:i'),
            'wsAnswerHtml'  => $wsAnswerHtml
        ];
        return $response;
    }
}





