<?php
declare(strict_types=1);
namespace packages\Text\Domain\Services;

class ShapTextService
{

    public function __construct(
    )
    {
    }
    /**
     * 送信するメッセージの取得と置換を行う
     * 
     * @param string $consumerStatus
     * @param string $shortUrl
     * 
     * @return $sendText
     */
    public function shapSmsSendText($consumerStatus, $shortUrl)
    {
        $configSearchKey = 'Text.sendSmsMessage.' . $consumerStatus;
        $sendText        = config($configSearchKey);
        if(empty($sendText)) {
            \Log::info($configSearchKey);
            \Log::error('送るメッセージがありません。');
        }
        $sendText = str_replace('{{url}}', $shortUrl, $sendText);
        return $sendText;
    }

    public function shapMailSendText($consumerStatus, $shortUrl)
    {
        $configSearchKey = 'Text.sendMailMessage.' . $consumerStatus;
        $sendText        = config($configSearchKey);
        if(empty($sendText)) {
            \Log::info($configSearchKey);
            \Log::error('送るメッセージがありません。');
        }
        $sendText = str_replace('{{url}}', $shortUrl, $sendText);
        return $sendText;
    }
}