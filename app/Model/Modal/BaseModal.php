<?php

namespace App\Model\Modal;

use App\Presenters\BasePresenter;
use Nette\Application\UI\Control;

/**
 * @property-read BasePresenter $presenter
 */
class BaseModal extends Control implements IModal
{

	/** @var string */
	public $closeDestination;

	/** @var array */
	public $closeArgs = [];

	/** @var string */
	public $closeUrl;

	protected $closeWithoutRedraw = FALSE;

	private $classes = [];

	protected $templateFile;



	public function setCloseDestination($link, $args = [])
	{
		$this->closeDestination = $link;
		$this->closeArgs = $args;
	}



	/**
	 * This will work only with closeDestination, not closeUrl.
	 * Modal with this on will not redraw underlaying snippets (if ajax),
	 * will just set propper URL in browser location bar.
	 * Do not use when changes in modal should be visible after explicit close of modal
	 * (when you don't redirect yourself)
	 *
	 * @param bool|TRUE $bool
	 */
	public function closeWithoutRedraw($bool = TRUE)
	{
		$this->closeWithoutRedraw = $bool;
	}



	/** @return bool */
	public function isClosingWithoutRedraw()
	{
		return $this->closeWithoutRedraw;
	}



	/**
	 * @param mixed $closeUrl
	 */
	public function setCloseUrl($closeUrl)
	{
		$this->closeUrl = $closeUrl;
	}



	public function flashMessage($message, $type = 'info'): \stdClass
	{
		return $this->presenter->flashMessage($message, $type);
	}



	public function moveTo($destination, $args = [])
	{
		$this->presenter->moveTo($destination, $args);
	}



	public function createComponentClose()
	{
		return new CloseControl($this);
	}



	/** @var string */
	public function getClasses()
	{
		if(empty($this->classes)) {
			return "";
		} else {
			return implode(" ", $this->classes);
		}
	}



	public function addClass(string $class)
	{
		$this->classes[] = $class;
	}



	public function render()
	{
		$this->template->render();
	}



	public function close($closeDestination = NULL, $closeArgs = NULL)
	{
		$this->moveTo($closeDestination ?: $this->closeDestination, $closeArgs ?: $this->closeArgs);
	}

}
