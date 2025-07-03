<?php
/**
 * ステータス毎の送信するメッセージ
 */

$duplicate  = "この度は、数ある求人の中から弊社の求人にご応募頂き、誠にありがとうございます。\n";
$duplicate .= "前回のご応募時に送信したアンケートから、ご回答をお願いします。";

$new  = "【選考のご案内】\n";
$new .= "この度は数ある求人の中から、\n";
$new .= "弊社の求人にご応募頂き、誠にありがとうございます。\n\n";
$new .= "下記URLよりweb選考にお進みくださいませ。\n\n";
$new .= "{{url}}\n";
$new .= "※このSMSは送信専用です。";

$decideInterview  = "【面接確定のご連絡】\n\n";
$decideInterview .= "面接日時が確定しました！\n";
$decideInterview .= "下記のURLから面接内容をご確認ください。\n";
$decideInterview .= "{{url}}\n\n";

$remind  = "【面接日時確認のご連絡】\n\n";
$remind .= "下記のリンクから面接内容をご確認ください。\n";
$remind .= "{{url}}\n\n";

$failure  = "下記のURLからWEB選考の結果をご確認ください。\n";
$failure .= "{{url}}";

return [
    'duplicate'       => $duplicate,
    'new'             => $new,
    'decideInterview' => $decideInterview,
    'remind'          => $remind,
    'failure'         => $failure
];
