<?php


namespace App\AdminModule\CzechitasModule\EditLessonModule\Modal;


use App\AdminModule\CzechitasModule\EditLessonModule\Components\SetLessonFileFormFactory;
use App\Model\Czechitas\Lesson\Lesson;
use App\Model\Czechitas\LessonFile\LessonFile;
use App\Model\Modal\BaseModal;

interface SetLessonFileModalFactory
{


	public function create(Lesson $lesson, LessonFile $lessonFile = NULL): SetLessonFileModal;

}



class SetLessonFileModal extends BaseModal
{

	/** @var Lesson */
	private $lesson;

	/** @var LessonFile */
	private $lessonFile;

	/** @var SetLessonFileFormFactory */
	private $factory;

	/** @var array */
	public $onSuccess = [];



	public function __construct(Lesson $lesson, LessonFile $lessonFile = NULL, SetLessonFileFormFactory $factory)
	{
		$this->lesson = $lesson;
		$this->lessonFile = $lessonFile;
		$this->factory = $factory;
	}



	public function createComponentForm()
	{
		$formFactory = $this->factory->create($this->lesson, $this->lessonFile);
		$formFactory->onSuccess[] = function () {
			$this->close();
		};

		return $formFactory->getForm();
	}



	public function render()
	{
		$this->template->setFile(__DIR__ . "/SetLessonFileModal.latte");
		$this->template->title = $this->lessonFile ? 'Upravit soubor' : 'Přidat soubor';
		$this->template->render();
	}

}
