<?php

namespace App\Http\Controllers\Auth\Api\BlackList;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use packages\BlackList\UseCase\Delete\DeleteBlackListServiceInterface;

class DeleteBlackListController extends Controller
{
    private $DeleteBlackListService;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        DeleteBlackListServiceInterface $DeleteBlackListService
    )
    {
        $this->middleware('auth:api');
        $this->DeleteBlackListService = $DeleteBlackListService;
    }

    public function store(Request $request)
    {
        if ($request->get('apiToken') !== config('app.api_token')) {
            throw new \Exception('APIトークンが不正です。');
        }
        
        $this->DeleteBlackListService->deleteBlackList($request->get('blackListId'));
    }
}


