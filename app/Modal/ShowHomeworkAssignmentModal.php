<?php


namespace App\Modal;



use App\Components\Homework\ChangeHomeworkStateFactory;
use App\Model\Czechitas\HomeworkAssignment\HomeworkAssignment;
use App\Model\Czechitas\HomeworkSolution\HomeworkSolutionRepository;
use App\Model\Czechitas\Lesson\Lesson;
use App\Model\Helpers\Comments\CommentProviderFactory;
use App\Model\Modal\BaseModal;
use App\Model\Page\PageBlock\PageBlockRepository;
use Nette\Application\UI\Multiplier;

interface ShowHomeworkAssignmentModalFactory
{

	public function create(HomeworkAssignment $homeworkAssignment): ShowHomeworkAssignmentModal;
}



class ShowHomeworkAssignmentModal extends BaseModal
{


	/** @var HomeworkAssignment */
	private $homeworkAssignment;

	/** @var PageBlockRepository */
	private $pageBlockRepository;

	/** @var ChangeHomeworkStateFactory */
	private $changeHomeworkStateFactory;

	/** @var HomeworkSolutionRepository */
	private $homeworkSolutionRepository;

	/** @var CommentProviderFactory */
	private $commentProviderFactory;



	public function __construct(HomeworkAssignment $homeworkAssignment,
		PageBlockRepository $pageBlockRepository,
		ChangeHomeworkStateFactory $changeHomeworkStateFactory,
		HomeworkSolutionRepository $homeworkSolutionRepository,
		CommentProviderFactory $commentProviderFactory)
	{
		$this->homeworkAssignment = $homeworkAssignment;
		$this->pageBlockRepository = $pageBlockRepository;
		$this->changeHomeworkStateFactory = $changeHomeworkStateFactory;
		$this->homeworkSolutionRepository = $homeworkSolutionRepository;
		$this->commentProviderFactory = $commentProviderFactory;
	}



	public function render()
	{
		$this->template->setFile(__DIR__ . "/ShowHomeworkAssignmentModal.latte");
		$this->template->homeworkAssignment = $this->homeworkAssignment;
		$this->template->homeworkSolution = $this->homeworkSolutionRepository->getByUserAndHomeworkAssignment($this->presenter->getUser()->getIdentity(), $this->homeworkAssignment);
		$this->template->render();
	}



	public function createComponentFrontendBlockComponent()
	{
		return new Multiplier(function ($pageBlockId) {

			$pageBlock = $this->pageBlockRepository->getPageBlockById($pageBlockId);

			return $pageBlock->getFrontendComponent();

		});
	}



	public function createComponentHomeworkState()
	{
		$control = $this->changeHomeworkStateFactory->create($this->homeworkAssignment);
		return $control;
	}


	public function createComponentComments()
	{
		$homeworkSolution = $this->homeworkSolutionRepository->getByUserAndHomeworkAssignment($this->presenter->getUser()->getIdentity(), $this->homeworkAssignment);
		if ($homeworkSolution) {
			return $this->commentProviderFactory->create($homeworkSolution)->getFrontendComponent();
		}
	}
}
