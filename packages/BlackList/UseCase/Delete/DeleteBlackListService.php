<?php

declare(strict_types=1);

namespace packages\BlackList\UseCase\Delete;

use Log;
use Illuminate\Support\Carbon;
use packages\BlackList\Infrastructure\BlackList\BlackListRepositoryInterface;

class DeleteBlackListService implements DeleteBlackListServiceInterface
{
    private $BlackListRepository;

    public function __construct(
        BlackListRepositoryInterface $BlackListRepository
    )
    {
        $this->BlackListRepository = $BlackListRepository;
    }

    public function deleteBlackList($blackListId)
    {
        $this->BlackListRepository->deleteBlackList($blackListId);
    }

    public function deleteBlackListForCsDetail($blackListIds)
    {
        $this->BlackListRepository->deleteBlackListForCsDetail($blackListIds);
    }
}





