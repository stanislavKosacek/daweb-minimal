<?php

namespace App\Model\Page\PageBlock\Types;

use App\Model\Page\PageBlock\PageBlock;
use App\Model\Page\PageBlock\PageBlockRepository;
use Nette\DI\Container;
use Nette\InvalidArgumentException;
use Nette\Utils\Strings;

class PageBlockTypeProvider
{

	/** @var Container */
	private $container;

	/** @var IPageBlock[] */
	private $strategies;

	/** @var PageBlockRepository */
	private $pageBlockRepository;



	public function __construct(Container $container, PageBlockRepository $pageBlockRepository)
	{
		$this->container = $container;
		foreach ($this->container->findByType(IPageBlock::class) as $name) {
			$service = $container->getService($name);
			$this->strategies[$service->getTypeStatic()] = $service;
		}
		$this->pageBlockRepository = $pageBlockRepository;
	}



	public function getBlockTypesList(): array
	{
		return $this->strategies;
	}



	public function getBlockTypeByTypeName(string $type): IPageBlock
	{

		if (!array_key_exists($type, $this->strategies)) {
			throw new InvalidArgumentException('Strategy (\'' . $type . '\') was not found. Only ' . join(", ", array_keys($this->strategies)) . " are present.");
		}

		return $this->strategies[$type];

	}



	public function getBlockTypesNameList(): array
	{
		$strategies = [];
		foreach ($this->strategies as $strategy) {
			$strategies[$strategy->getTypeStatic()] = $strategy->getTranslatedName();
		}

		return $strategies;
	}



	public function flushBlock(PageBlock $block)
	{
		$this->pageBlockRepository->persistAndFlush($block);
	}

}
