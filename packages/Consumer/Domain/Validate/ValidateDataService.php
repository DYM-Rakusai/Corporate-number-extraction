<?php
declare(strict_types=1);
namespace packages\Consumer\Domain\Validate;

class ValidateDataService
{
    public function __construct(
    )
    {
    }

    public function isValidTel($tel)
    {
        $isValid = false;
        if(empty($tel)) {
			return false;
        }
        if (preg_match("/0[7-9]0[0-9]\d{3}\d{4}$/", $tel)) {
            $isValid = true;
		}
        return $isValid;
    }

    public function isValidMail($mail)
    {
		$pattern = '/^.*@[a-zA-Z0-9-]+(?:.[a-zA-Z0-9-]+)*$/';
        if(empty($mail)) {
			return false;
        }
		if ( preg_match($pattern, $mail) ) {
			return true;
		} else {
			return false;
		}
    }

}



