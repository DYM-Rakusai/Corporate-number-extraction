<?php
declare(strict_types=1);
namespace packages\Worksheet\UseCase\Shap;

interface ShapWsDataServiceInterface
{
    public function shapWsAnswer(
        $worksheetAnswers,
        $schedules       = NULL,
        $adjustSchedules = NULL,
        $entryJob
    );
}


