<?php


namespace App\AdminModule\SettingsModule\Modal;



use App\AdminModule\SettingsModule\Components\SetRedirectFormFactory;
use App\Model\Modal\BaseModal;
use App\Model\Router\Redirect\Redirect;
use App\Model\Router\Target\Target;

interface SetRedirectModalFactory
{

	public function create(?Redirect $redirect = NULL): SetRedirectModal;
}



class SetRedirectModal extends BaseModal
{

	/** @var SetRedirectFormFactory */
	private $factory;

	/** @var Redirect|null */
	private $redirect;



	public function __construct(?Redirect $redirect = NULL, SetRedirectFormFactory $factory)
	{
		$this->redirect = $redirect;
		$this->factory = $factory;
	}



	public function createComponentForm()
	{
		$control = $this->factory->create($this->redirect);
		$control->onSuccess[] = function () {
			$this->flashMessage($this->redirect ? "Redirect byl upraven" : "Redirect byl přidán", "success");
			$this->close();
		};

		return $control->getForm();
	}



	public function render()
	{
		$this->template->setFile(__DIR__ . "/SetRedirectModal.latte");
		$this->template->title = $this->redirect ? "Upravit redirect" : "Přidat redirect";
		$this->template->edit = $this->redirect ? TRUE : FALSE;
		$this->template->render();
	}
}
