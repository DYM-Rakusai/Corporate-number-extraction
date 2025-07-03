<?php
declare(strict_types=1);
namespace packages\Worksheet\UseCase\Shap;

use Log;
use Illuminate\Support\Carbon;
use packages\Worksheet\Domain\Shap\ShapWsAnswerService;

class ShapWsDataService implements ShapWsDataServiceInterface
{
    private $nowCarbon;

    public function __construct(
        ShapWsAnswerService $ShapWsAnswerService
    )
    {
        $nowStamp                  = Carbon::now('Asia/Tokyo');
        $this->nowCarbon           = Carbon::parse($nowStamp);
        $this->ShapWsAnswerService = $ShapWsAnswerService;
    }

    public function shapWsAnswer(
        $worksheetAnswers,
        $schedules       = NULL,
        $adjustSchedules = NULL,
        $entryJob
    )
    {
        $wsAnswerText = $this->ShapWsAnswerService->shapWsAnswerText(
            $worksheetAnswers,
            $schedules       = NULL,
            $adjustSchedules = NULL,
        );
        return $wsAnswerText;
    }
}



