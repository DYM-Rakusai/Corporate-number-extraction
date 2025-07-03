<?php
declare(strict_types=1);
namespace packages\Job\UseCase\Read;

interface CheckKeywordServiceInterface
{
    public function checkKeyword($keyword, $userId);
}
