<?php


namespace App\Model\Cron;


use App\Model\Email\Email\EmailRepository;
use App\Model\Email\SendEmailFactory;
use Contributte\Scheduler\IJob;
use DateTime;


class SendEmails implements IJob
{

	/** @var EmailRepository */
	private $emailRepository;

	/** @var SendEmailFactory */
	private $sendEmailFactory;



	public function __construct(EmailRepository $emailRepository, SendEmailFactory $sendEmailFactory)
	{
		$this->emailRepository = $emailRepository;
		$this->sendEmailFactory = $sendEmailFactory;
	}


	public function isDue(DateTime $dateTime): bool
	{
		return TRUE;
	}



	public function run(): void
	{
		$unsentEmails = $this->emailRepository->findUnsentEmails(50);
		foreach ($unsentEmails as $email) {
			$this->sendEmailFactory->create($email)->sendEmail();
		}

	}
}
