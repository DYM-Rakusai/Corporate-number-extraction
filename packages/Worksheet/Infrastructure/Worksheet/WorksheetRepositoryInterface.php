<?php
declare(strict_types=1);

namespace packages\Worksheet\Infrastructure\Worksheet;

interface WorksheetRepositoryInterface
{
    public function getWorksheetData($atsConsumerId);
    public function insertWorksheet($insertData);
    public function updateWorksheet($whereKeys, $updateData);
    public function getWorkSheetAnswerByAtsCsId($atsCsId);
    public function getWorkSheetAnswer($atsCsIds);
    public function getNoAnswerCsIds($limitDate);
}
