<?php


namespace App\AdminModule\CzechitasModule\Presenters;


use App\AdminModule\CzechitasModule\Grid\HomeworkAssignmentGridFactory;
use App\AdminModule\CzechitasModule\Grid\HomeworkSolutionGridFactory;
use App\AdminModule\Modal\ShowCommentsModalFactory;
use App\AdminModule\Presenters\SecuredPresenter;
use App\Model\Czechitas\HomeworkAssignment\HomeworkAssignment;
use App\Model\Czechitas\HomeworkAssignment\HomeworkAssignmentRepository;
use App\Model\Czechitas\HomeworkSolution\HomeworkSolution;
use App\Model\Czechitas\HomeworkSolution\HomeworkSolutionRepository;
use Nette\Application\BadRequestException;

class HomeworkRepairPresenter extends SecuredPresenter
{


	/** @var HomeworkAssignmentGridFactory @autowire */
	protected $homeworkAssignmentGridFactory;

	/** @var HomeworkAssignmentRepository @autowire */
	protected $homeworkAssignmentRepository;

	/** @var HomeworkAssignment|null */
	protected $selectedHomeworkAssignment;

	/** @var HomeworkSolutionGridFactory @autowire */
	protected $homeworkSolutionGridFactory;

	/** @var HomeworkSolutionRepository @autowire */
	protected $homeworkSolutionRepository;

	/** @var HomeworkSolution */
	protected $selectedHomeworkSolution;

	/** @var ShowCommentsModalFactory @autowire */
	protected $showCommentsModalFactory;



	public function renderDefault()
	{

	}



	public function actionDetail($id)
	{
		if (!$id or !$this->selectedHomeworkAssignment = $this->homeworkAssignmentRepository->getHomeworkAssignmentById($id)) {
			throw new BadRequestException();
		}

		$this->template->homeworkAssignment = $this->selectedHomeworkAssignment;
	}



	public function actionComments($id)
	{
		if (!$id or !$this->selectedHomeworkSolution = $this->homeworkSolutionRepository->getById($id)) {
			throw new BadRequestException();
		}

		$modal = $this->showCommentsModalFactory->create($this->selectedHomeworkSolution);
		$modal->addClass("modal-xl");

		$this->actionDetail($this->selectedHomeworkSolution->getHomeworkAssignment()->getId());

		$this->raiseModal($modal, "commentsModal", "detail", [$this->selectedHomeworkSolution->getHomeworkAssignment()->getId()], "detail");

	}



	public function createComponentHomeworkAssignmentGrid()
	{
		$grid = $this->homeworkAssignmentGridFactory->create();

		return $grid->getGrid();
	}



	public function createComponentHomeworkSolutionGrid()
	{
		$grid = $this->homeworkSolutionGridFactory->create($this->selectedHomeworkAssignment);

		return $grid->getGrid();
	}


}
