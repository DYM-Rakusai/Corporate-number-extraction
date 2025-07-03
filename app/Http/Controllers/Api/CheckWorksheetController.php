<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use packages\BlackList\UseCase\Validate\CheckBlackListServiceInterface;
use packages\Worksheet\UseCase\Validate\CheckPatternServiceInterface;
use packages\Consumer\UseCase\Read\GetCsDataServiceInterface;
use packages\Consumer\UseCase\Validate\ValidateConsumerDataServiceInterface;

class CheckWorksheetController extends Controller
{
    private $CheckBlackListService;
    private $CheckPatternService;
    private $GetCsDataService;
    private $ValidateConsumerDataService;

    public function __construct(
        CheckBlackListServiceInterface       $CheckBlackListService,
        CheckPatternServiceInterface         $CheckPatternService,
        GetCsDataServiceInterface            $GetCsDataService,
        ValidateConsumerDataServiceInterface $ValidateConsumerDataService
    )
    {
        $this->CheckBlackListService       = $CheckBlackListService;
        $this->CheckPatternService         = $CheckPatternService;
        $this->GetCsDataService            = $GetCsDataService;
        $this->ValidateConsumerDataService = $ValidateConsumerDataService;
    }    

    public function store(Request $request)
    {
        \Log::info('----------GetWorksheetController---------');
        \Log::info(PHP_EOL . json_encode($request->all(), JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
        
        if ($request->get('apiToken') !== config('app.api_token')) {
            throw new \Exception('APIトークンが不正です。');
        }

        $consumerData = $this->GetCsDataService->getConsumerData('id', $request->get('consumerId'));

        $pattern      = $this->determinePattern($consumerData, $request->get('answerData'));
   
        return ['pattern' => $pattern];
    }


    private function determinePattern($consumerData, $answerData)
    {
        $csTel  = $consumerData['tel'];
        $csMail = $consumerData['mail'];

        $isValidList = $this->ValidateConsumerDataService->getIsValidList([
            'tel'  => $csTel,
            'mail' => $csMail
        ]);

        $isBlackList = $this->CheckBlackListService->checkBlackList($csTel, $csMail, $isValidList);

        if ($isBlackList) {
            \Log::info('不合格：ブラックリスト');
            return 'failure';
        }

        return $this->CheckPatternService->checkPattern($answerData);
    }

}
