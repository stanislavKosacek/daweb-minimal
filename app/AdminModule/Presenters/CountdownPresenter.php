<?php


namespace App\AdminModule\Presenters;



use App\AdminModule\Modal\EditCountdownModalFactory;
use App\Model\Czechitas\Countdown\Countdown;
use App\Model\Czechitas\Countdown\CountdownRepository;
use Nette\Application\BadRequestException;
use Nextras\Dbal\Utils\DateTimeImmutable;

class CountdownPresenter extends SecuredPresenter
{

	/** @var CountdownRepository @autowire */
	protected $countdownRepository;


	/** @var Countdown */
	protected $selectedCountdown;

	/** @var EditCountdownModalFactory @autowire */
	protected $editCountdownModalFactory;



	public function renderDefault()
	{
		$this->template->countdownList = $this->countdownRepository->getCountdownList();
	}



	public function actionDetail($id)
	{
		if (!$id or !$this->selectedCountdown = $this->countdownRepository->getById($id)) {
			throw new BadRequestException();
		}

		$modal = $this->editCountdownModalFactory->create($this->selectedCountdown);
		$modal->addClass("modal-xl");
		$this->raiseModal($modal, "editCountdown", "default");
	}



	public function handleAdd()
	{
		$countdown = new Countdown();
		$this->orm->persistAndFlush($countdown);

		$this->redirect("this");
	}



	public function handleReset($id)
	{
		$countDown = $this->countdownRepository->getCountdownById($id);

		if ($countDown) {
			$countDown->setEndTime(new DateTimeImmutable("+ 10 minutes"));
			$this->orm->persistAndFlush($countDown);
		}

		$this->redirect("this");
	}



	public function handlePlusMinute($id)
	{
		$countDown = $this->countdownRepository->getCountdownById($id);

		if ($countDown) {
			$countDown->setEndTime($countDown->getEndTime()->modify("+ 1 minute"));
			$this->orm->persistAndFlush($countDown);
		}

		$this->redirect("this");
	}



	public function handleMinusMinute($id)
	{
		$countDown = $this->countdownRepository->getCountdownById($id);

		if ($countDown) {
			$countDown->setEndTime($countDown->getEndTime()->modify("- 1 minute"));
			$this->orm->persistAndFlush($countDown);
		}

		$this->redirect("this");
	}



	public function handleRemove($id)
	{
		$countDown = $this->countdownRepository->getCountdownById($id);

		if ($countDown) {
			$this->orm->removeAndFlush($countDown);
		}

		$this->redirect("this");
	}



	public function handleSwitchDefault($id)
	{
		$countdown = $this->countdownRepository->getCountdownById($id);
		$defaultCountdown = $countDown = $this->countdownRepository->getDefaultCountdown();

		if ($countdown) {
			$countdown->setDefault(TRUE);
			$this->orm->persist($countdown);

			if ($defaultCountdown) {
				$defaultCountdown->setDefault(FALSE);
				$this->orm->persist($defaultCountdown);
			}
			$this->orm->flush();
		}

		$this->redirect("this");

	}
}
