<?php
declare(strict_types=1);

namespace packages\Job\UseCase\Update;

interface UpdateJobKeywordServiceInterface
{
    public function updateJobKeywordData($updateJobKeywords, $userId);
}
