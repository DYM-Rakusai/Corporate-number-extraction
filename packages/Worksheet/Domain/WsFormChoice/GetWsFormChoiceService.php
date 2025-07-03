<?php
declare(strict_types=1);
namespace packages\Worksheet\Domain\WsFormChoice;

class GetWsFormChoiceService
{
    public function __construct(
    )
    {
    }

    public function getFormChoices($consumerData)
    {
        $formChoices = config('Form.formChoices');
        return  $formChoices['worksheet'];
    }
}
