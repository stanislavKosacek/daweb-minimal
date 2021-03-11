<?php

namespace App\Model\Modal;

use Nette\Application\UI\Control;

class ModalContainer extends Control
{

	/** @var array */
	private $modals = [];

	/** @var array */
	private $showModals = [];

	/** @var array */
	private $modalClasses = [];



	public function showModal($name)
	{
		$this->showModals[] = $name;
		if ($this->presenter->isAjax()) {
			$this->redrawControl(NULL, FALSE);
			$this->redrawControl('modalsContainer');
			$this->redrawControl("scripts");
		}
	}



	public function addModal(IModal $modal, $name, $destination = NULL, array $args = [])
	{
		if ($destination) {
			$modal->setCloseDestination($destination, $args);
			$modal->setCloseUrl($this->presenter->link($destination, $args));
		} else {
			$modal->setCloseUrl($this->presenter->link("this"));
		}
		$this->addComponent($modal, $name);
		$this->modals[] = $name;
		$this->modalClasses[$name] = $modal->getClasses();
	}



	public function render()
	{
		$this->template->setFile(__DIR__ . "/ModalContainer.latte");
		$this->template->showModals = $this->showModals;
		$this->template->modals = $this->modals;
		$this->template->modalClasses = $this->modalClasses;
		$this->template->render();
	}

}
