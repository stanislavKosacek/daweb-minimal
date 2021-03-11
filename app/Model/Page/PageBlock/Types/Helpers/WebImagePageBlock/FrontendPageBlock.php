<?php


namespace App\Model\Page\PageBlock\Types\Helpers\WebImagePageBlock;

use App\Model\Page\PageBlock\Types\WebImagePageBlock;
use Nette\Application\UI\Control;

interface FrontendPageBlockFactory
{

	public function create(WebImagePageBlock $webImagePageBlock): FrontendPageBlock;

}



class FrontendPageBlock extends Control
{

	/** @var WebImagePageBlock */
	private $webImagePageBlock;



	public function __construct(WebImagePageBlock $webImagePageBlock)
	{
		$this->webImagePageBlock = $webImagePageBlock;
	}



	public function render()
	{
		$this->template->setFile(__DIR__ . "/FrontendPageBlock.latte");
		$this->template->pageBlock = $this->webImagePageBlock;
		$this->template->render();
	}

}
