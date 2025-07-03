<?php

declare(strict_types=1);

namespace packages\Worksheet\Infrastructure\Worksheet;

use App\Model\Worksheet;

class WorksheetRepository implements WorksheetRepositoryInterface
{
    public function __construct()
    {
    }
    public function getWorksheetData($atsConsumerId)
    {
        $worksheetData = Worksheet::where('ats_consumer_id', '=', $atsConsumerId)->get();
        return $worksheetData;
    }

    public function insertWorksheet($insertData)
    {
        Worksheet::insert($insertData);
    }

    public function getWorkSheetAnswer($atsCsIds)
    {
        $workSheetDatas = Worksheet::whereIn('ats_consumer_id', $atsCsIds)
            ->get()
            ->toArray();
        return $workSheetDatas;
    }


    public function getWorkSheetAnswerByAtsCsId($atsCsId)
    {
        $workSheetData = Worksheet::where('ats_consumer_id', $atsCsId)->get();
        return $workSheetData;
    }

    public function getNoAnswerCsIds($limitDate)
    {
        $consumerId = Worksheet::where('created_at', '<=', $limitDate)
            ->where('ws_answers', '=', null)
            ->pluck('ats_consumer_id')
            ->toArray();
        return $consumerId;
    }


    public function updateWorksheet($whereKeys, $updateData)
    {
        if (empty($whereKeys)) {
            \Log::error('updateWorksheetエラー');
            return;
        }
        $WorksheetQuery = Worksheet::query();
        foreach ($whereKeys as $whereCol => $whereVal) {
            $WorksheetQuery->where($whereCol, '=', $whereVal);
        }
        $WorksheetQuery->update($updateData);
    }
}
