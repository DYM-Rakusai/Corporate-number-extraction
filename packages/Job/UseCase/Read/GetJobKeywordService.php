<?php
declare(strict_types=1);

namespace packages\Job\UseCase\Read;

use Log;
use packages\Job\Infrastructure\JobMapping\JobMappingRepositoryInterface;

class GetJobKeywordService implements GetJobKeywordServiceInterface
{
    private $JobMappingRepository;

    public function __construct(
        JobMappingRepositoryInterface $JobMappingRepository
    )
    {
        $this->JobMappingRepository = $JobMappingRepository;
    }

    public function getJobMappingByUserId($userId)
    {
        $jobMapData = $this->JobMappingRepository->getJobMappingByUserId($userId);
        return $jobMapData;
    }

    public function getAllJobKeyword()
    {
        $jobMapData = $this->JobMappingRepository->getAllJobKeyword();
        return $jobMapData;
    }
}





