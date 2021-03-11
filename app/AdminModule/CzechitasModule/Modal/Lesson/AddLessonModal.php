<?php


namespace App\AdminModule\CzechitasModule\Modal\Lesson;



use App\AdminModule\CzechitasModule\Components\Lesson\AddLessonFormFactory;
use App\Model\Modal\BaseModal;

interface AddLessonModalFactory
{

	public function create(): AddLessonModal;
}



class AddLessonModal extends BaseModal
{


	/** @var AddLessonFormFactory */
	private $factory;



	public function __construct(AddLessonFormFactory $factory)
	{
		$this->factory = $factory;
	}



	public function createComponentForm()
	{
		$control = $this->factory->create();
		$control->onSuccess[] = function () {
			$this->flashMessage("Lekce byla přidána", "success");
			$this->close();
		};

		return $control->getForm();
	}



	public function render()
	{
		$this->template->setFile(__DIR__ . "/AddLessonModal.latte");
		$this->template->title = "Přidat lekci";
		$this->template->render();
	}
}
