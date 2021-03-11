<?php

namespace App\AdminModule\CzechitasModule\Components\Exercise;

use App\Model\Czechitas\CodeShare\CodeShare;
use App\Model\Czechitas\CodeShare\CodeShareRepository;
use App\Model\Czechitas\Exercise\Exercise;
use App\Model\Czechitas\Exercise\ExerciseRepository;
use App\Model\Czechitas\Lesson\LessonRepository;
use App\Model\Form\BaseForm;
use App\Model\Form\BaseFormFactory;
use App\Model\Orm;
use App\Model\Page\PageBlock\Types\CodeSharePageBlock;
use App\Model\Page\PageBlock\Types\ExercisePageBlock;
use Nette\SmartObject;
use Nette\Utils\Json;
use Nette\Utils\Random;

interface SetExerciseFormFactory
{

	function create(?Exercise $exercise = NULL, ?ExercisePageBlock $exercisePageBlock = NULL): SetExerciseForm;
}



class SetExerciseForm
{

	use SmartObject;

	/** @var Exercise|null */
	private $exercise;

	/** @var ExercisePageBlock|null */
	private $exercisePageBlock;

	/** @var Orm */
	private $orm;

	/** @var ExerciseRepository */
	private $exerciseRepository;

	/** @var BaseFormFactory */
	private $baseFormFactory;

	/** @var array */
	public $onSuccess = [];

	/** @var LessonRepository */
	private LessonRepository $lessonRepository;



	public function __construct(?Exercise $exercise = NULL, ?ExercisePageBlock $exercisePageBlock = NULL, Orm $orm, ExerciseRepository $exerciseRepository, BaseFormFactory $baseFormFactory, LessonRepository $lessonRepository)
	{
		$this->exercise = $exercise;
		$this->exercisePageBlock = $exercisePageBlock;
		$this->orm = $orm;
		$this->exerciseRepository = $exerciseRepository;
		$this->baseFormFactory = $baseFormFactory;
		$this->lessonRepository = $lessonRepository;
	}



	public function getForm()
	{
		$form = $this->baseFormFactory->create();
		$form->addText("name", "Název")
			 ->setRequired();
		$form->addTextArea("assignment", "Zadání")
			 ->setHtmlAttribute("class", "markdown")
			 ->setHtmlId("markdown" . Random::generate(9));

		if (!$this->exercisePageBlock) {
			$form->addSelect("lesson", "Lekce", $this->lessonRepository->findAll()->fetchPairs("id", "page->name"))
				 ->setPrompt("------");
		}

		if ($this->exercise) {
			$form->addInteger("orderInLesson", "Pořadí v lekci")
				 ->setDefaultValue($this->exercise->getOrderInLesson())
				 ->setRequired("Zadejte pořadí v lekci");
		}
		$form->addRadioList("published", "Publikováno", [0 => "Ne", 1 => "Ano"])->setRequired()->setDefaultValue(0);
		$form->addRadioList("publishedSolution", "Publikováno řešení", [0 => "Ne", 1 => "Ano"])->setRequired()->setDefaultValue(0);

		if ($this->exercise) {
			$defaults = [
				"name" => $this->exercise->getName(),
				"assignment" => $this->exercise->getAssignment(),
				"published" => $this->exercise->isPublished() ? 1 : 0,
				"publishedSolution" => $this->exercise->isPublishedSolution() ? 1 : 0,
			];

			if (!$this->exercisePageBlock) {
				$defaults["lesson"] = $this->exercise->getLesson() ? $this->exercise->getLesson()->getId() : NULL;
			}

			$form->setDefaults($defaults);
		}

		$form->addSubmit("send", $this->exercise ? "Upravit" : "Přidat");

		$form->onSuccess[] = [$this, 'processForm'];

		return $form;
	}



	public function processForm(BaseForm $form)
	{
		$values = $form->getValues();


		$exercise = $this->exercise ?? new Exercise();
		$exercise->setName($values->name);
		$exercise->setAssignment($values->assignment);

		if ($this->exercise) {
			$exercise->setOrderInLesson($values->orderInLesson);
		}
		if ($this->exercisePageBlock) {
			$exercise->setLesson($this->exercisePageBlock->getPage()->getLesson());
		} else {
			if ($values->lesson) {
				$lesson = $this->lessonRepository->getById($values->lesson);
				$exercise->setLesson($lesson);
				if (!$this->exercise) {
					$exercise->setOrderInLesson($lesson->getExercises()->countStored());
				}
			} else {
				$exercise->setLesson(NULL);
				$exercise->setOrderInLesson(NULL);
			}
		}

		$exercise->setPublished($values->published ? TRUE : FALSE);
		$exercise->setPublishedSolution($values->publishedSolution ? TRUE : FALSE);

		$this->orm->persist($exercise);

		if ($this->exercisePageBlock) {
			$this->exercisePageBlock->data = ["exerciseId" => $exercise->getId()];
			$this->orm->persistAndFlush($this->exercisePageBlock);
		}

		$this->orm->persistAndFlush($exercise);

		$this->onSuccess();
	}


}
