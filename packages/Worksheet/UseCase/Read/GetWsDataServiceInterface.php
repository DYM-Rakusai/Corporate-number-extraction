<?php
declare(strict_types=1);
namespace packages\Worksheet\UseCase\Read;

interface GetWsDataServiceInterface
{
    public function getWsAnswer($atsCsId, $isHtml = true);
    public function getWsAnswerDatas($atsCsIds);
    public function getNoAnswerCsIds($limitDate);
}


