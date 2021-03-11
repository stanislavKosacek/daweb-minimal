<?php


namespace App\Model\Page\PageBlock;


use Nette\Utils\Json;
use Nette\Utils\Strings;
use Nextras\Orm\Entity\ImmutableValuePropertyWrapper;
use Nextras\Orm\Entity\IProperty;
use Nextras\Orm\Entity\Reflection\PropertyMetadata;


class JsonContainer extends ImmutableValuePropertyWrapper
{


	public function convertToRawValue($value)
	{
		return Json::encode($value);
	}



	public function convertFromRawValue($value)
	{
		if ($value) {
			return Json::decode($value);
		}
		return NULL;
	}
}
