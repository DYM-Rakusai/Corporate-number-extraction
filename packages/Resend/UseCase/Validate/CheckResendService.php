<?php
declare(strict_types=1);
namespace packages\Resend\UseCase\Validate;

use Log;
use Illuminate\Support\Carbon;
use packages\Resend\Infrastructure\Resend\ResendRepositoryInterface;

class CheckResendService implements CheckResendServiceInterface
{
    private $ResendRepository;

    public function __construct(
        ResendRepositoryInterface $ResendRepository
    )
    {
        $this->ResendRepository = $ResendRepository;
    }

    public function isExistResendData($atsCsId)
    {
        $isExistData = $this->ResendRepository->checkExistResendData($atsCsId);
        return $isExistData;
    }
}




