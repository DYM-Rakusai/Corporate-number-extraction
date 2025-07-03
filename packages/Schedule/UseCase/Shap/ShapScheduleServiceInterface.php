<?php
declare(strict_types=1);
namespace packages\Schedule\UseCase\Shap;

interface ShapScheduleServiceInterface
{
    public function getScheduleDataForUpdate($ableSchedules, $userId);
    public function scheduleForAdminPage($userId);
}

