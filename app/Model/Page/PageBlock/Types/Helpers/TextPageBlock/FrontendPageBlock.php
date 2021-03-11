<?php


namespace App\Model\Page\PageBlock\Types\Helpers\TextPageBlock;

use App\Model\Page\PageBlock\Types\TextPageBlock;
use Nette\Application\UI\Control;

interface FrontendPageBlockFactory
{

	public function create(TextPageBlock $textPageBlock): FrontendPageBlock;

}



class FrontendPageBlock extends Control
{

	/** @var TextPageBlock */
	private $textPageBlock;



	public function __construct(TextPageBlock $textPageBlock)
	{
		$this->textPageBlock = $textPageBlock;
	}



	public function render()
	{
		$this->template->setFile(__DIR__ . "/FrontendPageBlock.latte");
		$this->template->pageBlock = $this->textPageBlock;
		$this->template->render();
	}

}
