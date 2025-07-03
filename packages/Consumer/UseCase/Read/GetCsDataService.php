<?php

declare(strict_types=1);

namespace packages\Consumer\UseCase\Read;

use Log;
use Illuminate\Support\Carbon;
use packages\Consumer\Infrastructure\Consumer\ConsumerRepositoryInterface;
use packages\Consumer\Domain\Get\ShapCsDataService;

class GetCsDataService implements GetCsDataServiceInterface
{
    private $ConsumerRepository;
    private $ShapCsDataService;

    public function __construct(
        ConsumerRepositoryInterface $ConsumerRepository,
        ShapCsDataService           $ShapCsDataService
    )
    {
        $this->ConsumerRepository = $ConsumerRepository;
        $this->ShapCsDataService  = $ShapCsDataService;
    }

    public function getConsumerData($whereCol, $whereVal)
    {
        $consumerDataObj = $this->ConsumerRepository->getConsumerDataObj($whereCol, $whereVal);
        $shapCsData      = $this->ShapCsDataService->shapCsData($consumerDataObj);
        return $shapCsData;
    }

    public function getCsDatas($csAtsIds, $getCol)
    {
        $csDataArray = $this->ConsumerRepository->getConsumerDataByArray($csAtsIds, $getCol);
        return $csDataArray;
    }


    public function getCsPageData($searchDatas, $checkflag, $userId)
    {
        $searchKeys = [
            'start_app_date' => [
                'db_key'   => 'app_date',
                'comp_key' => 'under'
            ], //範囲
            'end_app_date' => [
                'db_key'   => 'app_date',
                'comp_key' => 'over'
            ], //範囲
            'app_name' => [
                'db_key'   => 'name',
                'comp_key' => 'partial_name_match'
            ], //部分一致
            'app_tel' => [
                'db_key'   => 'tel',
                'comp_key' => 'partial_tel_match'
            ], //部分一致
            'app_mail' => [
                'db_key'   => 'mail',
                'comp_key' => 'partial_match'
            ], //部分一致
            'app_status' => [
                'db_key'   => 'consumer_status',
                'comp_key' => 'match'
            ],
        ];
        $csPageData = $this->ConsumerRepository->getPageCsData(
            $searchDatas,
            $searchKeys,
            $checkflag,
            $userId
        );
        return $csPageData;
    }

    public function getInEntryDate($startDate, $endDate, $checkItems, $checkNum)
    {
        $csData = $this->ConsumerRepository->getInEntryDateCs($startDate, $endDate, $checkItems, $checkNum);
        return $csData;
    }


    public function getCsDataById($csId, $column)
    {
        $CsData = $this->ConsumerRepository->getCsDataById($csId, $column);
        return $CsData;
    }
}





