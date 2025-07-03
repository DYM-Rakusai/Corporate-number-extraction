<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use packages\Schedule\UseCase\Delete\DeleteScheduleServiceInterface;

class DeletePastSchedule extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:deletePastSchedule {nowDate?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '５日以前のスケジュールを削除';

    private $DeleteScheduleService;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(
        DeleteScheduleServiceInterface $DeleteScheduleService
    )
    {
        $this->DeleteScheduleService = $DeleteScheduleService;
        parent::__construct();
    }

    public function handle()
    {
        $nowDate       = $this->argument('nowDate');
        $nowCarbon     = new Carbon($nowDate);
        $fiveDaysAgo   = $nowCarbon->copy()->subDays(5)->startOfDay();
        $pastCarbonStr = $fiveDaysAgo->format('Y-m-d 00:00:00');
        $this->DeleteScheduleService->pastFreeSchedule($pastCarbonStr);
    }
}


