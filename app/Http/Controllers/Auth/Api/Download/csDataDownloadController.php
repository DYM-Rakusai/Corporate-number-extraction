<?php
namespace app\Http\Controllers\Auth\Api\Download;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use packages\Download\UseCase\Create\CreateCsDataDownloadServiceInterface;
use packages\Consumer\UseCase\Read\GetCsDataServiceInterface;
use packages\Schedule\UseCase\Read\GetScheduleServiceInterface;
use packages\Worksheet\UseCase\Read\GetWsDataServiceInterface;
use packages\Download\Domain\Services\ShapCsDataService;

class csDataDownloadController extends Controller
{
    private $ConsumerRepository;
    private $CreateCsDataDownloadService;
    private $GetCsDataService;
    private $GetScheduleService;
    private $GetWsDataService;  
    private $ShapCsDataService;

    public function __construct(
        CreateCsDataDownloadServiceInterface $CreateCsDataDownloadService,
        GetCsDataServiceInterface            $GetCsDataService,
        GetScheduleServiceInterface          $GetScheduleService,
        GetWsDataServiceInterface            $GetWsDataService,
        ShapCsDataService                    $ShapCsDataService
    )
    {
        $this->middleware(['auth']);
        $this->CreateCsDataDownloadService = $CreateCsDataDownloadService;
        $this->GetCsDataService            = $GetCsDataService;
        $this->GetScheduleService          = $GetScheduleService;
        $this->GetWsDataService            = $GetWsDataService;
        $this->ShapCsDataService           = $ShapCsDataService;
    }

    function store(Request $request)
    {
        \Log::info($request);
        \Log::info('Excelダウンロード処理開始');

        $searchParam    = $request->get('param');
        $user           = Auth::user();
        $userId         = $user->authority === 'master' ? '' : $user->id;
        $atsCsIds       = [];

        $csDatas  = $this->GetCsDataService->getCsPageData($searchParam, false, $userId);

        foreach ($csDatas as $keyNum => $atsCsIdData) {
            $atsCsIds[$keyNum] = $atsCsIdData['ats_consumer_id'];
        }

        $scheduleDatas = $this->GetScheduleService->getScheduleDataByArray($atsCsIds);
        $wsAllDatas    = $this->GetWsDataService->getWsAnswerDatas($atsCsIds);
        $getWsDatas    = [];

        foreach ($wsAllDatas as $wsAllData) {
            $getWsDatas[$wsAllData['ats_consumer_id']] = [
                'ws_answers'       => $wsAllData['ws_answers'],
                'adjust_schedules' => $wsAllData['adjust_schedules'],
            ];
        }

        $shapDatas = $this->ShapCsDataService->shapExportData(
            $csDatas,
            $scheduleDatas,
            $getWsDatas
        );

        $this->CreateCsDataDownloadService->CreateCsDataDownload($shapDatas, $atsCsIds);
    }

}