<?php
declare(strict_types=1);
namespace packages\Spread\Domain\Services;

use Log;
use Google_Client;
use Google_Service_Sheets;

class GetGoogleClientService
{
    private $googleClient;

    public function __construct(
    )
    {
        $this->setGoogleClient();
    }

    private function setGoogleClient()
    {
        $client = new Google_Client();//Googleクライアントインスタンスを作成
        $client->setScopes(
            [
                \Google_Service_Sheets::SPREADSHEETS,
                \Google_Service_Sheets::DRIVE
            ]
        );
        $googleDataArray    = \Config::get('googleAccount.account');
        $client->setAuthConfig($googleDataArray);
        $googleSpreadClient = new Google_Service_Sheets($client);//スプレッドを操作するインスタンス
        $this->googleClient = $googleSpreadClient;
    }

    public function getGoogleClient()
    {
        return $this->googleClient;
    }
}