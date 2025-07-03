<?php
declare(strict_types=1);
namespace packages\Job\UseCase\Read;

interface GetJobKeywordServiceInterface
{
    public function getJobMappingByUserId($userId);
    public function getAllJobKeyword();
}


