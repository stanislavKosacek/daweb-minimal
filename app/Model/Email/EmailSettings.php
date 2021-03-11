<?php

namespace App\Model\Email;



use App\Model\Email\Email\Email;
use App\Model\Orm;
use Nette\Mail\Message;
use Nette\Mail\SmtpException;
use Nette\Mail\SmtpMailer;
use Nextras\Dbal\Utils\DateTimeImmutable;


class EmailSettings
{

	/** @var array */
	private $emailSettings;



	public function __construct(array $emailSettings)
	{
		$this->emailSettings = $emailSettings;
	}



	/**
	 * @return array
	 */
	public function getEmailSettings(): array
	{
		return $this->emailSettings;
	}


}
