<?php
declare(strict_types=1);
namespace packages\Worksheet\UseCase\Update;

use Log;
use Illuminate\Support\Carbon;
use packages\Worksheet\Infrastructure\Worksheet\WorksheetRepositoryInterface;

class WorksheetUpdateService implements WorksheetUpdateServiceInterface
{
    private $WorksheetRepository;

    public function __construct(
        WorksheetRepositoryInterface $WorksheetRepository
    )
    {
        $this->WorksheetRepository = $WorksheetRepository;
    }

    /**
     * @param array $whereKeys ['whereするカラム名' => '検索する値']
     * @param array $updateData 更新する値
     */
    public function updateWorksheet($whereKeys, $updateData)
    {
        $this->WorksheetRepository->updateWorksheet(
            $whereKeys,
            $updateData);
    }
}


