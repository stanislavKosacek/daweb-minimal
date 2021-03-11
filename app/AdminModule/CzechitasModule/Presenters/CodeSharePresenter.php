<?php


namespace App\AdminModule\CzechitasModule\Presenters;


use App\AdminModule\CzechitasModule\Grid\CodeShareGridFactory;
use App\AdminModule\CzechitasModule\Modal\CodeShare\SetCodeShareModalFactory;
use App\AdminModule\Presenters\SecuredPresenter;
use App\Model\Czechitas\CodeShare\CodeShare;
use Nette\Application\BadRequestException;

class CodeSharePresenter extends SecuredPresenter
{


	/** @var SetCodeShareModalFactory @autowire */
	protected $setCodeShareModalFactory;

	/** @var CodeShareGridFactory @autowire */
	protected $codeShareGridFactory;

	/** @var CodeShare */
	protected $selectedCodeShare;



	public function renderDefault()
	{

	}



	public function actionAdd()
	{
		$modal = $this->setCodeShareModalFactory->create();
		$modal->addClass("modal-lg");

		$this->raiseModal($modal, "addCodeshare", "default");
	}


	public function actionEdit($id)
	{
		if (!$id or !$this->selectedCodeShare = $this->codeShareRepository->getCodeShareById($id)) {
			throw new BadRequestException();
		}

		$modal = $this->setCodeShareModalFactory->create($this->selectedCodeShare);
		$modal->addClass("modal-lg");

		$this->raiseModal($modal, "addCodeshare", "default");
	}



	public function createComponentCodeShareGrid()
	{
		$grid = $this->codeShareGridFactory->create();

		return $grid->getGrid();
	}
}
