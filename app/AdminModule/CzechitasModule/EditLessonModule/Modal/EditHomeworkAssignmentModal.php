<?php


namespace App\AdminModule\CzechitasModule\EditLessonModule\Modal;



use App\AdminModule\CzechitasModule\EditLessonModule\Components\EditHomeworkAssignmentFormFactory;
use App\Model\Czechitas\HomeworkAssignment\HomeworkAssignment;
use App\Model\Czechitas\Lesson\Lesson;
use App\Model\Modal\BaseModal;

interface EditHomeworkAssignmentModalFactory
{

	public function create(Lesson $lesson, HomeworkAssignment $homeworkAssignment): EditHomeworkAssignmentModal;
}



class EditHomeworkAssignmentModal extends BaseModal
{


	/** @var EditHomeworkAssignmentFormFactory */
	private $factory;

	/** @var Lesson */
	private $lesson;

	/** @var HomeworkAssignment */
	private $homeworkAssignment;



	public function __construct(Lesson $lesson, HomeworkAssignment $homeworkAssignment, EditHomeworkAssignmentFormFactory $factory)
	{
		$this->lesson = $lesson;
		$this->homeworkAssignment = $homeworkAssignment;
		$this->factory = $factory;
	}



	public function createComponentForm()
	{
		$control = $this->factory->create($this->lesson, $this->homeworkAssignment);
		$control->onSuccess[] = function () {
			$this->flashMessage("Úkol byl upraven", "success");
			$this->close();
		};

		return $control->getForm();
	}



	public function render()
	{
		$this->template->setFile(__DIR__ . "/EditHomeworkAssignmentModal.latte");
		$this->template->title = "Upravit úkol";
		$this->template->render();
	}
}
