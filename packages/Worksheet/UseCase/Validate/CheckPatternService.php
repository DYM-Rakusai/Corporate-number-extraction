<?php
declare(strict_types=1);
namespace packages\Worksheet\UseCase\Validate;

use Log;
use packages\Worksheet\Domain\Pattern\CheckPatternByAnswer;

class CheckPatternService implements CheckPatternServiceInterface
{
	private $CheckPatternByAnswer;

    public function __construct(
    	CheckPatternByAnswer $CheckPatternByAnswer
    )
    {
    	$this->CheckPatternByAnswer = $CheckPatternByAnswer;
    }

    public function checkPattern($answerData)
    {
    	$pattern = $this->CheckPatternByAnswer->checkAnswer($answerData);
    	return $pattern;
    }
}


