<?php


namespace App\Model\Page\PageBlock\Types\Helpers\TextPageBlock;

use App\Model\Form\BaseForm;
use App\Model\Form\BaseFormFactory;
use App\Model\Orm;
use App\Model\Page\PageBlock\Types\TextPageBlock;
use Nette\Application\UI\Control;
use Nette\Utils\Random;

interface AdminPageBlockFactory
{

	public function create(TextPageBlock $textPageBlock): AdminPageBlock;

}



class AdminPageBlock extends Control
{

	/** @var TextPageBlock */
	private $textPageBlock;

	/** @var BaseFormFactory */
	private $baseFormFactory;

	/** @var Orm */
	private $orm;



	public function __construct(TextPageBlock $textPageBlock, BaseFormFactory $baseFormFactory, Orm $orm)
	{
		$this->textPageBlock = $textPageBlock;
		$this->baseFormFactory = $baseFormFactory;
		$this->orm = $orm;
	}



	public function render()
	{
		$this->template->setFile(__DIR__ . "/AdminPageBlock.latte");
		$this->template->pageBlock = $this->textPageBlock;
		$this->template->render();
	}



	public function createComponentForm()
	{
		$form = $this->baseFormFactory->create();
		$form->setAjax(TRUE);
		$form->addTextArea("text", "text")
			 ->setHtmlAttribute("class", "markdown")
			 ->setHtmlId("markdown" . Random::generate(9));

		$form->addSubmitButton("save", $this->textPageBlock->getText() ? "Upravit" : "Uložit");

		if ($this->textPageBlock->getText()) {
			$form->setDefaults([
				"text" => $this->textPageBlock->getText(),
			]);
		}

		$form->onSuccess[] = [$this, "processForm"];


		return $form->getForm();
	}



	public function processForm(BaseForm $form)
	{
		$values = $form->getValues();


		$this->textPageBlock->data = ["text" => $values->text];

		$this->orm->persistAndFlush($this->textPageBlock);

		$this->redirect("this");
	}

}
