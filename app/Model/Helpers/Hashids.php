<?php


namespace App\Model\Helpers;


class Hashids extends \Hashids\Hashids
{
	public function __construct(array $configParameters)
	{
		parent::__construct($configParameters["salt"], $configParameters["hashLength"]);
	}

}
