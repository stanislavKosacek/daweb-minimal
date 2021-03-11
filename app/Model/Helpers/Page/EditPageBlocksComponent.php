<?php


namespace App\Model\Helpers\Page;


use App\Model\Orm;
use App\Model\Page\Page\Page;
use App\Model\Page\PageBlock\PageBlockRepository;
use Nette\Application\BadRequestException;
use Nette\Application\UI\Control;
use Nette\Application\UI\Multiplier;

interface EditPageBlocksComponentFactory
{

	public function create(Page $page): EditPageBlocksComponent;

}



class EditPageBlocksComponent extends Control
{

	/** @var Page */
	private $page;

	/** @var Orm */
	private $orm;

	/** @var PageBlockRepository */
	private $pageBlockRepository;



	public function __construct(Page $page, Orm $orm, PageBlockRepository $pageBlockRepository)
	{
		$this->page = $page;
		$this->orm = $orm;
		$this->pageBlockRepository = $pageBlockRepository;
	}



	public function render()
	{
		$this->template->setFile(__DIR__ . "/EditPageBlocksComponent.latte");
		$this->template->selectedPage = $this->page;
		$this->template->render();
	}



	public function handleMoveBlockUp($blockId)
	{
		if (
			!$blockId or
			!$selectedPageBlock = $this->pageBlockRepository->getPageBlockById($blockId)
		) {
			throw new BadRequestException();
		}

		$blockWithLowerPriority = $this->page
			->pageBlocks
			->toCollection()
			->resetOrderBy()
			->orderBy("priority", "DESC")
			->getBy(["priority<" => $selectedPageBlock->getPriority()]);

		if ($blockWithLowerPriority) {
			$this->switchEntities($selectedPageBlock, $blockWithLowerPriority);
			$this->presenter->flashMessage("Blok byl posunut", "success");
		} else {
			$this->presenter->flashMessage("Posunutí bloku selhalo", "error");
		}

		if ($this->presenter->isAjax()) {
			$this->getPresenter()->redrawControl(NULL);
			$this->redrawControl("pageBlocks");
		} else {
			$this->redirect("this");
		}

	}



	public function handleMoveBlockDown($blockId)
	{
		if (
			!$blockId or
			!$selectedPageBlock = $this->pageBlockRepository->getPageBlockById($blockId)
		) {
			throw new BadRequestException();
		}

		$blockWithHigherPriority = $this->page
			->pageBlocks
			->toCollection()
			->resetOrderBy()
			->orderBy("priority", "ASC")
			->getBy(["priority>" => $selectedPageBlock->getPriority()]);

		if ($blockWithHigherPriority) {
			$this->switchEntities($selectedPageBlock, $blockWithHigherPriority);
			$this->presenter->flashMessage("Blok byl posunut", "success");
		} else {
			$this->presenter->flashMessage("Posunutí bloku selhalo", "error");
		}

		if ($this->presenter->isAjax()) {
			$this->getPresenter()->redrawControl(NULL);
			$this->redrawControl("pageBlocks");
		} else {
			$this->redirect("this");
		}

	}



	public function handleDeleteBlock($blockId)
	{
		if (
			!$blockId or
			!$selectedPageBlock = $this->pageBlockRepository->getPageBlockById($blockId)
		) {
			throw new BadRequestException();
		}


		$this->orm->removeAndFlush($selectedPageBlock);

		if ($this->presenter->isAjax()) {
			$this->getPresenter()->redrawControl(NULL);
			$this->redrawControl("pageBlocks");
		} else {
			$this->redirect("this");
		}

	}



	private function switchEntities($entity1, $entity2)
	{
		$x = $entity1->priority;
		$entity1->priority = $entity2->priority;
		$entity2->priority = $x;

		$this->orm->persist($entity1);
		$this->orm->persist($entity2);
		$this->orm->flush();
	}



	public function createComponentAdminBlockComponent()
	{
		return new Multiplier(function ($pageBlockId) {

			$pageBlock = $this->pageBlockRepository->getPageBlockById($pageBlockId);

			return $pageBlock->getAdminComponent();

		});
	}


}
