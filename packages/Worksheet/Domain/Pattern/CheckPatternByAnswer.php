<?php
declare(strict_types=1);
namespace packages\Worksheet\Domain\Pattern;
use Illuminate\Support\Carbon;

class CheckPatternByAnswer
{
    public function __construct()
    {
    }

    public function checkAnswer($answerData)
    {
        $formJudge = config('Form.formJudge.worksheet');
        $result    = 'success';

        foreach($formJudge as $formKey => $judgeData) {
            $answer = $answerData[$formKey] ?? null;

            if (is_null($answer)) {
                \Log::warning("回答データが見つかりません: {$formKey}");
                continue;
            }

            if($judgeData['judgeType'] == 'age') {
                $pattern = $this->checkAge($answer, $judgeData['lower'], $judgeData['upper']);
            } elseif($judgeData['judgeType'] == 'in') {
                $pattern = $this->inCheck($answer, $judgeData['list']);
            } elseif($judgeData['judgeType'] == 'notIn') {
                $pattern = $this->notInCheck($answer, $judgeData['list']);
            }
            if($pattern == 'failure') {
                \Log::info('不合格理由：'. json_encode($formKey));
                \Log::info('回答：'. json_encode($answer));
                return $pattern;
            }
        }
        \Log::info('合格');
        return $pattern;
    }
    
    // 範囲外なら不合格
    private function checkAge($birthday, $lower, $upper)
    {
        $dateOfBirth = Carbon::createFromDate($birthday['year'], $birthday['month'], $birthday['day']);
        $age         = $dateOfBirth->age;
        
        \Log::info("年齢: {$age}");
        
        if($age < $lower || $age > $upper){
            return 'failure';
        }else{
            return 'success';
        }
    }

    // 含むなら不合格
    private function inCheck($answer, $checkList)
    {
        return in_array($answer, $checkList, true) ? 'failure' : 'success';
    }

    // 含まないなら不合格
    private function notInCheck($answer, $checkList)
    {
        return !in_array($answer, $checkList, true) ? 'failure' : 'success';
    }
}
