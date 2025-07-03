<?php

namespace App\Http\Controllers\Auth\Api\BlackList;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use packages\BlackList\UseCase\Create\AddBlackListServiceInterface;

class AddBlackListController extends Controller
{
    private $AddBlackListService;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        AddBlackListServiceInterface $AddBlackListService
    )
    {
        $this->middleware('auth:api');
        $this->AddBlackListService = $AddBlackListService;
    }

    public function store(Request $request)
    {
        if ($request->get('apiToken') !== config('app.api_token')) {
            throw new \Exception('APIトークンが不正です。');
        }

        $this->AddBlackListService->addBlackList(
            $request->get('blackListName'),
            $request->get('blackListTel' ),
            $request->get('blackListMail')
        );
    }
}


