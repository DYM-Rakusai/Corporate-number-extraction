<?php
declare(strict_types=1);

namespace packages\Job\UseCase\Read;

use packages\Job\Infrastructure\JobMapping\JobMappingRepositoryInterface;

class CheckKeywordService implements CheckKeywordServiceInterface
{
    private $JobMappingRepository;

    public function __construct(
        JobMappingRepositoryInterface $JobMappingRepository
    ) {
        $this->JobMappingRepository = $JobMappingRepository;
    }

    public function checkKeyword($keyword, $userId)
    {
        $isExist  = false;
        $existVal = $this->JobMappingRepository->checkKeyword($keyword, $userId);
        if ($existVal) {
            $isExist = true;
        }
        return $isExist;
    }
}
