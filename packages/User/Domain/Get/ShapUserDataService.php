<?php
declare(strict_types=1);
namespace packages\User\Domain\Get;

class ShapUserDataService
{

    public function __construct(
    )
    {
    }

    public function shapUserData($userDataObj)
    {
        if($userDataObj->isEmpty()) {
            return [];
        }
        $userDataArray = $userDataObj->toArray();
        $Users         = [];
        if(!empty($userDataArray[0]['User'])) {
            foreach($userDataArray[0]['User'] as $User) {
                $Users[$User['key']] = $User['val'];
            }
        }
        $shapUserData = [];
        foreach($userDataArray[0] as $colName => $colVal) {
            $shapUserData[$colName] = $colVal;
        }
        $shapUserData['User'] = $Users;
        return $shapUserData;
    }
}



