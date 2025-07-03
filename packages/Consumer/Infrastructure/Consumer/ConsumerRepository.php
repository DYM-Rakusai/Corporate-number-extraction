<?php

declare(strict_types=1);

namespace packages\Consumer\Infrastructure\Consumer;

use App\Model\Consumer;
use App\Model\Schedule;
use packages\Consumer\Infrastructure\Consumer\ForSearch\ConsumerSearchQuery;

class ConsumerRepository implements ConsumerRepositoryInterface
{
    // private $ConsumerSearchQuery;

    public function __construct(
        // ConsumerSearchQuery $ConsumerSearchQuery
    )
    {
        // $this->ConsumerSearchQuery = $ConsumerSearchQuery;
    }

    public function insertCs($insertData)
    {
        $csId = Consumer::insertGetId($insertData);
        return $csId;
    }

    public function isExistConsumer($checkCol, $checkVal, $whereDate = NULL, $isSentAutoMsg = NULL)
    {
        $consumerQuery = Consumer::query();
        $consumerQuery->where($checkCol, '=', $checkVal);
        if(!empty($whereDate)) {
            $consumerQuery->where('created_at', '>=', $whereDate);
        }

        $isExsistCs = $consumerQuery->exists();
        return $isExsistCs;
    }


    public function getConsumerDataObj($whereCol, $whereVal)
    {
        $consumerQuery = Consumer::query();
        $consumerQuery->where($whereCol, '=', $whereVal);
        $consumerDataObj = $consumerQuery->get();
        return $consumerDataObj;
    }

    public function getConsumerDataByArray($csAtsIds, $getCol)
    {
        $csDataArray = Consumer::whereIn('ats_consumer_id', $csAtsIds)
            ->pluck($getCol, 'ats_consumer_id')
            ->toArray();
        return $csDataArray;
    }


    public function getPageCsData(
        $searchDatas,
        $searchKeys,
        $checkflag,
        $userId
    ) {
        $consumerQuery = Consumer::query();
        $ConsumerSearchQueryObj = new ConsumerSearchQuery($consumerQuery);
        $ConsumerSearchQueryObj->searchParamsByArray($searchKeys, $searchDatas, $userId);
        //面接日時指定（〜以降、(含む)）
        $ConsumerSearchQueryObj->searchInterviewDate('>=', $searchDatas['start_interview_date'] ?? '');
        //面接日時指定（〜まで、(含む)）
        $ConsumerSearchQueryObj->searchInterviewDate('<=', $searchDatas['end_interview_date'] ?? '');
        //面接設定
        $ConsumerSearchQueryObj->searchInterviewSet($searchDatas['interview_set'] ?? '');

        $ConsumerSearchQueryObj->consumerQuery->with('scheduleData');
        $ConsumerSearchQueryObj->consumerQuery->latest('created_at');
        $ConsumerSearchQueryObj->consumerQuery->with('userData');

        if ($checkflag != true) {
            $atsCsIdData = $ConsumerSearchQueryObj->consumerQuery->get()->toArray();
            return $atsCsIdData;
        }
        $csPageData = $ConsumerSearchQueryObj->consumerQuery->paginate(30);
        
        return $csPageData;
    }


    public function updateCsData($whereData, $updateData)
    {
        if(empty($whereData)) {
            \Log::error('whereのキーが不正のためupdateしない');
            return;
        }
        $consumerQuery = Consumer::query();
        foreach($whereData as $whereKey => $whereVal) {
            $consumerQuery->where($whereKey, '=', $whereVal);
        }
        $consumerQuery->update($updateData);
    }

    public function getInEntryDateCs($startDate, $endDate, $checkItems, $checkNum)
    {
        $consumerQuery = Consumer::query();
        $consumerQuery->where('app_date', '>=', $startDate)
            ->where('app_date', '<=', $endDate);

        if($checkNum == '1') {
            foreach($checkItems as $checkItem) {
                $consumerQuery->where(function($query) use ($checkItem) {
                    $query->orWhere('entry_job', '<>', $checkItem);
                });
            }
        }
    
        $csData = $consumerQuery->get();
        return $csData;
    }

    public function updateAppMedia($csIds, $updateDatas)
    {
        $consumerDataQuery = Consumer::query();
        $consumerDataQuery->whereIn('id', $csIds);
        $consumerDataQuery->update($updateDatas);
    }

    public function getCsDataById($csId, $column)
    {
        $csDataArray = Consumer::where('id', $csId)
            ->value($column);
        return $csDataArray;
    }

}
