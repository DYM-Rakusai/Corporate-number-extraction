<?php
declare(strict_types=1);
namespace packages\Worksheet\UseCase\Read;

use Log;
use Illuminate\Support\Carbon;
use packages\Worksheet\Domain\Shap\ShapWsAnswerService;
use packages\Worksheet\Infrastructure\Worksheet\WorksheetRepositoryInterface;

class GetWsDataService implements GetWsDataServiceInterface
{
    private $nowCarbon;
    private $ShapWsAnswerService;
    private $worksheetDataObj;
    private $WorksheetRepository;

    public function __construct(
        ShapWsAnswerService          $ShapWsAnswerService,
        WorksheetRepositoryInterface $WorksheetRepository
    )
    {
        $this->ShapWsAnswerService = $ShapWsAnswerService;
        $this->WorksheetRepository = $WorksheetRepository;
    }

    public function getWsAnswer($atsCsId, $isHtml = true)
    {
        if(empty($this->worksheetDataObj)) {
            $this->worksheetDataObj = $this->WorksheetRepository->getWorksheetData($atsCsId);
        }
        $wsAnswers       = !empty($this->worksheetDataObj[0]->ws_answers)       ? json_decode($this->worksheetDataObj[0]->ws_answers      , true) : [];
        $adjustSchedules = !empty($this->worksheetDataObj[0]->adjust_schedules) ? json_decode($this->worksheetDataObj[0]->adjust_schedules, true) : NULL;

        $answerData = $this->ShapWsAnswerService->shapWsAnswerText(
            $wsAnswers,
            NULL,
            $adjustSchedules
        );

        if($isHtml) {
            $answerData = str_replace("\n", '<br>', $answerData);
        }
        $this->worksheetDataObj = NULL;
        return $answerData;
    }

    public function getWsAnswerDatas($atsCsIds)
    {
        return $this->WorksheetRepository->getWorkSheetAnswer($atsCsIds);
    }

    public function getNoAnswerCsIds($limitDate)
    {
        $wsIds = $this->WorksheetRepository->getNoAnswerCsIds($limitDate);
        return $wsIds;
    }

}



