<?php


namespace App\Components\Homework;


use App\Model\Czechitas\HomeworkAssignment\HomeworkAssignment;
use App\Model\Czechitas\HomeworkSolution\HomeworkSolution;
use App\Model\Czechitas\HomeworkSolution\HomeworkSolutionRepository;
use App\Model\Orm;
use Nette\Application\UI\Control;


interface ChangeHomeworkStateFactory
{

	public function create(HomeworkAssignment $homeworkAssignment): ChangeHomeworkState;

}



class ChangeHomeworkState extends Control
{

	/** @var HomeworkAssignment */
	private $homeworkAssignment;

	/** @var HomeworkSolutionRepository */
	private $homeworkSolutionRepository;

	/** @var Orm */
	private $orm;

	/** @var HomeworkSolution|null */
	private $homeworkSolution = NULL;



	public function __construct(HomeworkAssignment $homeworkAssignment, HomeworkSolutionRepository $homeworkSolutionRepository, Orm $orm)
	{
		$this->homeworkAssignment = $homeworkAssignment;
		$this->homeworkSolutionRepository = $homeworkSolutionRepository;
		$this->orm = $orm;
	}



	public function render()
	{
		$this->homeworkSolution = $this->homeworkSolutionRepository->getByUserAndHomeworkAssignment($this->presenter->getUser()->getIdentity(), $this->homeworkAssignment);

		if (!$this->homeworkSolution and !$this->presenter->getUser()->isInRole("admin")) {
			$homeworkSolution = new HomeworkSolution();
			$homeworkSolution->setUser($this->presenter->getUser()->getIdentity());
			$homeworkSolution->setHomeworkAssignment($this->homeworkAssignment);

			$this->homeworkSolution = $this->orm->persistAndFlush($homeworkSolution);
		}

		$this->template->setFile(__DIR__ . "/ChangeHomeworkState.latte");
		$this->template->homeworkAssignment = $this->homeworkAssignment;
		$this->template->userSolution = $this->homeworkSolution;
		$this->template->render();
	}



	public function handleMarkHomeworkAsSubmitted()
	{
		$userSolution = $this->homeworkSolutionRepository->getByUserAndHomeworkAssignment($this->presenter->getUser()->getIdentity(), $this->homeworkAssignment);
		$userSolution->setState(HomeworkSolution::STATE_WAITING);
		$this->orm->persistAndFlush($userSolution);

		$this->redirect("this");
	}



	public function handleRemoveSolution()
	{
		$userSolution = $this->homeworkSolutionRepository->getByUserAndHomeworkAssignment($this->presenter->getUser()->getIdentity(), $this->homeworkAssignment);
		if ($userSolution) {
			$userSolution->setState(HomeworkSolution::STATE_UNDELIVERED);
			$this->orm->persistAndFlush($userSolution);
		}

		$this->redirect("this");
	}

}
