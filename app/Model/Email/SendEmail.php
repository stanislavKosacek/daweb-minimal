<?php

namespace App\Model\Email;



use App\Model\Email\Email\Email;
use App\Model\Orm;
use Nette\Mail\Message;
use Nette\Mail\SmtpException;
use Nette\Mail\SmtpMailer;
use Nextras\Dbal\Utils\DateTimeImmutable;

interface SendEmailFactory {

    public function create(Email $email): SendEmail;

}

class SendEmail
{

	/** @var array */
	private $emailSettings;

	/** @var Orm */
	private $orm;

	/** @var Email */
	private $email;



	public function __construct(Email $email, EmailSettings $emailSettings, Orm $orm)
	{
		$this->email = $email;
		$this->emailSettings = $emailSettings->getEmailSettings();
		$this->orm = $orm;
	}



	public function sendEmail()
	{
		$mail = new Message();
		$mail->setHtmlBody($this->email->getBody(), "./assets/email/");
		$mail->setSubject($this->email->getSubject());
		$mail->setFrom($this->email->getFrom());
		$mail->addTo($this->email->getTo());
		$mail->setHeader("MIME-Version", "1.0");
		$mail->setHeader("X-Mailer", "PHP/" . phpversion());
		$mail->setHeader("Content-Type", "text/html; charset=utf-8");
		$mail->setHeader("List-Unsubscribe", "<mailto: standa@skosacek.cz?subject=unsubscribe>");
		$this->send($mail);

		$this->orm->persistAndFlush($this->email);

		return $this->email;

	}



	private function send(Message $email): void
	{
		$mailer = new SmtpMailer(["host" => $this->emailSettings["host"], "port" => $this->emailSettings["port"] , "username" => $this->emailSettings["username"], "password" => $this->emailSettings["password"]]);
		try {
			$mailer->send($email);
			$this->email->setError(0);
			$this->email->setErrorMessage(NULL);
			$this->email->setSent(new DateTimeImmutable());
		} catch (SmtpException $e) {
			$this->email->setError(1);
			$this->email->setErrorMessage($e->getMessage());
		}

	}


}
