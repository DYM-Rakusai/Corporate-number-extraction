<?php

declare(strict_types=1);

namespace packages\Job\Infrastructure\JobMapping;

use App\Model\JobMapping;

class JobMappingRepository implements JobMappingRepositoryInterface
{
    public function __construct()
    {
    }

    public function deleteAllJobMap($userId)
    {
        JobMapping::where('user_id', $userId)
            ->delete();
    }

    public function insertJobMapping($insertData)
    {
        JobMapping::insert($insertData);
    }

    public function getJobMappingByUserId($userId)
    {
        $jobMappingData = JobMapping::where('user_id', '=', $userId)->pluck('job_keyword')->toArray();
        return $jobMappingData;
    }

    public function checkKeyword($keyword, $userId)
    {
        $isExist = JobMapping::where('user_id', '!=', $userId)
            ->whereIn('job_keyword', $keyword)
            ->exists();
        return $isExist;
    }

    public function getUserIdToKeyword()
    {
        $keywordArray = JobMapping::select('job_keyword', 'user_id')
                              ->get()
                              ->keyBy('job_keyword')
                              ->toArray();
        return $keywordArray;
    }

    public function getAllJobKeyword()
    {
        $jobMappingData = JobMapping::pluck('job_keyword')->toArray();
        return $jobMappingData;
    }
}
