<?php

namespace App\Model\Email\EmailType;

use Nette\DI\Container;

class EmailTypeProvider
{

	/** @var Container */
	private $container;

	/** @var IEmailType[] */
	private $strategies;



	public function __construct(Container $container)
	{
		$this->container = $container;
		foreach ($this->container->findByType(IEmailType::class) as $name) {
			$service = $container->getService($name);
			$this->strategies[$service->getEmailType()] = $service;
		}
	}



	public function getEmailTypesList(): array
	{
		return $this->strategies;
	}



	public function getEmailTypesNameList(): array
	{
		$strategies = [];
		foreach ($this->strategies as $strategy) {
			$strategies[$strategy->getEmailType()] = $strategy->getTranslatedName();
		}

		return $strategies;
	}



	public function getEmailTypeTranslatedName(string $type): ?string
	{
		$list = $this->getEmailTypesNameList();

		if (array_key_exists($type, $list)) {
			return $list[$type];
		}

		return NULL;
	}

}
