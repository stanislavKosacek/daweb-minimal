<?php


namespace App\AdminModule\CzechitasModule\Presenters;


use App\AdminModule\CzechitasModule\Grid\LessonGridFactory;
use App\AdminModule\CzechitasModule\Modal\Lesson\AddLessonModalFactory;
use App\AdminModule\CzechitasModule\Modal\Lesson\EditLessonModalFactory;
use App\AdminModule\Presenters\SecuredPresenter;
use App\Model\Czechitas\Lesson\Lesson;
use App\Model\Czechitas\Lesson\LessonRepository;
use Nette\Application\BadRequestException;

class LessonPresenter extends SecuredPresenter
{

	/** @var LessonGridFactory @autowire */
	protected $lessonGridFactory;

	/** @var LessonRepository @autowire */
	protected $lessonRepository;

	/** @var Lesson */
	protected $selectedLesson;


	/** @var AddLessonModalFactory @autowire */
	protected $addLessonModalFactory;

	/** @var EditLessonModalFactory @autowire */
	protected $editLessonModalFactory;


	public function renderDefault()
	{

	}



	public function actionAdd()
	{
		$modal = $this->addLessonModalFactory->create();
		$this->raiseModal($modal, "addLesson", "default");
	}



	public function actionEditLesson($id)
	{
		if (!$id or !$this->selectedLesson = $this->lessonRepository->getLessonById($id)) {
			throw new BadRequestException();
		}

		$modal = $this->editLessonModalFactory->create($this->selectedLesson);
		$this->raiseModal($modal, "editLesson", "default");

	}


	public function createComponentLessonGrid()
	{
		$grid = $this->lessonGridFactory->create();

		return $grid->getGrid();
	}

}
