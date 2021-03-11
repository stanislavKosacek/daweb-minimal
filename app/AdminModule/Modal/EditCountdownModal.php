<?php


namespace App\AdminModule\Modal;



use App\Model\Czechitas\Countdown\Countdown;
use App\Model\Form\BaseForm;
use App\Model\Form\BaseFormFactory;
use App\Model\Modal\BaseModal;
use App\Model\Orm;
use Nextras\Dbal\Utils\DateTimeImmutable;

interface EditCountdownModalFactory
{

	public function create(Countdown $countdown): EditCountdownModal;
}



class EditCountdownModal extends BaseModal
{


	/** @var Countdown */
	private Countdown $countdown;

	/** @var Orm */
	private Orm $orm;

	/** @var BaseFormFactory */
	private BaseFormFactory $factory;



	public function __construct(Countdown $countdown, Orm $orm, BaseFormFactory $factory)
	{
		$this->countdown = $countdown;
		$this->orm = $orm;
		$this->factory = $factory;
	}



	public function render()
	{
		$this->template->setFile(__DIR__ . "/EditCountdownModal.latte");
		$this->template->title = "Odpočet";
		$this->template->countdown = $this->countdown;
		$this->template->render();
	}



	public function handleGetEnd()
	{
		$this->presenter->payload->time = $this->countdown->getEndTime()->format(DATE_ISO8601);
		$this->presenter->sendPayload();
	}



	public function handleReset($minutes = 10)
	{
		$this->countdown->setEndTime(new DateTimeImmutable("+ $minutes minutes"));
		$this->orm->persistAndFlush($this->countdown);

		$this->redirect("this");
	}



	public function handlePlus($minutes = 1)
	{
		$this->countdown->setEndTime($this->countdown->getEndTime()->modify("+ $minutes minutes"));
		$this->orm->persistAndFlush($this->countdown);

		$this->redirect("this");
	}



	public function handleMinus($minutes = 1)
	{
		$this->countdown->setEndTime($this->countdown->getEndTime()->modify("- $minutes minutes"));
		$this->orm->persistAndFlush($this->countdown);

		$this->redirect("this");
	}



	public function createComponentForm()
	{
		$form = $this->factory->create();

		$form->addText("time", "Nastavit konkrétní čas")
			 ->setHtmlType("time")
			 ->setRequired();

		$form->addSubmit("send", "Uložit čas");

		$form->setDefaults(["time" => $this->countdown->getEndTime()->format("H:i")]);

		$form->onSuccess[] = [$this, "processForm"];

		return $form;
	}



	public function processForm(BaseForm $form)
	{
		$values = $form->getValues();

		$time = explode(":", $values->time);
		$endTime = new DateTimeImmutable();
		$this->countdown->setEndTime($endTime->setTime($time[0], $time[1], 0));

		$this->orm->persistAndFlush($this->countdown);
		$this->redirect("this");
	}



	public function createComponentFormText()
	{
		$form = $this->factory->create();

		$form->addText("text", "Text");

		$form->addSubmit("send", "Změnit text");

		$form->setDefaults(["text" => $this->countdown->getText()]);

		$form->onSuccess[] = [$this, "processFormText"];

		return $form;
	}



	public function processFormText(BaseForm $form)
	{
		$values = $form->getValues();

		$this->countdown->setText($values->text);

		$this->orm->persistAndFlush($this->countdown);
		$this->redirect("this");
	}
}
