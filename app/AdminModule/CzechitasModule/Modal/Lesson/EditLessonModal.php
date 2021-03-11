<?php


namespace App\AdminModule\CzechitasModule\Modal\Lesson;



use App\AdminModule\CzechitasModule\Components\Lesson\EditLessonFormFactory;
use App\Model\Czechitas\Lesson\Lesson;
use App\Model\Modal\BaseModal;

interface EditLessonModalFactory
{

	public function create(Lesson $lesson): EditLessonModal;
}



class EditLessonModal extends BaseModal
{


	/** @var EditLessonFormFactory */
	private $factory;

	/** @var Lesson */
	private $lesson;



	public function __construct(Lesson $lesson, EditLessonFormFactory $factory)
	{
		$this->factory = $factory;
		$this->lesson = $lesson;
	}



	public function createComponentForm()
	{
		$control = $this->factory->create($this->lesson);
		$control->onSuccess[] = function () {
			$this->flashMessage("Lekce byla upravena", "success");
			$this->close();
		};

		return $control->getForm();
	}



	public function render()
	{
		$this->template->setFile(__DIR__ . "/EditLessonModal.latte");
		$this->template->title = "Upravit lekci";
		$this->template->render();
	}
}
