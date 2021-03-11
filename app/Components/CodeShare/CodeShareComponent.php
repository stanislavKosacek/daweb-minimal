<?php


namespace App\Components\CodeShare;


use App\Model\Czechitas\CodeShare\CodeShare;
use Nette\Application\UI\Control;

interface CodeShareComponentFactory
{

	public function create(CodeShare $codeShare, bool $showHeader = TRUE): CodeShareComponent;

}



class CodeShareComponent extends Control
{

	/**
	 * @sk Tady bude nějaká anotace
	 * @var CodeShare
	 */
	private $codeShare;

	/** @var bool */
	private $showHeader;



	public function __construct(CodeShare $codeShare, bool $showHeader = TRUE)
	{
		$this->codeShare = $codeShare;
		$this->showHeader = $showHeader;
	}



	public function render()
	{
		$this->template->setFile(__DIR__ . "/CodeShareComponent.latte");
		$this->template->selectedCodeShare = $this->codeShare;
		$this->template->showHeader = $this->showHeader;

		$this->template->uniqueId = $this->getUniqueId();
		$this->template->render();
	}

}
