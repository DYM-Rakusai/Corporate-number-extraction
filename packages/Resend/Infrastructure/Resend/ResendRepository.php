<?php

declare(strict_types=1);

namespace packages\Resend\Infrastructure\Resend;

use App\Model\Resend;

class ResendRepository implements ResendRepositoryInterface
{
    public function __construct(
    )
    {
    }

    public function insertResend($insertData)
    {
        Resend::insert($insertData);
    }

    public function updateResend($whereKeys, $updateData)
    {
    	if(empty($whereKeys)) {
    		return;
    	}
    	$resendQuery = Resend::query();
    	foreach($whereKeys as $whereCol => $whereVal) {
    		$resendQuery->where($whereCol, '=', $whereVal);
    	}
    	$resendQuery->update($updateData);
    }

    public function getResendData($whereDate)
    {
        $resends = Resend::where('send_time', 'like binary', "%$whereDate%")
            ->where('confirm_time', '=', NULL)
            ->get();
        return $resends;
    }

    public function checkExistResendData($atsCsId)
    {
        $isExistData = Resend::where('ats_consumer_id', '=', strval($atsCsId))->exists();
        return $isExistData;
    }
}



