<?php

namespace App\AdminModule\CzechitasModule\Components\CodeShare;

use App\Model\Czechitas\CodeShare\CodeShare;
use App\Model\Czechitas\CodeShare\CodeShareRepository;
use App\Model\Czechitas\Exercise\Exercise;
use App\Model\Czechitas\ExerciseSolutionFile\ExerciseSolutionFile;
use App\Model\Form\BaseForm;
use App\Model\Form\BaseFormFactory;
use App\Model\Orm;
use App\Model\Page\PageBlock\Types\CodeSharePageBlock;
use Nette\SmartObject;
use Nette\Utils\Json;

interface SetExerciseFileFormFactory
{

	function create(Exercise $exercise, ExerciseSolutionFile $exerciseSolutionFile = NULL): SetExerciseFileForm;
}



class SetExerciseFileForm
{

	use SmartObject;



	/** @var array */
	public $onSuccess = [];

	/** @var Exercise */
	private $exercise;

	/** @var ExerciseSolutionFile|null */
	private $exerciseSolutionFile;

	/** @var Orm */
	private $orm;

	/** @var BaseFormFactory */
	private $baseFormFactory;



	public function __construct(Exercise $exercise, ExerciseSolutionFile $exerciseSolutionFile = NULL, Orm $orm, BaseFormFactory $baseFormFactory)
	{
		$this->exercise = $exercise;
		$this->exerciseSolutionFile = $exerciseSolutionFile;
		$this->orm = $orm;
		$this->baseFormFactory = $baseFormFactory;
	}



	public function getForm()
	{

		$languages = [
			"js" => "JavaScript",
			"html" => "Html",
			"css" => "css",
			"jsx" => "jsx",
			"sass" => "Sass",
			"less" => "Less",
			"txt" => "txt",
			"php" => "PHP",
		];

		$form = $this->baseFormFactory->create();
		$form->addText("name", "Název souboru")
			 ->setRequired();
		$form->addSelect("language", "Jazyk", $languages)
			 ->setRequired("Vyberte jazyk");
		$form->addTextArea("code", "Kód")
			 ->setAttribute("rows", 10);

		if ($this->exerciseSolutionFile) {
			$form->setDefaults([
				"name" => $this->exerciseSolutionFile->getFilename(),
				"code" => $this->exerciseSolutionFile->getCode(),
				"language" => $this->exerciseSolutionFile->getLanguage(),
			]);
		}

		$form->addSubmit("send", $this->exerciseSolutionFile ? "Upravit" : "Přidat");

		$form->onSuccess[] = [$this, 'processForm'];

		return $form;
	}



	public function processForm(BaseForm $form)
	{
		$values = $form->getValues();

		$exerciseSolutionFile = $this->exerciseSolutionFile ?? new ExerciseSolutionFile();

		$exerciseSolutionFile->setFilename($values->name);
		$exerciseSolutionFile->setLanguage($values->language);
		$exerciseSolutionFile->setExercise($this->exercise);
		$exerciseSolutionFile->setCode($values->code);

		$this->orm->persistAndFlush($exerciseSolutionFile);

		$this->onSuccess();
	}
}
