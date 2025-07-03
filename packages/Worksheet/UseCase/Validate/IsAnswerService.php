<?php
declare(strict_types=1);
namespace packages\Worksheet\UseCase\Validate;

use Log;
use packages\Worksheet\Infrastructure\Worksheet\WorksheetRepositoryInterface;

class IsAnswerService implements IsAnswerServiceInterface
{
	private $WorksheetRepository;

    public function __construct(
    	WorksheetRepositoryInterface $WorksheetRepository
    )
    {
    	$this->WorksheetRepository = $WorksheetRepository;
    }

    public function checkAnswer($atsConsumerId)
    {
		$worksheetData = $this->WorksheetRepository->getWorksheetData($atsConsumerId);
		$isAnswer      = empty($worksheetData[0]->ws_answers) ? true : false;
		return $isAnswer;
    }

    public function checkCompanyAnswer($atsConsumerId)
    {
        $worksheetData = $this->WorksheetRepository->getWorksheetData($atsConsumerId);
        $isNotAnswer   = empty($worksheetData[0]->company_answer_json) ? true : false;
        return $isNotAnswer;
    }
}


