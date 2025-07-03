<?php
declare(strict_types=1);
namespace packages\BlackList\UseCase\Validate;

interface CheckBlackListServiceInterface
{
    public function checkBlackList($csTel, $csMail, $isValidDatas);
}

