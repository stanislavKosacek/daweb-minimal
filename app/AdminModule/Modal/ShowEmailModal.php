<?php


namespace App\AdminModule\Modal;


use App\Model\Email\Email\Email;
use App\Model\Modal\BaseModal;
use Contributte\Translation\Translator;

interface ShowEmailModalFactory
{


	public function create(Email $email): ShowEmailModal;

}



class ShowEmailModal extends BaseModal
{


	/** @var Email */
	private $email;

	/** @var Translator */
	private $translator;



	public function __construct(Email $email, Translator $translator)
	{
		$this->email = $email;
		$this->translator = $translator;
	}



	public function render()
	{


		$this->template->setFile(__DIR__ . "/ShowEmailModal.latte");
		$this->template->title = 'Detail emailu';
		$this->template->locale = $this->translator->getLocale();
		$this->template->email = $this->email;
		$this->template->render();
	}

}
