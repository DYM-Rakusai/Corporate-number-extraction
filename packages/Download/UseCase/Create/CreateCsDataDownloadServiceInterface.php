<?php
declare(strict_types=1);

namespace packages\Download\UseCase\Create;

interface CreateCsDataDownloadServiceInterface
{
    public function CreateCsDataDownload($downloadDatas, $atsCsIds);
}