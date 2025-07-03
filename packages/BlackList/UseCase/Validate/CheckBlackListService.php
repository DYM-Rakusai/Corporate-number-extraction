<?php

declare(strict_types=1);

namespace packages\BlackList\UseCase\Validate;

use Log;
use Illuminate\Support\Carbon;
use packages\BlackList\Infrastructure\BlackList\BlackListRepositoryInterface;

class CheckBlackListService implements CheckBlackListServiceInterface
{
    private $BlackListRepository;

    public function __construct(
        BlackListRepositoryInterface $BlackListRepository
    )
    {
        $this->BlackListRepository = $BlackListRepository;
    }

    public function checkBlackList($csTel, $csMail, $isValidDatas)
    {
        $isExistBlackList = $this->BlackListRepository->isExistBlackList($csTel, $csMail, $isValidDatas);
        return $isExistBlackList;
    }
}
