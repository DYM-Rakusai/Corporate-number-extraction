<?php
declare(strict_types=1);
namespace packages\Remind\UseCase\Read;

use Log;
use Illuminate\Support\Carbon;
use packages\Remind\Infrastructure\Remind\RemindRepositoryInterface;

class ReadRemindService implements ReadRemindServiceInterface
{
    private $RemindRepository;

    public function __construct(
        RemindRepositoryInterface $RemindRepository
    )
    {
        $this->RemindRepository = $RemindRepository;
    }

    public function getRemindTargetData($nowDate)
    {
        $reminds = $this->RemindRepository->getRemindByTime($nowDate);
        return $reminds;        
    }
}




