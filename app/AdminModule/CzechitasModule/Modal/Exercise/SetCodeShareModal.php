<?php


namespace App\AdminModule\CzechitasModule\Modal\CodeShare;



use App\AdminModule\CzechitasModule\Components\CodeShare\SetCodeShareFormFactory;
use App\Model\Czechitas\CodeShare\CodeShare;
use App\Model\Modal\BaseModal;

interface SetCodeShareModalFactory
{

	public function create(?CodeShare $codeShare = NULL): SetCodeShareModal;
}



class SetCodeShareModal extends BaseModal
{

	/** @var CodeShare|null */
	private $codeShare;

	/** @var SetCodeShareFormFactory */
	private $factory;



	public function __construct(?CodeShare $codeShare = NULL, SetCodeShareFormFactory $factory)
	{

		$this->codeShare = $codeShare;
		$this->factory = $factory;
	}



	public function createComponentForm()
	{
		$control = $this->factory->create($this->codeShare);
		$control->onSuccess[] = function () {
			$this->flashMessage($this->codeShare ? "Sdílení kódu bylo upraveno" : "Sdílení kódu bylo přidáno", "success");
			$this->close();
		};

		return $control->getForm();
	}



	public function render()
	{
		$this->template->setFile(__DIR__ . "/SetCodeShareModal.latte");
		$this->template->title = $this->codeShare ? "Upravit sdílení kódu" : "Přidat sdílení kódu";
		$this->template->render();
	}
}
