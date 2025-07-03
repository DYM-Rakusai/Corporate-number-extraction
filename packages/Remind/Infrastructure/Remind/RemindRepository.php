<?php
declare(strict_types=1);
namespace packages\Remind\Infrastructure\Remind;

use App\Model\Remind;

class RemindRepository implements RemindRepositoryInterface
{

    public function __construct(
    )
    {
    }

    public function insertRemind($insertData)
    {
        Remind::insert($insertData);
    }

    public function getRemindByTime($date)
    {
    	$reminds = Remind::where('send_time', 'like binary', "%$date%")->get();
    	return $reminds;
    }

    public function updateRemind($atsCsId, $updateData)
    {
        Remind::where('ats_consumer_id', '=', $atsCsId)
            ->update($updateData);
    }
}


