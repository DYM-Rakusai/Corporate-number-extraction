<?php

declare(strict_types=1);

namespace packages\Consumer\UseCase\Validate;

use Log;
use packages\Consumer\Infrastructure\Consumer\ConsumerRepositoryInterface;
use packages\Consumer\Domain\Validate\ValidateDataService;

class ValidateConsumerDataService implements ValidateConsumerDataServiceInterface
{
    private $ConsumerRepository;
    private $ValidateDataService;

    public function __construct(
        ConsumerRepositoryInterface $ConsumerRepository,
        ValidateDataService         $ValidateDataService
    ) {
        $this->ConsumerRepository  = $ConsumerRepository;
        $this->ValidateDataService = $ValidateDataService;
    }

    public function getIsValidList($checkList) 
    {
        $isValidList = [];
        if (isset($checkList['tel'])) {
            $isValidList['tel'] = $this->ValidateDataService->isValidTel($checkList['tel']);
        }
        if (isset($checkList['mail'])) {
            $isValidList['mail'] = $this->ValidateDataService->isValidMail($checkList['mail']);
        }
        return $isValidList;
    }

    public function isExistConsumer($whereCol, $whereVal)
    {
        return $this->ConsumerRepository->isExistConsumer($whereCol, $whereVal, NULL, NULL);
    }
}


