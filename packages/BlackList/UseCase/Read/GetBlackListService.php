<?php

declare(strict_types=1);

namespace packages\BlackList\UseCase\Read;

use Log;
use Illuminate\Support\Carbon;
use packages\BlackList\Infrastructure\BlackList\BlackListRepositoryInterface;

class GetBlackListService implements GetBlackListServiceInterface
{
    private $BlackListRepository;

    public function __construct(
        BlackListRepositoryInterface $BlackListRepository
    )
    {
        $this->BlackListRepository = $BlackListRepository;
    }

    public function getBlackListData()
    {
        $blackListPageData = $this->BlackListRepository->getBlackList();
        return $blackListPageData;
    }

    public function getBlackListCsData($csTel, $csMail, $isValidList)
    {
        $blackListCsData = $this->BlackListRepository->getBlackListCsData($csTel, $csMail, $isValidList);
        return $blackListCsData;
    }
}





