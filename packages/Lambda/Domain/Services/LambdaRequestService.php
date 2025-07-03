<?php
declare(strict_types=1);
namespace packages\Lambda\Domain\Services;

use Aws\Lambda\LambdaClient;
use Aws\Lambda\Exception\LambdaException;

class LambdaRequestService
{

    public function __construct(
    )
    {
    }

    public function lambdaRequest($requestMethod, $requestParam)
    {
        
        $sendEnv = config('app.send_env');
        if($sendEnv != 'local') {
            try {
                $client = LambdaClient::factory( array(
                    "region"  => "ap-northeast-1",
                    'version' => 'latest'
                ) );
                $result = $client->invoke( array(
                    'FunctionName'   => $requestMethod,
                    'InvocationType' => 'Event',
                    'Payload'        => json_encode( $requestParam ),
                ));
            } catch ( LambdaException $e ) {
                throw $e;
            } catch ( Exception $e ) {
                throw $e;
            }
        }
    }
}