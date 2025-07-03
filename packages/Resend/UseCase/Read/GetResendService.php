<?php
declare(strict_types=1);
namespace packages\Resend\UseCase\Read;

use Log;
use Illuminate\Support\Carbon;
use packages\Resend\Infrastructure\Resend\ResendRepositoryInterface;

class GetResendService implements GetResendServiceInterface
{
    private $ResendRepository;

    public function __construct(
        ResendRepositoryInterface $ResendRepository
    )
    {
        $this->ResendRepository = $ResendRepository;

    }

    public function getResendDatas($nowDate)
    {
        $resendDatas = $this->ResendRepository->getResendData($nowDate);
        return $resendDatas;
    }
}


