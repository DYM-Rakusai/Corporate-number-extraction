<?php
declare(strict_types=1);
namespace packages\BlackList\UseCase\Create;

interface AddBlackListServiceInterface
{
    public function addBlackList($addName, $addTel, $addMail);
}


