<?php

declare(strict_types=1);

namespace packages\Consumer\UseCase\Validate;

use Log;
use Illuminate\Support\Carbon;
use packages\Consumer\Infrastructure\Consumer\ConsumerRepositoryInterface;
use packages\Consumer\Domain\Validate\ValidateDataService;

class CheckDuplicateConsumerService implements CheckDuplicateConsumerServiceInterface
{
    private $beforeTime;
    private $ConsumerRepository;
    private $ValidateDataService;

    public function __construct(
        ConsumerRepositoryInterface $ConsumerRepository,
        ValidateDataService         $ValidateDataService
    )
    {
        $this->beforeTime          = config('Consumer.consumerConf.duplicateTime');
        $this->ConsumerRepository  = $ConsumerRepository;
        $this->ValidateDataService = $ValidateDataService;
    }

    public function checkDuplicateCs($csTel, $csMail)
    {
        $beforeCarbon = Carbon::now('Asia/Tokyo')->subHour($this->beforeTime);
        $alreadyExist = false;

        // 電話番号が有効かつ重複があればtrue
        if ($this->ValidateDataService->isValidTel($csTel)) {
            if ($this->ConsumerRepository->isExistConsumer('tel', $csTel, $beforeCarbon, 1)) {
                Log::info("電話番号が重複: {$csTel}");
                $alreadyExist = true;
            }
        }

        // メールアドレスが有効かつ重複があればtrue
        if ($this->ValidateDataService->isValidMail($csMail)) {
            if ($this->ConsumerRepository->isExistConsumer('mail', $csMail, $beforeCarbon, 1)) {
                Log::info("メールアドレスが重複: {$csMail}");
                $alreadyExist = true;
            }
        }

        return $alreadyExist;
    }

    public function checkAlreadyExist($atsConsumerId)
    {
        return $this->ConsumerRepository->isExistConsumer('ats_consumer_id', $atsConsumerId, null, null);
    }
}


