<?php


namespace App\AdminModule\Presenters;



use App\AdminModule\CzechitasModule\Components\CodeShare\SetExerciseFileFormFactory;
use App\AdminModule\CzechitasModule\Components\Exercise\SetExerciseFormFactory;
use App\AdminModule\CzechitasModule\Modal\Exercise\SetExerciseModalFactory;
use App\AdminModule\Grid\ExerciseGridFactory;
use App\Model\Czechitas\Exercise\Exercise;
use App\Model\Czechitas\Exercise\ExerciseRepository;
use App\Model\Czechitas\ExerciseSolutionFile\ExerciseSolutionFileRepository;
use Nette\Application\BadRequestException;
use Nette\Application\UI\Multiplier;
use Nette\ComponentModel\IComponent;

class ExercisePresenter extends SecuredPresenter
{

	/** @var ExerciseRepository @autowire */
	protected $exerciseRepository;

	/** @var Exercise */
	protected $selectedExercise;


	/** @var ExerciseGridFactory @autowire */
	protected $exerciseGridFactory;

	/** @var SetExerciseModalFactory @autowire */
	protected $setExerciseModalFactory;

	/** @var SetExerciseFormFactory @autowire */
	protected $setExerciseFormFactory;

	/** @var ExerciseSolutionFileRepository @autowire */
	protected $exerciseSolutionFileRepository;

	/** @var SetExerciseFileFormFactory @autowire */
	protected $setExerciseFileFormFactory;



	public function renderDefault()
	{

	}



	public function actionDetail($id)
	{
		if (!$id or !$this->selectedExercise = $this->exerciseRepository->getExerciseById($id)) {
			throw new BadRequestException();
		}
		$this->template->exercise = $this->selectedExercise;
	}



	public function actionAdd()
	{
		$modal = $this->setExerciseModalFactory->create();
		$this->raiseModal($modal, "addExercise", "default");
	}



	public function actionEdit($id)
	{
		if (!$id or !$this->selectedExercise = $this->exerciseRepository->getExerciseById($id)) {
			throw new BadRequestException();
		}
		$modal = $this->setExerciseModalFactory->create($this->selectedExercise);
		$this->raiseModal($modal, "addExercise", "default");
	}



	protected function createComponentGrid()
	{
		return $this->exerciseGridFactory->create()->getGrid();
	}



	protected function createComponentEditExercise()
	{
		$control = $this->setExerciseFormFactory->create($this->selectedExercise);
		$control->onSuccess[] = function () {
			$this->redirect("this");
		};

		return $control->getForm();
	}



	public function createComponentNewExerciseFile()
	{
		$form = $this->setExerciseFileFormFactory->create($this->selectedExercise, NULL);

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
			$form = $this->setExerciseFileFormFactory->create($this->selectedExercise, $solution);

			$form->onSuccess[] = function () {
				$this->redirect("this");
			};
			$control = $form->getForm();
			$control->setAjax(TRUE);

			return $control;
		});

	}
}
