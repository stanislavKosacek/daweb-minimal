<?php


namespace App\AdminModule\CzechitasModule\EditLessonModule\Modal;



use App\AdminModule\CzechitasModule\EditLessonModule\Components\SetLessonTeamFormFactory;
use App\Model\Czechitas\Lesson\Lesson;
use App\Model\Modal\BaseModal;

interface SetLessonTeamModalFactory
{

	public function create(Lesson $lesson): SetLessonTeamModal;
}



class SetLessonTeamModal extends BaseModal
{


	/** @var SetLessonTeamFormFactory */
	private $factory;

	/** @var Lesson */
	private $lesson;



	public function __construct(Lesson $lesson, SetLessonTeamFormFactory $factory)
	{
		$this->factory = $factory;
		$this->lesson = $lesson;
	}



	public function createComponentForm()
	{
		$control = $this->factory->create($this->lesson);
		$control->onSuccess[] = function () {
			$this->flashMessage("Tým byl upraven", "success");
			$this->close();
		};

		return $control->getForm();
	}



	public function render()
	{
		$this->template->setFile(__DIR__ . "/SetLessonTeamModal.latte");
		$this->template->title = "Upravit tým k lekci";
		$this->template->render();
	}
}
