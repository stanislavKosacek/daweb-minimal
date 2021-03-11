<?php

declare(strict_types=1);

namespace App\Presenters;



use App\Model\Czechitas\Countdown\Countdown;
use App\Model\Czechitas\Countdown\CountdownRepository;
use Nette\Application\BadRequestException;

final class CountdownPresenter extends BasePresenter
{

	/** @var CountdownRepository @autowire */
	protected $countdownRepository;

	/** @var Countdown */
	protected $selectedCountdown;



	public function actionDefault($id)
	{
		if (!$id) {
			$this->selectedCountdown = $this->countdownRepository->getDefaultCountdown();
		} else {
			$this->selectedCountdown = $this->countdownRepository->getById($id);
		}

		if (!$this->selectedCountdown) {
			throw new BadRequestException();
		}

		$this->template->countdown = $this->selectedCountdown;
	}



	public function handleGetEnd()
	{
		$this->payload->time = $this->selectedCountdown->getEndTime()->format(DATE_ISO8601);
		$this->payload->text = $this->selectedCountdown->getText();
		$this->sendPayload();
	}
}
