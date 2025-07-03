<?php
declare(strict_types=1);
namespace packages\Remind\UseCase\Update;

use Log;
use Illuminate\Support\Carbon;
use packages\Remind\Infrastructure\Remind\RemindRepositoryInterface;

class UpdateRemindService implements UpdateRemindServiceInterface
{
    private $nowDate;
    private $RemindRepository;

    public function __construct(
        RemindRepositoryInterface $RemindRepository
    )
    {
        $nowStamp               = Carbon::now('Asia/Tokyo');
        $this->nowDate          = Carbon::parse($nowStamp);
        $this->RemindRepository = $RemindRepository;
    }

    public function updateRemind($atsCsId, $updateData)
    {
        $this->RemindRepository->updateRemind($atsCsId, $updateData);
    }
}


