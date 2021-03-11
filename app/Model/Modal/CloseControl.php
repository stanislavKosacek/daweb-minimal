<?php

namespace App\Model\Modal;

use Nette\Application\Responses\JsonResponse;
use Nette\Application\UI\Control;

class CloseControl extends Control
{

	/** @var IModal */
	private $modal;



	public function __construct(BaseModal $modal)
	{
		$this->modal = $modal;
	}



	public function handleClose()
	{
		$this->presenter->redirect($this->modal->closeDestination, $this->modal->closeArgs);
	}



	public function render()
	{
		$this->template->setFile(__DIR__ . "/CloseControl.latte");
		$this->template->modal = $this->modal;
		$this->template->render();
	}
}
