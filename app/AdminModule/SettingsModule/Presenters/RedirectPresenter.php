<?php


namespace App\AdminModule\SettingsModule\Presenters;


use App\AdminModule\Presenters\SecuredPresenter;
use App\AdminModule\SettingsModule\Modal\SetRedirectModalFactory;
use App\Model\Router\Redirect\Redirect;
use App\Model\Router\Redirect\RedirectRepository;
use Nette\Application\BadRequestException;

class RedirectPresenter extends SecuredPresenter
{

	/** @var RedirectRepository @autowire */
	protected $redirectRepository;

	/** @var SetRedirectModalFactory @autowire */
	protected $setRedirectModalFactory;


	/** @var Redirect */
	protected $selectedRedirect;



	public function renderDefault()
	{
		$this->template->redirects = $this->redirectRepository->findRedirects();
	}



	public function actionAdd()
	{
		$modal = $this->setRedirectModalFactory->create();
		$this->raiseModal($modal, "addRedirect", "default");
	}


	public function actionEdit($id)
	{
		if (!$id or !$this->selectedRedirect = $this->redirectRepository->getRedirectById($id)) {
			throw new BadRequestException();
		}
		$modal = $this->setRedirectModalFactory->create($this->selectedRedirect);
		$this->raiseModal($modal, "editRedirect", "default");
	}
}
