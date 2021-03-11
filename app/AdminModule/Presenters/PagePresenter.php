<?php


namespace App\AdminModule\Presenters;



use App\AdminModule\Grid\PageGridFactory;
use App\AdminModule\Modal\AddPageBlockModalFactory;
use App\AdminModule\Modal\AddPageModalFactory;
use App\AdminModule\Modal\EditPageModalFactory;
use App\AdminModule\Modal\ShowCommentsModalFactory;
use App\AdminModule\SettingsModule\Components\SetTargetFormFactory;
use App\Model\Helpers\Comments\CommentProviderFactory;
use App\Model\Helpers\Page\EditPageBlocksComponentFactory;
use App\Model\Page\Page\Page;
use App\Model\Page\Page\PageRepository;
use Nette\Application\BadRequestException;
use Nextras\Dbal\Utils\DateTimeImmutable;

class PagePresenter extends SecuredPresenter
{

	/** @var PageRepository @autowire */
	protected $pageRepository;


	/** @var PageGridFactory @autowire */
	protected $pageGridFactory;

	/** @var AddPageModalFactory @autowire */
	protected $addPageModalFactory;

	/** @var Page */
	protected $selectedPage;

	/** @var AddPageBlockModalFactory @autowire */
	protected $addPageBlockModalFactory;

	/** @var SetTargetFormFactory @autowire */
	protected $setTargetFormFactory;

	/** @var ShowCommentsModalFactory @autowire */
	protected $showCommentsModalFactory;

	/** @var CommentProviderFactory @autowire */
	protected $commentProviderFactory;

	/** @var EditPageModalFactory @autowire */
	protected $editPageModalFactory;

	/** @var EditPageBlocksComponentFactory @autowire */
	protected $editPageBlocksComponentFactory;



	public function renderDefault()
	{

	}



	public function actionAddPage()
	{
		$modal = $this->addPageModalFactory->create();
		$this->raiseModal($modal, "addPage", "default");
	}



	public function actionEditPage($id, $from = "default")
	{
		if (!$id or !$this->selectedPage = $this->pageRepository->getPageById($id)) {
			throw new BadRequestException();
		}

		$modal = $this->editPageModalFactory->create($this->selectedPage);

		if ($from == "detail") {
			$this->actionDetail($id);
			$this->raiseModal($modal, "editPage", "detail", ["id" => $id], "detail");
		} else {
			$this->raiseModal($modal, "editPage", "default");
		}

	}



	public function actionDetail($id)
	{
		if (!$id or !$this->selectedPage = $this->pageRepository->getPageById($id)) {
			throw new BadRequestException();
		}

		$this->template->selectedPage = $this->selectedPage;
	}



	public function actionAddPageBlock($id)
	{
		if (!$id or !$this->selectedPage = $this->pageRepository->getPageById($id)) {
			throw new BadRequestException();
		}

		$modal = $this->addPageBlockModalFactory->create($this->selectedPage);

		$this->actionDetail($id);

		$this->raiseModal($modal, "addPageBlock", "detail", [$id], "detail");

	}



	public function actionShowComments($id)
	{
		if (!$id or !$this->selectedPage = $this->pageRepository->getPageById($id)) {
			throw new BadRequestException();
		}

		$modal = $this->showCommentsModalFactory->create($this->selectedPage);
		$modal->addClass("modal-xl");

		$this->actionDetail($id);

		$this->raiseModal($modal, "showComments", "detail", [$id], "detail");

	}



	public function createComponentEditPageBlocks()
	{
		$control = $this->editPageBlocksComponentFactory->create($this->selectedPage);

		return $control;
	}



	public function createComponentPageGrid()
	{
		$grid = $this->pageGridFactory->create();

		return $grid->getGrid();
	}



	public function createComponentEditTargetForm()
	{
		$control = $this->setTargetFormFactory->create($this->selectedPage->getTarget(), $this->selectedPage);
		$control->onSuccess[] = function () {
			$this->flashMessage("Target byl upraven", "success");
			$this->redirect("this");
		};

		$form = $control->getForm();
		$form->setAjax(TRUE);

		return $form;
	}



	public function createComponentComments()
	{
		return $this->commentProviderFactory->create($this->selectedPage)->getAdminComponent();
	}



	public function handleTogglePublishedState()
	{
		if ($this->selectedPage->isPublished()) {
			$this->selectedPage->setPublished(NULL);
		} else {
			$this->selectedPage->setPublished(new DateTimeImmutable());
		}
		$this->orm->persistAndFlush($this->selectedPage);
		$this->redirect("this");
	}
}
