<?php
declare(strict_types=1);
namespace packages\BlackList\UseCase\Read;

interface GetBlackListServiceInterface
{
    public function getBlackListData();
    public function getBlackListCsData($csTel, $csMail, $isValidList);
}


