<?php


namespace App\AdminModule\SettingsModule\Presenters;


use App\AdminModule\Presenters\SecuredPresenter;
use App\AdminModule\SettingsModule\Modal\SetTargetModalFactory;
use App\Model\Router\Target\Target;
use App\Model\Router\Target\TargetRepository;
use Nette\Application\BadRequestException;

class UrlPresenter extends SecuredPresenter
{

	/** @var TargetRepository @autowire */
	protected $targetRepository;

	/** @var SetTargetModalFactory @autowire */
	protected $setTargetModalFactory;

	/** @var Target */
	protected $selectedTarget;



	public function renderDefault()
	{
		$this->template->targets = $this->targetRepository->findTargets();
	}



	public function actionAdd()
	{
		$modal = $this->setTargetModalFactory->create();
		$this->raiseModal($modal, "addTarget", "default");
	}


	public function actionEdit($id)
	{
		if (!$id or !$this->selectedTarget = $this->targetRepository->getTargetById($id)) {
			throw new BadRequestException();
		}
		$modal = $this->setTargetModalFactory->create($this->selectedTarget);
		$this->raiseModal($modal, "addTarget", "default");
	}
}
