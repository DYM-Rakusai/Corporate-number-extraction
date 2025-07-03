<?php
declare(strict_types=1);

namespace packages\Job\Infrastructure\JobMapping;

interface JobMappingRepositoryInterface
{
    public function deleteAllJobMap($userId);
    public function insertJobMapping($insertData);
    public function getJobMappingByUserId($userId);
    public function getAllJobKeyword();
    public function checkKeyword($keyword, $userId);
    public function getUserIdToKeyword();
}
