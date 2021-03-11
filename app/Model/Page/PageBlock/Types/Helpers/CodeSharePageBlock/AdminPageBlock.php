<?php


namespace App\Model\Page\PageBlock\Types\Helpers\CodeSharePageBlock;

use App\AdminModule\CzechitasModule\Components\CodeShare\SetCodeShareFormFactory;
use App\Components\CodeShare\CodeShareComponentFactory;
use App\Model\Page\PageBlock\Types\CodeSharePageBlock;
use Nette\Application\UI\Control;
use Nette\Application\UI\Multiplier;

interface AdminPageBlockFactory
{

	public function create(CodeSharePageBlock $codeSharePageBlock): AdminPageBlock;

}



class AdminPageBlock extends Control
{

	/** @var CodeSharePageBlock */
	private $codeSharePageBlock;

	/** @var SetCodeShareFormFactory */
	private $setCodeShareFormFactory;

	/** @var CodeShareComponentFactory */
	private $codeShareComponentFactory;



	public function __construct(CodeSharePageBlock $codeSharePageBlock, SetCodeShareFormFactory $setCodeShareFormFactory, CodeShareComponentFactory $codeShareComponentFactory)
	{
		$this->codeSharePageBlock = $codeSharePageBlock;
		$this->setCodeShareFormFactory = $setCodeShareFormFactory;
		$this->codeShareComponentFactory = $codeShareComponentFactory;
	}



	public function render()
	{

		$this->template->setFile(__DIR__ . "/AdminPageBlock.latte");
		$this->template->pageBlock = $this->codeSharePageBlock;
		$this->template->render();
	}



	protected function createComponentForm()
	{
		$form = $this->setCodeShareFormFactory->create($this->codeSharePageBlock->getCodeShare(), $this->codeSharePageBlock);

		$form->onSuccess[] = function () {
			$this->redirect("this");
		};
		$control = $form->getForm();
		$control->setAjax(TRUE);

		return $control;
	}



	public function createComponentCodeShareComponent()
	{
		return $this->codeShareComponentFactory->create($this->codeSharePageBlock->getCodeShare(), FALSE);
	}

}
