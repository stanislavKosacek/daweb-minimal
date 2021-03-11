<?php

namespace App\AdminModule\Modal;

use App\Model\Form\BaseFormFactory;
use App\Model\Modal\BaseModal;
use Nette\Utils\IHtmlString;

interface ConfirmModalFactory
{

	/**
	 * @param $message string|string[]
	 * @return ConfirmModal
	 */
	public function create($message);
}


class ConfirmModal extends BaseModal
{

	const NAME = 'ConfirmModal';

	const TYPE_CONFIRM = '_confirm_'; // gives choice yes/no

	const TYPE_ALERT = '_alert_'; // gives no choice, just OK

	/** @var BaseFormFactory */
	private $baseFormFactory;

	/** @var array */
	public $onConfirmed = [];

	/** @var array */
	private $messages;

	/** @var IHtmlString */
	private $extraHtml;

	/** @var string */
	public $type = self::TYPE_CONFIRM;




	public function __construct($message, BaseFormFactory $baseFormFactory)
	{
		$this->messages = !is_array($message) ? (array) $message : $message;
		$this->baseFormFactory = $baseFormFactory;
	}



	/**
	 * @return string
	 */
	public function getType()
	{
		return $this->type;
	}



	/**
	 * @param string $type
	 */
	public function setType($type)
	{
		$this->type = $type;
	}



	/**
	 * alias for setType(self::TYPE_ALERT)
	 */
	public function setAlert()
	{
		$this->setType(self::TYPE_ALERT);
	}



	/**
	 * @param array $messages
	 */
	public function setMessages($messages)
	{
		$this->messages = $messages;
	}



	public function isConfirm()
	{
		return self::TYPE_CONFIRM === $this->type;
	}



	public function isAlert()
	{
		return self::TYPE_ALERT === $this->type;
	}



	/**
	 * @param IHtmlString|string $extraHtml
	 */
	public function setExtraHtml($extraHtml)
	{
		$this->extraHtml = $extraHtml;
	}



	public function createComponentConfirm()
	{
		$form = $this->baseFormFactory->create("admin.modal");
		$form->addSubmit('confirm', "yes")
			->onClick[] = function () {
				$this->onConfirmed(true);
		};
		$form->addSubmit('dismiss', 'no')
			->onClick[] = function () {
				$this->onConfirmed(false);
		};

		return $form;
	}



	public function createComponentAlert()
	{
		$form = $this->baseFormFactory->create();
		$form->addSubmit('dismiss', 'ok')
			->onClick[] = function () {
				$this->onConfirmed(false);
		};

		return $form;
	}



	public function render()
	{
		$this->template->messages = $this->messages;
		$this->template->extraHtml = $this->extraHtml;
		if ($this->isConfirm()) {
			$filename = 'ConfirmModal.latte';
		} else {
			$filename = 'AlertMAlertModal.latteodal.latte';
		}
		$this->template->setFile(__DIR__ . '/' . $filename);
		$this->template->render();
	}


}
