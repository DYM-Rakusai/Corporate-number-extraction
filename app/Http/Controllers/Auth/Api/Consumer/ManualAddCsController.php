<?php
declare(strict_types=1);
namespace App\Http\Controllers\Auth\Api\Consumer;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Api\AddConsumerController;
use packages\Consumer\UseCase\Create\CreateConsumerDataServiceInterface;
use packages\User\UseCase\Read\GetUserServiceInterface;

class ManualAddCsController extends Controller
{
    private $AddConsumerController;
    private $CreateConsumerDataService;
    private $GetUserService;

    public function __construct(
        AddConsumerController              $AddConsumerController,
        CreateConsumerDataServiceInterface $CreateConsumerDataService,
        GetUserServiceInterface            $GetUserService
    )
    {
        $this->middleware('auth:api');
        $this->AddConsumerController     = $AddConsumerController;
        $this->CreateConsumerDataService = $CreateConsumerDataService;
        $this->GetUserService            = $GetUserService;
    }

    public function store(Request $request)
    {
        \Log::Info($request);
        $manualAddData   = $request['manualAddData'];
        $atsConsumerId   = 'rakusai-' . uniqid();
        $tel             = str_replace('-', '', $manualAddData['tel']);
        
        $entryDate       = $this->shapEntryDate(
            $manualAddData['app_date'],
            $manualAddData['entry_hour'],
            $manualAddData['entry_minute']
        );
        
        //$userData = $this->GetUserService->getUserDataByVal($manualAddData['userName'], 'name');
        //$userId = $userData[0]['id'];

        $csData = [
            'name'            => $manualAddData['name'],
            'kana'            => $manualAddData['kana'] ?? '',
            'address'         => $manualAddData['address'] ?? '',
            'birthday'        => $manualAddData['birthday'] ?? '',
            'tel'             => $tel,
            'mail'            => $manualAddData['mail'] ?? '',
            'app_date'        => $entryDate,
            'app_media'       => $manualAddData['app_media'],
            'entry_job'       => $manualAddData['entry_job'],
            //'user_id'       => $userId,
            "job_keyword"     => "",
            'is_link_ats'     => 0,
            'ats_consumer_id' => $atsConsumerId
        ];

        if($manualAddData['is_sent_auto_msg'] == 1) 
        {
            $csRequest = [
                'apiToken'   => config('app.api_token'),
                'applicants' => $csData,
            ];

            $request->merge($csRequest);
            $this->AddConsumerController->store($request);
        }else 
        {
            $this->CreateConsumerDataService->insertCsData(
                $atsConsumerId,
                $tel,
                $manualAddData['mail'],
                $manualAddData,
                '未対応',
                false
            );
        }
        return response()->json(['message' => '応募者追加'], 200); 
    }

    private function shapEntryDate($entryDate, $entryHour, $entryMinute)
    {
        $entryHourStr   = sprintf('%02d', $entryHour);
        $entryMinuteStr = sprintf('%02d', $entryMinute);
        $entryCarbon    = new Carbon($entryDate . ' ' . $entryHourStr . ':' . $entryMinuteStr . ':00');
        return $entryCarbon->format('Y-m-d H:i');
    }
}





