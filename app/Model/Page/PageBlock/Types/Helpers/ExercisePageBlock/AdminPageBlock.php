<?php


namespace App\Model\Page\PageBlock\Types\Helpers\Exercise;

use App\AdminModule\CzechitasModule\Components\CodeShare\SetExerciseFileFormFactory;
use App\AdminModule\CzechitasModule\Components\Exercise\SetExerciseFormFactory;
use App\Model\Czechitas\ExerciseSolutionFile\ExerciseSolutionFileRepository;
use App\Model\Page\PageBlock\Types\ExercisePageBlock;
use Nette\Application\UI\Control;
use Nette\Application\UI\Multiplier;

interface AdminPageBlockFactory
{

	public function create(ExercisePageBlock $exercisePageBlock): AdminPageBlock;

}



class AdminPageBlock extends Control
{


	/** @var ExercisePageBlock */
	private ExercisePageBlock $exercisePageBlock;

	/** @var SetExerciseFormFactory */
	private SetExerciseFormFactory $setExerciseFormFactory;

	/** @var SetExerciseFileFormFactory */
	private SetExerciseFileFormFactory $setExerciseFileFormFactory;

	/** @var ExerciseSolutionFileRepository */
	private ExerciseSolutionFileRepository $exerciseSolutionFileRepository;



	public function __construct(ExercisePageBlock $exercisePageBlock, SetExerciseFormFactory $setExerciseFormFactory, SetExerciseFileFormFactory $setExerciseFileFormFactory, ExerciseSolutionFileRepository $exerciseSolutionFileRepository)
	{
		$this->exercisePageBlock = $exercisePageBlock;
		$this->setExerciseFormFactory = $setExerciseFormFactory;
		$this->setExerciseFileFormFactory = $setExerciseFileFormFactory;
		$this->exerciseSolutionFileRepository = $exerciseSolutionFileRepository;
	}



	public function render()
	{

		$this->template->setFile(__DIR__ . "/AdminPageBlock.latte");
		$this->template->pageBlock = $this->exercisePageBlock;
		$this->template->render();
	}



	protected function createComponentForm()
	{
		$form = $this->setExerciseFormFactory->create($this->exercisePageBlock->getExercise(), $this->exercisePageBlock);

		$form->onSuccess[] = function () {
			$this->redirect("this");
		};
		$control = $form->getForm();
		$control->setAjax(TRUE);

		return $control;
	}


	protected function createComponentSolutions()
	{
		$form = $this->setExerciseFormFactory->create($this->exercisePageBlock->getExercise(), $this->exercisePageBlock);

		$form->onSuccess[] = function () {
			$this->redirect("this");
		};
		$control = $form->getForm();
		$control->setAjax(TRUE);

		return $control;
	}



	public function createComponentNewExerciseFile()
	{
		$form = $this->setExerciseFileFormFactory->create($this->exercisePageBlock->getExercise(), NULL);

		$form->onSuccess[] = function () {
			$this->redirect("this");
		};
		$control = $form->getForm();
		$control->setAjax(TRUE);

		return $control;
	}


	public function createComponentEditExerciseFile()
	{
		return new Multiplier(function ($id) {

			$solution = $this->exerciseSolutionFileRepository->getExerciseSolutionFileById($id);
			$form = $this->setExerciseFileFormFactory->create($this->exercisePageBlock->getExercise(), $solution);

			$form->onSuccess[] = function () {
				$this->redirect("this");
			};
			$control = $form->getForm();
			$control->setAjax(TRUE);

			return $control;
		});

	}

}
