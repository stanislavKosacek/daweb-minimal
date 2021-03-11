<?php


namespace App\AdminModule\CzechitasModule\EditLessonModule\Components;



use App\Model\Czechitas\HomeworkAssignment\HomeworkAssignment;
use App\Model\Czechitas\Lesson\Lesson;
use App\Model\Form\BaseForm;
use App\Model\Form\BaseFormFactory;
use App\Model\Orm;
use Nette\SmartObject;
use Nette\Utils\Strings;
use Nextras\Dbal\Utils\DateTimeImmutable;

interface EditHomeworkAssignmentFormFactory
{

	public function create(Lesson $lesson, HomeworkAssignment $homeworkAssignment): EditHomeworkAssignmentForm;
}



class EditHomeworkAssignmentForm
{

	use SmartObject;

	/** @var Lesson */
	private $lesson;

	/** @var HomeworkAssignment */
	private $homeworkAssignment;

	/** @var Orm */
	private $orm;

	/** @var BaseFormFactory */
	private $baseFormFactory;

	/** @var array */
	public $onSuccess = [];



	public function __construct(Lesson $lesson, HomeworkAssignment $homeworkAssignment, Orm $orm, BaseFormFactory $baseFormFactory)
	{
		$this->lesson = $lesson;
		$this->homeworkAssignment = $homeworkAssignment;
		$this->orm = $orm;
		$this->baseFormFactory = $baseFormFactory;
	}



	public function getForm(): BaseForm
	{

		$form = $this->baseFormFactory->create();
		$form->addGroup();
		$form->addText("name", "Název*")
			 ->setRequired("Zadejte název");

		$form->addText("deadline", "Deadline")
			 ->setHtmlType("datetime-local");

		$form->setDefaults([
			"name" => $this->homeworkAssignment->getPage()->getName(),
			"deadline" => $this->homeworkAssignment->getDeadline() ? $this->homeworkAssignment->getDeadline()->format("Y-m-d\TH:i") : NULL,
		]);

		$form->addSubmitButton("send", "Upravit");
		$form->onSuccess[] = [$this, "processForm"];

		return $form;

	}



	public function processForm(BaseForm $form)
	{
		$values = $form->getValues();

		$homework = $this->homeworkAssignment;
		$gitFolder = $this->lesson->getType() . "/" . Strings::webalize($values->name);
		$homework->setGitFolder($gitFolder);
		if ($values->deadline) {
			$homework->setDeadline(new DateTimeImmutable($values->deadline));
		} else {
			$homework->setDeadline(NULL);
		}

		$this->orm->persistAndFlush($homework);

		$this->onSuccess();
	}

}
