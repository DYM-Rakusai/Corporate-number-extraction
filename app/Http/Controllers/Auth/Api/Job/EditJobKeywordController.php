<?php

namespace App\Http\Controllers\Auth\Api\Job;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use packages\Job\UseCase\Read\CheckKeywordServiceInterface;
use packages\Job\UseCase\Read\GetJobKeywordServiceInterface;
use packages\User\UseCase\Read\GetUserServiceInterface;
use packages\Job\UseCase\Update\UpdateJobKeywordServiceInterface;
use packages\User\UseCase\Update\UpdateUserServiceInterface;

class EditJobKeywordController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        CheckKeywordServiceInterface     $CheckKeywordService,
        GetJobKeywordServiceInterface    $GetJobKeywordService,
        GetUserServiceInterface          $GetUserService,
        UpdateJobKeywordServiceInterface $UpdateJobKeywordService,
        UpdateUserServiceInterface       $UpdateUserService
        
    ) {
        $this->middleware('auth:api');
        $this->CheckKeywordService     = $CheckKeywordService;
        $this->GetJobKeywordService    = $GetJobKeywordService;
        $this->GetUserService          = $GetUserService;
        $this->UpdateJobKeywordService = $UpdateJobKeywordService;
        $this->UpdateUserService       = $UpdateUserService;
    }

    public function store(Request $request)
    {
        $response = [
            'error'  => '',
            'status' => 'success'
        ];
        $requestData = $request->input('jobKeywords', []);
        $userId      = $request->input('userId');
        $keywordsVal = array_values($requestData);
        $uniqueArray = array_unique($keywordsVal);
        //同一キーワードのチェック
        if (count($keywordsVal) !== count($uniqueArray)) {
            return response()->json([
                'error'  => 'sameWord',
                'status' => 'error'
            ], 400);
        }
        //DBをチェック
        if ($this->CheckKeywordService->checkKeyword($keywordsVal, $userId)) {
            return response()->json([
                'error'  => 'sameWord',
                'status' => 'error'
            ], 400);
        }
        \Log::info('------EditJobKeywordController------');
        if ($request->get('apiToken') !== config('app.apiToken')) {
            throw new \Exception('APIトークンが不正です。');
            return;
        }
        $this->UpdateJobKeywordService->updateJobKeywordData($requestData, $userId);
        $response = [
            'error'  => '',
            'status' => '200'
        ];
        return $response;
    }
}
