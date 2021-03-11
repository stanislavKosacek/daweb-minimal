<?php


namespace App\AdminModule\SettingsModule\Modal;



use App\AdminModule\SettingsModule\Components\SetImageFormFactory;
use App\Model\Modal\BaseModal;
use App\Model\WebImage\WebImage;

interface SetImageModalFactory
{

	public function create(?WebImage $webImage = NULL): SetImageModal;
}



class SetImageModal extends BaseModal
{

	/** @var WebImage|null */
	private $webImage;

	/** @var SetImageFormFactory */
	private $factory;



	public function __construct(?WebImage $webImage = NULL, SetImageFormFactory $factory)
	{

		$this->webImage = $webImage;
		$this->factory = $factory;
	}



	public function createComponentForm()
	{
		$control = $this->factory->create($this->webImage);
		$control->onSuccess[] = function () {
			$this->flashMessage($this->webImage ? "Obrázek byl upraven" : "Obrázek byl uložen", "success");
			$this->close();
		};

		return $control->getForm();
	}



	public function render()
	{
		$this->template->setFile(__DIR__ . "/SetImageModal.latte");
		$this->template->title = $this->webImage ? "Upravit obrázek" : "Přidat obrázek";
		$this->template->render();
	}
}
