<?php

declare(strict_types=1);

namespace packages\BlackList\UseCase\Create;

use Log;
use Illuminate\Support\Carbon;
use packages\BlackList\Infrastructure\BlackList\BlackListRepositoryInterface;

class AddBlackListService implements AddBlackListServiceInterface
{
    private $BlackListRepository;
    private $nowCarbon;

    public function __construct(
        BlackListRepositoryInterface $BlackListRepository
    )
    {
        $this->BlackListRepository = $BlackListRepository;
        $nowStamp = Carbon::now('Asia/Tokyo');
        $this->nowCarbon = Carbon::parse($nowStamp);
    }

    public function addBlackList($addName, $addTel, $addMail)
    {
        $addTel = str_replace('-', '', $addTel);
        $insertData = [
            'name'       => $addName,
            'tel'        => $addTel,
            'mail'       => $addMail,
            'created_at' => $this->nowCarbon
        ];
        $this->BlackListRepository->insertBlackList($insertData);
    }
}





