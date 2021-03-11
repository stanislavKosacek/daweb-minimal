<?php


namespace App\AdminModule\CzechitasModule\EditLessonModule\Modal;



use App\AdminModule\CzechitasModule\EditLessonModule\Components\AddHomeworkAssignmentFormFactory;
use App\Model\Czechitas\HomeworkAssignment\HomeworkAssignment;
use App\Model\Czechitas\Lesson\Lesson;
use App\Model\Modal\BaseModal;

interface AddHomeworkAssignmentModalFactory
{

	public function create(Lesson $lesson): AddHomeworkAssignmentModal;
}



class AddHomeworkAssignmentModal extends BaseModal
{


	/** @var AddHomeworkAssignmentFormFactory */
	private $factory;

	/** @var Lesson */
	private $lesson;



	public function __construct(Lesson $lesson, AddHomeworkAssignmentFormFactory $factory)
	{
		$this->factory = $factory;
		$this->lesson = $lesson;
	}



	public function createComponentForm()
	{
		$control = $this->factory->create($this->lesson);
		$control->onSuccess[] = function (HomeworkAssignment $homeworkAssignment) {
			$this->flashMessage("Úkol byl přidán", "success");
			$this->presenter->forward(":Admin:Czechitas:EditLesson:Homework:detail", ["id" => $homeworkAssignment->getLesson()->getId(), "homeworkId" => $homeworkAssignment->getId()]);
		};

		return $control->getForm();
	}



	public function render()
	{
		$this->template->setFile(__DIR__ . "/AddHomeworkAssignmentModal.latte");
		$this->template->title = "Upravit tým k lekci";
		$this->template->render();
	}
}
