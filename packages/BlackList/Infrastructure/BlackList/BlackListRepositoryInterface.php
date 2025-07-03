<?php
declare(strict_types=1);

namespace packages\BlackList\Infrastructure\BlackList;

interface BlackListRepositoryInterface
{
    public function isExistBlackList($csTel, $csMail, $isValidList);
    public function getBlackList();
    public function insertBlackList($insertData);
    public function deleteBlackList($blackListId);
    public function getBlackListCsData($csTel, $csMail, $isValidList);
    public function deleteBlackListForCsDetail($blackListIds);
}
