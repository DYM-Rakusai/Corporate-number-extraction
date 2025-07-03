<?php
declare(strict_types=1);
namespace packages\Worksheet\UseCase\Validate;

interface IsAnswerServiceInterface
{
    public function checkAnswer($atsConsumerId);
    public function checkCompanyAnswer($atsConsumerId);
}


