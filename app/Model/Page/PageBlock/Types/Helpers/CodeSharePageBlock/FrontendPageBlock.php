<?php


namespace App\Model\Page\PageBlock\Types\Helpers\CodeSharePageBlock;

use App\Components\CodeShare\CodeShareComponentFactory;
use App\Model\Page\PageBlock\Types\CodeSharePageBlock;
use Nette\Application\UI\Control;

interface FrontendPageBlockFactory
{

	public function create(CodeSharePageBlock $codeSharePageBlock): FrontendPageBlock;

}



class FrontendPageBlock extends Control
{

	/** @var CodeSharePageBlock */
	private $codeSharePageBlock;

	/** @var CodeShareComponentFactory */
	private $codeShareComponentFactory;



	public function __construct(CodeSharePageBlock $codeSharePageBlock, CodeShareComponentFactory $codeShareComponentFactory)
	{
		$this->codeSharePageBlock = $codeSharePageBlock;
		$this->codeShareComponentFactory = $codeShareComponentFactory;
	}



	public function render()
	{
		$this->template->setFile(__DIR__ . "/FrontendPageBlock.latte");
		$this->template->pageBlock = $this->codeSharePageBlock;
		$this->template->render();
	}



	public function createComponentCodeShareComponent()
	{
		return $this->codeShareComponentFactory->create($this->codeSharePageBlock->getCodeShare(), FALSE);
	}

}
