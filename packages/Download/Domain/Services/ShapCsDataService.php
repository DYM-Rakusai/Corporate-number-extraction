<?php
declare(strict_types=1);
namespace packages\Download\Domain\Services;

use Log;
use DateTime;
use Carbon\Carbon;
use packages\User\UseCase\Read\GetUserServiceInterface;
use packages\Worksheet\Domain\Shap\ShapWsAnswerService;

class ShapCsDataService
{
    private $ShapWsAnswerService;

    public function __construct(    
        GetUserServiceInterface $GetUserService,
        ShapWsAnswerService     $ShapWsAnswerService
    )
    {
        $this->GetUserService      = $GetUserService;
        $this->ShapWsAnswerService = $ShapWsAnswerService;
    }

    public function shapExportData(
        $csDatas, 
        $scheduleDatas, 
        $getWsDatas
    )
    {        
        //ats_consumer_idに紐づくws_answerの配列を作成
        $wsDatas = array();
        $csJobs = [];
        foreach($csDatas as $csData) {
            $csJobs[$csData['ats_consumer_id']] = $csData['entry_job'];
        }
        foreach ($getWsDatas as $atsCsId => $getWsData)
        {
            $wsAnswers       = !empty($getWsData['ws_answers']) ? json_decode($getWsData['ws_answers'], true) : [];
            $adjustSchedules = !empty($getWsData['adjust_schedules']) ? json_decode($getWsData['adjust_schedules'], true) : [];
            $answerData = $this->ShapWsAnswerService->shapWsAnswerText(
                $wsAnswers,
                NULL,
                $adjustSchedules,
                $csJobs[$atsCsId]
            );
            $wsDatas[$atsCsId] = $answerData;
        }

        //ヘッダーデータ
        $header   = array();
        $header[] = config('Download.headerList.header');

        //Excelに出力するデータを作成
        $exportData = array();
        foreach ($csDatas as $rowNum => $csData)
        {
            $baseData['id']              = $rowNum + 1;
            $baseData['name']            = $csData['name'];
            $baseData['kana']            = $csData['kana'];
            $baseData['birthday']        = $csData['birthday'];
            $baseData['gender']          = $csData['gender'];
            $baseData['mail']            = $csData['mail'];
            $baseData['tel']             = $csData['tel'];
            $baseData['address']         = $csData['address'];
            $baseData['app_date']        = $csData['app_date'];
            $baseData['app_media']       = $csData['app_media'];
            $baseData['entry_job']       = $csData['entry_job'];
            $baseData['interview_date']  = $scheduleDatas[$csData['ats_consumer_id']] ?? '';
            $baseData['consumer_status'] = $csData['consumer_status'];
            $baseData['memo']            = $csData['memo'];
            $userName                    = $this->getUserName($csData);
            $baseData['user_id']         = $userName;
            $baseData['ws_answer']       = $wsDatas[$csData['ats_consumer_id']] ?? '';
            $header[]                    = $baseData;            
        }
        $exportData = $header;
        
        return $exportData;
    }


    private function getUserName($csData)
    {
        if($csData['user_id']){
            $userName = $this->GetUserService->getUserValById($csData['user_id'], 'name');
        }else{
            $userName = "";
        }

        return $userName;
    }
}
