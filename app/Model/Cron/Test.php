<?php


namespace App\Model\Cron;


use App\Model\Email\Email\EmailRepository;

interface TestFactory {

    public function create(): Test;

}

class Test
{

	/** @var EmailRepository */
	private $emailRepository;



	public function __construct(EmailRepository $emailRepository)
	{
		$this->emailRepository = $emailRepository;
	}



	public function sendEmails()
	{
		$emails = $this->emailRepository->findUnsentEmails(50);

	}
}
