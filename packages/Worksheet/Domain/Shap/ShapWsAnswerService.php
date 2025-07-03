<?php
declare(strict_types=1);
namespace packages\Worksheet\Domain\Shap;
use Illuminate\Support\Carbon;

class ShapWsAnswerService
{
    public function __construct(
    )
    {
    }

    public function shapWsAnswerText(
        $worksheetAnswers      ,
        $schedules       = NULL,
        $adjustSchedules = NULL
    )
    {
        $formChoices = config('Form.formChoices.worksheet');
        $answerText  = '';
        
        foreach($formChoices as $formKey => $formChoice) {
            $wsAnswer = $worksheetAnswers[$formKey] ?? '';
            if(!empty($wsAnswer)) {
                if    ($formKey == 'birthday'       ) {
                    $date        = Carbon::createFromDate($wsAnswer['year'], $wsAnswer['month'], $wsAnswer['day']);
                    $answerText .= "{$formChoice['title']} : {$date->format('Y年n月j日')}（{$date->age}歳）\n";
                } 
                elseif(gettype($wsAnswer) == 'array') {
                    $answer = '';
                    foreach($wsAnswer as $wsAnswerVal) {
                        $answer  .=   $formChoice['choice'][$wsAnswerVal] ?? $wsAnswerVal;
                    }
                    $answerText  .= "{$formChoice['title']} : {$answer}\n";
                } 
                else {
                    $wsAnswerText =   $formChoice['choice'][$wsAnswer]    ?? $wsAnswer;
                    $answerText  .= "{$formChoice['title']} : {$wsAnswerText}\n";
                }
            }
        }
        if(!empty($adjustSchedules)) {
            $answerText .= "\n面接希望日\n";
            foreach($adjustSchedules as $adjustSchedule) {
                if(array_key_exists('memo', $adjustSchedule)) {
                    $answerText .= 'その他：' . "\n";
                    $answerText .= $adjustSchedule['memo'] . "\n";
                    continue;
                }
                $start       = (new Carbon($adjustSchedule['start']))->format('Y/m/d H:i');
                $end         = (new Carbon($adjustSchedule['end'  ]))->format('Y/m/d H:i');
                $answerText .= "{$start} 〜 {$end}\n";
            }
        }

        return $answerText;
    }
}
