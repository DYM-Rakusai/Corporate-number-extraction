<?php
declare(strict_types=1);
namespace packages\Url\Domain\Services;

use Aws\DynamoDb\Exception\DynamoDbException;
use Aws\DynamoDb\DynamoDbClient;
use Aws\Result;
use Aws\Sdk;
use Log;

class MakeShortUrlService
{
    const BASE_SHORTENED_URL = 'https://rakusai.3gk.me/';
    const DYNAMO_DB_TABLE_NAME = 'RakusaiShortUrl';
    public $dynamoDb;

    public function __construct(
    )
    {
        $this->dynamoDb;
    }

    /**
     * 短縮URL生成
     *
     * @link https://bit.ly/30tydyu
     * @param string $url
     * @return string $resultUrl
     */
    public function shorten($url)
    {
        $sendEnv = config('app.send_env');
        if($sendEnv == 'local') {
            return $url;
        }else{
            $resultUrl = '';
            // DynamoDBで見つかったらそのURLを返す
            $savedUrl = $this->getUrlOnDynamoDb($url);
            if (!empty($savedUrl)) {
                $resultUrl = $savedUrl;
                return $resultUrl;
            }
            $urlHash = $this->getHash($url, 8);
            $shortenedUrl = sprintf('%s%s', self::BASE_SHORTENED_URL, $urlHash);
            if ($this->setUrlOnDynamoDb($url, $shortenedUrl)) {
                $resultUrl = $shortenedUrl;
            }
        }
        return $resultUrl;
    }

    /**
     * AWS DynamoDBから短縮前URL取得
     *
     * @link https://qiita.com/snoguchi/items/a16dfb831d6ef53d5f4e
     * @access public
     * @param string $originalUrl 短縮前URL
     * @return Result $result 結果
     */
    public function getUrlOnDynamoDb($originalUrl)
    {
        $url = '';
        $dynamoDb = $this->getDynamoDbClient();
        $params = [
            'TableName' => self::DYNAMO_DB_TABLE_NAME,
            'Key' => [
                'OriginalUrl' => ['S' => $originalUrl],
            ],
        ];
        Log::notice(['検索条件', $params], ['file' => __FILE__, 'line' => __LINE__]);
        try {
            $result = $dynamoDb->getItem($params);
            $resultUrl = $result->search('Item.ShortenedUrl.S');
            if (!empty($resultUrl)) {
                $url = $resultUrl;
            }
            Log::notice($result->search('Item.ShortenedUrl'), ['file' => __FILE__, 'line' => __LINE__]);
        } catch (DynamoDbException $e) {
            Log::critical(['短縮URL取得失敗', $e->getAwsErrorMessage()], ['file' => __FILE__, 'line' => __LINE__]);
        }
        return $url;
    }

    /**
     * ハッシュ値を取得する
     *
     * @param string $string
     * @param integer $length
     * @return string
     */
    public function getHash($string, $length=6)
    {
        $character = array_merge(
            range('0', '9'),
            range('a', 'z'),
            range('A', 'Z')
        );
        $hash = hash('sha256', $string);  // ハッシュ値の取得
        $number = hexdec($hash);         // 16進数ハッシュ値を10進数
        $result = $this->decNth($number, $character);      // 62進数に変換
        return substr($result, 0, $length); //$len の長さ文抜き出し
    }

    public function setUrlOnDynamoDb($originalUrl, $shortenedUrl)
    {
        $dynamoDb = $this->getDynamoDbClient();
        $item = [
            'OriginalUrl' => ['S' => $originalUrl],
            'ShortenedUrl' => ['S' => $shortenedUrl],
        ];
        $params = [
            'TableName' => self::DYNAMO_DB_TABLE_NAME,
            'Item' => $item,
        ];

        try {
            $result = $dynamoDb->putItem($params);
            Log::notice(['追加完了', $item]);
        } catch (DynamoDbException $e) {
            Log::critical(
                ['短縮URL追加失敗', $e->getAwsErrorMessage()],
                ['file' => __FILE__, 'line' => __LINE__]
            );
        }
        if (is_a($result, 'Aws\Result')) {
            return true;
        };
        return false;
    }

    /**
     * 62進数変換
     *
     * @param int|float $number
     * @param array $character 文字配列
     * @return string $result 結果
     */
    public function decNth($num, $char)
    {
        $base   = count($char);
        $result = '';
        while ($num > 0) {
            $result = $char[fmod($num, $base)] . $result;
            $num = floor($num / $base);
        }
        return empty($result) ? '' : $result;
    }

    /**
     * AWS DynamoDBに接続する
     *
     * @return DynamoDbClient $dynamoDb
     */
    public function getDynamoDbClient()
    {
        /*if (is_a($this->$dynamoDb, 'Aws\DynamoDb\DynamoDbClient')) {
            Log::notice('DynamoDB クライアントインスタンス再利用');
            return $this->$dynamoDb;
        }*/
        $sdk = new Sdk([
            'endpoint' => 'http://dynamodb.ap-northeast-1.amazonaws.com',
            'region'   => 'ap-northeast-1',
            'version'  => 'latest'
        ]);
        $dynamoDb = $sdk->createDynamoDb();
        #$this->$dynamoDb = $dynamoDb;
        return $dynamoDb;
    }
}