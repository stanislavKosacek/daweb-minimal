<?php

declare(strict_types=1);

namespace App\Presenters;



use App\Components\Exercise\ExerciseComponentFactory;
use App\Modal\ShowHomeworkAssignmentModalFactory;
use App\Model\Czechitas\Exercise\ExerciseRepository;
use App\Model\Czechitas\HomeworkAssignment\HomeworkAssignment;
use App\Model\Czechitas\HomeworkAssignment\HomeworkAssignmentRepository;
use App\Model\Helpers\Comments\CommentProviderFactory;
use App\Model\Page\Page\Page;
use App\Model\Page\Page\PageRepository;
use App\Model\Page\PageBlock\PageBlockRepository;
use Nette\Application\BadRequestException;
use Nette\Application\UI\Multiplier;

final class PagePresenter extends BasePresenter
{


	/** @var PageRepository @autowire */
	protected $pageRepository;

	/** @var Page */
	protected $selectedPage;

	/** @var PageBlockRepository @autowire */
	protected $pageBlockRepository;

	/** @var CommentProviderFactory @autowire */
	protected $commentProviderFactory;

	/** @var HomeworkAssignmentRepository @autowire */
	protected $homeworkAssignmentRepository;

	/** @var HomeworkAssignment */
	protected $selectedHomeworkAssignment;

	/** @var ShowHomeworkAssignmentModalFactory @autowire */
	protected $showHomeworkAssignmentModalFactory;

	/** @var ExerciseRepository @autowire */
	protected $exerciseRepository;

	/** @var ExerciseComponentFactory @autowire */
	protected $exerciseComponentFactory;



	public function actionDefault($id)
	{
		$id = (int)$id;
		if (!$id or !$this->selectedPage = $this->pageRepository->getPageById($id)) {
			throw new BadRequestException();
		}

		if (!$this->selectedPage->isPublished()) {
			$this->setView("notPublished");
		}

		if ($this->selectedPage->isPublished() and $this->selectedPage->getLesson()) {
			$this->template->lesson = $this->selectedPage->getLesson();
			$this->setView("lesson");
		}

		if ($this->selectedPage->isPublished() and $this->selectedPage->getType() == Page::TYPE_HOMEWORK) {
			$this->selectedHomeworkAssignment =$this->selectedPage->getHomeworkAssignment();
			$this->actionDefault($this->selectedHomeworkAssignment->getLesson()->getPage()->getId());
			$modal = $this->showHomeworkAssignmentModalFactory->create($this->selectedHomeworkAssignment);

			$modal->addClass("modal-xl");

			$this->raiseModal($modal, "showHomework", "default", [$this->selectedHomeworkAssignment->getLesson()->getPage()->getId()], "lesson");
		}

		$this->template->selectedPage = $this->selectedPage;
	}



	public function createComponentFrontendBlockComponent()
	{
		return new Multiplier(function ($pageBlockId) {

			$pageBlock = $this->pageBlockRepository->getPageBlockById($pageBlockId);

			return $pageBlock->getFrontendComponent();

		});
	}



	public function createComponentComments()
	{
		if ($this->selectedPage) {
			return $this->commentProviderFactory->create($this->selectedPage)->getFrontendComponent();
		}
	}



	public function createComponentExercise()
	{
		return new Multiplier(function ($exerciseId) {
			$exercise = $this->exerciseRepository->getExerciseById($exerciseId);
			return $this->exerciseComponentFactory->create($exercise);
		});
	}
}
