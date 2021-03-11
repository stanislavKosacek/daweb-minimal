<?php


namespace App\AdminModule\Modal;



use App\AdminModule\Components\Page\EditPageFormFactory;
use App\Model\Modal\BaseModal;
use App\Model\Page\Page\Page;

interface EditPageModalFactory
{

	public function create(Page $page): EditPageModal;
}



class EditPageModal extends BaseModal
{


	/** @var Page */
	private $page;

	/** @var EditPageFormFactory */
	private $factory;



	public function __construct(Page $page, EditPageFormFactory $factory)
	{
		$this->page = $page;
		$this->factory = $factory;
	}



	public function createComponentForm()
	{
		$control = $this->factory->create($this->page);
		$control->onSuccess[] = function () {
			$this->flashMessage("Stránka byla upravena", "success");
			$this->close();
		};

		return $control->getForm();
	}



	public function render()
	{
		$this->template->setFile(__DIR__ . "/EditPageModal.latte");
		$this->template->title = "Upravit stránku";
		$this->template->render();
	}
}
