<?php


namespace App\AdminModule\Modal;



use App\AdminModule\Components\Page\AddPageBlockFormFactory;
use App\Model\Modal\BaseModal;
use App\Model\Page\Page\Page;

interface AddPageBlockModalFactory
{

	public function create(Page $page): AddPageBlockModal;
}



class AddPageBlockModal extends BaseModal
{


	/** @var AddPageBlockFormFactory */
	private $factory;

	/** @var Page */
	private $page;



	public function __construct(Page $page, AddPageBlockFormFactory $factory)
	{
		$this->page = $page;
		$this->factory = $factory;
	}



	public function createComponentForm()
	{
		$control = $this->factory->create($this->page);
		$control->onSuccess[] = function () {
			$this->flashMessage("Blok byl přidán", "success");
			$this->close();
		};

		return $control->getForm();
	}



	public function render()
	{
		$this->template->setFile(__DIR__ . "/AddPageBlockModal.latte");
		$this->template->title = "Přidat blok stránky";
		$this->template->render();
	}
}
