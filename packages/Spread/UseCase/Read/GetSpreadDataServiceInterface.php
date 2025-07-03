<?php
declare(strict_types=1);
namespace packages\Spread\UseCase\Read;

interface GetSpreadDataServiceInterface
{
    public function getAllSpreadDataArray($sheetName, $spreadId);
}


