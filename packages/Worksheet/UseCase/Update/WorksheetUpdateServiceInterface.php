<?php
declare(strict_types=1);
namespace packages\Worksheet\UseCase\Update;

interface WorksheetUpdateServiceInterface
{
    public function updateWorksheet($whereKeys, $updateData);
}


