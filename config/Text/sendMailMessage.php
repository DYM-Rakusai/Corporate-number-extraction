<?php
/**
 * ステータス毎の送信するメッセージ
 */

$duplicate  = "この度は、数ある求人の中から弊社の求人にご応募頂き、誠にありがとうございます。<br>";
$duplicate .= "前回のご応募時に送信したアンケートからご回答をお願いします。";

$new  = "この度は数ある求人の中から、<br>";
$new .= "弊社の求人にご応募頂き、誠にありがとうございます。<br><br>";
$new .= "下記URLよりweb選考にお進みくださいませ。<br><br>";
$new .= "{{url}}<br>";
$new .= "※このメールは送信専用です。";

$decideInterview  = "面接日時が確定しました！<br>";
$decideInterview .= "下記のURLから面接内容をご確認ください。<br>";
$decideInterview .= "{{url}}";


$remind  = "下記のリンクから面接内容をご確認ください。<br>";
$remind .= "{{url}}";

$decideForCompany  = "面接日時が確定しました！<br>";
$decideForCompany .= "下記のURLから面接が決まった方の応募者情報をご確認ください。<br>";
$decideForCompany .= "{{url}}";

$adjustForCompany  = "下記のURLから回答内容を確認し、応募者の面接日程を調整してください。<br>";
$adjustForCompany .= "{{url}}";

$failure = "下記のURLからWEB選考の結果をご確認ください。<br>";
$failure .= "{{url}}";

return [
    'duplicate'        => $duplicate,
    'new'              => $new,
    'decideInterview'  => $decideInterview,
    'remind'           => $remind,
    'decideForCompany' => $decideForCompany,
    'adjustForCompany' => $adjustForCompany,
    'failure'          => $failure
];


