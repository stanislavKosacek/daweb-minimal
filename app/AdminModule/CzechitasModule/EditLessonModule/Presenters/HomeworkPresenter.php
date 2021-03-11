<?php


namespace App\AdminModule\CzechitasModule\EditLessonModule\Presenters;


use App\AdminModule\CzechitasModule\EditLessonModule\Modal\AddHomeworkAssignmentModalFactory;
use App\AdminModule\CzechitasModule\EditLessonModule\Modal\EditHomeworkAssignmentModalFactory;
use App\AdminModule\Modal\AddPageBlockModalFactory;
use App\AdminModule\Presenters\SecuredPresenter;
use App\Model\Czechitas\HomeworkAssignment\HomeworkAssignment;
use App\Model\Czechitas\HomeworkAssignment\HomeworkAssignmentRepository;
use App\Model\Czechitas\Lesson\Lesson;
use App\Model\Czechitas\Lesson\LessonRepository;
use App\Model\Helpers\Page\EditPageBlocksComponentFactory;
use App\Model\Router\Redirect\RedirectRepository;
use Nette\Application\BadRequestException;

class HomeworkPresenter extends SecuredPresenter
{

	/** @var LessonRepository @autowire */
	protected $lessonRepository;

	/** @var Lesson */
	protected $selectedLesson;

	/** @var HomeworkAssignmentRepository @autowire */
	protected $homeworkAssignmentRepository;

	/** @var HomeworkAssignment */
	protected $selectedHomeworkAssignment;

	/** @var AddHomeworkAssignmentModalFactory @autowire */
	protected $addHomeworkAssignmentModalFactory;

	/** @var EditHomeworkAssignmentModalFactory @autowire */
	protected $editHomeworkAssignmentModalFactory;

	/** @var RedirectRepository @autowire */
	protected $redirectRepository;

	/** @var EditPageBlocksComponentFactory @autowire */
	protected $editPageBlocksComponentFactory;

	/** @var AddPageBlockModalFactory @autowire */
	protected $addPageBlockModalFactory;



	public function actionDefault($id)
	{
		if (!$id or !$this->selectedLesson = $this->lessonRepository->getLessonById($id)) {
			throw new BadRequestException();
		}

		$this->template->selectedLesson = $this->selectedLesson;
	}



	public function actionAddHomework($id)
	{
		if (!$id or !$this->selectedLesson = $this->lessonRepository->getLessonById($id)) {
			throw new BadRequestException();
		}

		$modal = $this->addHomeworkAssignmentModalFactory->create($this->selectedLesson);
		$this->actionDefault($id);

		$this->raiseModal($modal, "addHomework", "default", [$id], "default");
	}



	public function actionEditHomework($id, $homeworkId, $view = "default")
	{

		if (
			!$id or
			!$homeworkId or
			!$this->selectedLesson = $this->lessonRepository->getLessonById($id) or
			!$this->selectedHomeworkAssignment = $this->homeworkAssignmentRepository->getHomeworkAssignmentById($homeworkId)
		) {
			throw new BadRequestException();
		}

		$modal = $this->editHomeworkAssignmentModalFactory->create($this->selectedLesson, $this->selectedHomeworkAssignment);
		if ($view == "default") {
			$this->actionDefault($id);
			$this->raiseModal($modal, "editHomework", "default", [$id], $view);
		} elseif ($view == "detail") {
			$this->actionDetail($id, $homeworkId);
			$this->raiseModal($modal, "editHomework", "detail", [$id, $homeworkId], $view);
		}

	}



	public function actionDetail($id, $homeworkId)
	{
		if (
			!$id or
			!$homeworkId or
			!$this->selectedLesson = $this->lessonRepository->getLessonById($id) or
			!$this->selectedHomeworkAssignment = $this->homeworkAssignmentRepository->getHomeworkAssignmentById($homeworkId)
		) {
			throw new BadRequestException();
		}

		$this->template->selectedLesson = $this->selectedLesson;
		$this->template->selectedHomeworkAssignment = $this->selectedHomeworkAssignment;

	}



	public function actionAddPageBlock($id, $homeworkId)
	{
		if (
			!$id or
			!$homeworkId or
			!$this->selectedLesson = $this->lessonRepository->getLessonById($id) or
			!$this->selectedHomeworkAssignment = $this->homeworkAssignmentRepository->getHomeworkAssignmentById($homeworkId)
		) {
			throw new BadRequestException();
		}

		$modal = $this->addPageBlockModalFactory->create($this->selectedHomeworkAssignment->getPage());

		$this->actionDetail($id, $homeworkId);

		$this->raiseModal($modal, "addPageBlock", "detail", [$id, $homeworkId], "detail");

	}



	public function createComponentEditPageBlocks()
	{
		$control = $this->editPageBlocksComponentFactory->create($this->selectedHomeworkAssignment->getPage());

		return $control;
	}



	public function handleDeleteHomework($homeworkId)
	{
		$homeworkAssignment = $this->homeworkAssignmentRepository->getHomeworkAssignmentById($homeworkId);
		$page = $homeworkAssignment->getPage();

		if ($homeworkAssignment->getHomeworkSolutions()->countStored() == 0) {
			$url = $homeworkAssignment->getPage()->getTarget() ? $homeworkAssignment->getPage()->getTarget()->getSlug() : NULL;
			if ($url) {
				$redirect = $this->redirectRepository->getRedirectByTo($url);
				if ($redirect) {
					$this->orm->removeAndFlush($redirect);
				}
			}

			$this->orm->remove($homeworkAssignment);

			foreach ($page->getPageBlocks() as $block) {
				$this->orm->removeAndFlush($block);
			}

			$target = $page->getTarget();
			$this->orm->removeAndFlush($page);

			$this->orm->removeAndFlush($target);



			$this->flashMessage("Úspěšně smazáno", "success");
		} else {
			$this->flashMessage("Není možné smazat úkol, který má již odevzdaná řešení", "error");
		}

		$this->redirect("this");

	}
}
