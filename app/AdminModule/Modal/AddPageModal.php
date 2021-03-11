<?php


namespace App\AdminModule\Modal;



use App\AdminModule\Components\Page\AddPageFormFactory;
use App\Model\Modal\BaseModal;

interface AddPageModalFactory
{

	public function create(): AddPageModal;
}



class AddPageModal extends BaseModal
{


	/** @var AddPageFormFactory */
	private $factory;



	public function __construct(AddPageFormFactory $factory)
	{

		$this->factory = $factory;
	}



	public function createComponentForm()
	{
		$control = $this->factory->create();
		$control->onSuccess[] = function () {
			$this->flashMessage("Stránka byla přidána", "success");
			$this->close();
		};

		return $control->getForm();
	}



	public function render()
	{
		$this->template->setFile(__DIR__ . "/AddPageModal.latte");
		$this->template->title = "Přidat stránku";
		$this->template->render();
	}
}
