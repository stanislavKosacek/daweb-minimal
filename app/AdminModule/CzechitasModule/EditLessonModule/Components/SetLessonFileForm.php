<?php


namespace App\AdminModule\CzechitasModule\EditLessonModule\Components;


use App\Model\Czechitas\Lesson\Lesson;
use App\Model\Czechitas\LessonFile\LessonFile;
use App\Model\Form\BaseForm;
use App\Model\Form\BaseFormFactory;
use App\Model\Helpers\Storage\LessonFileStorage;
use App\Model\Orm;
use Nette\Http\FileUpload;
use Nette\SmartObject;

interface SetLessonFileFormFactory
{

	public function create(Lesson $lesson, LessonFile $lessonFile = NULL): SetLessonFileForm;
}



class SetLessonFileForm
{

	use SmartObject;

	/** @var Lesson|null */
	private $lesson;

	/** @var LessonFile */
	private $lessonFile;

	/** @var Orm */
	private $orm;

	/** @var LessonFileStorage */
	private $storage;

	/** @var BaseFormFactory */
	private $baseFormFactory;

	/** @var array */
	public $onSuccess = [];



	public function __construct(?Lesson $lesson, LessonFile $lessonFile = NULL, Orm $orm, LessonFileStorage $storage, BaseFormFactory $baseFormFactory)
	{
		$this->lesson = $lesson;
		$this->lessonFile = $lessonFile;
		$this->orm = $orm;
		$this->storage = $storage;
		$this->baseFormFactory = $baseFormFactory;
	}



	public function getForm(): BaseForm
	{

		$form = $this->baseFormFactory->create();
		$form->addTextArea("description", "Popis");
		$upload = $form->addUpload("filename", "Soubor");

		if (!$this->lessonFile) {
			$upload->setRequired("Nahrajte soubor");
		}


		$defaults = [];
		if ($this->lessonFile) {
			$defaults["description"] = $this->lessonFile->getDescription();
		}
		$form->setDefaults($defaults);
		$form->addSubmitButton("send", $this->lessonFile ? "Upravit" : "Přidat");
		$form->onSuccess[] = [$this, "processForm"];

		return $form;

	}



	public function processForm(BaseForm $form)
	{
		$values = $form->getValues();
		$lessonFile = $this->lessonFile ? $this->lessonFile : new LessonFile();
		$lessonFile->setDescription($values->description);
		$lessonFile->setLesson($this->lesson);

		if ($values->filename->isOk()) {

			if ($this->lessonFile and $this->lessonFile->getFilename()) {
				$this->storage->deleteFile($this->lessonFile->getFilename());
			}
			$lessonFile->setFilename($this->storage->upload($values->filename));
			$lessonFile->setName($values->filename->getName());
		}

		$this->orm->persistAndFlush($lessonFile);
		$this->onSuccess();
	}


}
