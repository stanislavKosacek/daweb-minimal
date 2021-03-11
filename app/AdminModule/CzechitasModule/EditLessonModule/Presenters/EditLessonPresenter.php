<?php


namespace App\AdminModule\CzechitasModule\EditLessonModule\Presenters;


use App\AdminModule\CzechitasModule\EditLessonModule\Modal\SetLessonTeamModalFactory;
use App\AdminModule\CzechitasModule\Modal\Lesson\EditLessonModalFactory;
use App\AdminModule\Modal\AddPageBlockModalFactory;
use App\AdminModule\Presenters\SecuredPresenter;
use App\AdminModule\SettingsModule\Components\SetTargetFormFactory;
use App\Model\Czechitas\Lesson\Lesson;
use App\Model\Czechitas\Lesson\LessonRepository;
use App\Model\Czechitas\LessonTeamRole\LessonTeamRoleRepository;
use App\Model\Helpers\Page\EditPageBlocksComponentFactory;
use App\Model\Page\PageBlock\PageBlockRepository;
use Nette\Application\BadRequestException;
use Nette\Application\UI\Multiplier;
use Nextras\Dbal\Utils\DateTimeImmutable;

class EditLessonPresenter extends SecuredPresenter
{

	/** @var LessonRepository @autowire */
	protected $lessonRepository;

	/** @var Lesson */
	protected $selectedLesson;

	/** @var AddPageBlockModalFactory @autowire */
	protected $addPageBlockModalFactory;


	/** @var PageBlockRepository @autowire */
	protected $pageBlockRepository;

	/** @var SetTargetFormFactory @autowire */
	protected $setTargetFormFactory;

	/** @var EditLessonModalFactory @autowire */
	protected $editLessonModalFactory;

	/** @var SetLessonTeamModalFactory @autowire */
	protected $setLessonTeamModalFactory;

	/** @var LessonTeamRoleRepository @autowire */
	protected $lessonTeamRoleRepository;

	/** @var EditPageBlocksComponentFactory @autowire */
	protected $editPageBlocksComponentFactory;



	public function actionDefault($id)
	{
		if (!$id or !$this->selectedLesson = $this->lessonRepository->getLessonById($id)) {
			throw new BadRequestException();
		}

		$this->template->selectedLesson = $this->selectedLesson;
	}



	public function actionEditLesson($id)
	{
		if (!$id or !$this->selectedLesson = $this->lessonRepository->getLessonById($id)) {
			throw new BadRequestException();
		}

		$modal = $this->editLessonModalFactory->create($this->selectedLesson);
		$this->actionDefault($id);

		$this->raiseModal($modal, "editLesson", "default", [$id], "default");

	}



	public function actionEditTeam($id)
	{
		if (!$id or !$this->selectedLesson = $this->lessonRepository->getLessonById($id)) {
			throw new BadRequestException();
		}

		$modal = $this->setLessonTeamModalFactory->create($this->selectedLesson);
		$this->actionDefault($id);

		$this->raiseModal($modal, "editTeam", "default", [$id], "default");
	}



	public function actionAddPageBlock($id)
	{
		if (!$id or !$this->selectedLesson = $this->lessonRepository->getLessonById($id)) {
			throw new BadRequestException();
		}

		$modal = $this->addPageBlockModalFactory->create($this->selectedLesson->getPage());

		$this->actionDefault($id);

		$this->raiseModal($modal, "addPageBlock", "default", [$id], "default");

	}



	public function createComponentEditPageBlocks()
	{
		$control = $this->editPageBlocksComponentFactory->create($this->selectedLesson->getPage());

		return $control;
	}



	public function createComponentAdminBlockComponent()
	{
		return new Multiplier(function ($pageBlockId) {

			$pageBlock = $this->pageBlockRepository->getPageBlockById($pageBlockId);

			return $pageBlock->getAdminComponent();

		});
	}



	public function createComponentEditTargetForm()
	{
		$control = $this->setTargetFormFactory->create($this->selectedLesson->getPage()->getTarget(), $this->selectedLesson->getPage());
		$control->onSuccess[] = function () {
			$this->flashMessage("Target byl upraven", "success");
			$this->redirect("this");
		};

		$form = $control->getForm();
		$form->setAjax(TRUE);

		return $form;
	}



	public function handleTogglePublishedState()
	{
		if ($this->selectedLesson->getPage()->isPublished()) {
			$this->selectedLesson->getPage()->setPublished(NULL);
		} else {
			$this->selectedLesson->getPage()->setPublished(new DateTimeImmutable());
		}
		$this->orm->persistAndFlush($this->selectedLesson->getPage());
		$this->redirect("this");
	}



	public function handleRemoveLessonTeamRole($roleId)
	{
		$role = $this->lessonTeamRoleRepository->getLessonTeamRoleById($roleId);

		$this->orm->removeAndFlush($role);

		$this->redirect("this");
	}
}
