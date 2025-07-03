<?php
declare(strict_types=1);

namespace packages\Job\UseCase\Update;

use Log;
use packages\Job\Infrastructure\JobMapping\JobMappingRepositoryInterface;

class UpdateJobKeywordService implements UpdateJobKeywordServiceInterface
{
    private $JobMappingRepository;

    public function __construct(
        JobMappingRepositoryInterface $JobMappingRepository
    ) {
        $this->JobMappingRepository = $JobMappingRepository;
    }

    public function updateJobKeywordData($updateJobKeywords, $userId)
    {
        $insertDataArray = [];
        foreach ($updateJobKeywords as $jobKeyword) {
            if (empty($jobKeyword)) {
                continue;
            }
            $insertData = [
                'job_keyword' => $jobKeyword,
                'user_id'     => $userId
            ];
            $insertDataArray[] = $insertData;
        }
        $this->JobMappingRepository->deleteAllJobMap($userId);
        $this->JobMappingRepository->insertJobMapping($insertDataArray);
    }
}
