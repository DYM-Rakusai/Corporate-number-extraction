<?php
declare(strict_types=1);
namespace packages\BlackList\UseCase\Delete;

interface DeleteBlackListServiceInterface
{
    public function deleteBlackList($blackListId);
    public function deleteBlackListForCsDetail($blackListIds);
}




