<?php

namespace App\Model\Email\Email;

use Nextras\Dbal\Utils\DateTimeImmutable;
use Nextras\Orm\Entity\Entity;


/**
 * Email
 *
 * @property int $id    {primary}
 * @property string $from
 * @property string $to
 * @property string|null $subject
 * @property string|null $body
 * @property string $locale
 * @property bool $error
 * @property string|null $errorMessage
 * @property DateTimeImmutable $added
 * @property DateTimeImmutable|null $sent
 * @property DateTimeImmutable|null $again
 * @property string $emailType
 */
class Email extends Entity
{

	public function onCreate(): void
	{
		parent::onCreate();
		$this->added = new DateTimeImmutable();
		$this->error = FALSE;
	}


	/**
	 * @return int
	 */
	public function getId(): int
	{
		return $this->id;
	}



	/**
	 * @return string
	 */
	public function getFrom(): string
	{
		return $this->from;
	}



	/**
	 * @param string $from
	 */
	public function setFrom(string $from): void
	{
		$this->from = $from;
	}



	/**
	 * @return string
	 */
	public function getTo(): string
	{
		return $this->to;
	}



	/**
	 * @param string $to
	 */
	public function setTo(string $to): void
	{
		$this->to = $to;
	}



	/**
	 * @return string|null
	 */
	public function getSubject(): ?string
	{
		return $this->subject;
	}



	/**
	 * @param string|null $subject
	 */
	public function setSubject(?string $subject): void
	{
		$this->subject = $subject;
	}



	/**
	 * @return string|null
	 */
	public function getBody(): ?string
	{
		return $this->body;
	}



	/**
	 * @param string|null $body
	 */
	public function setBody(?string $body): void
	{
		$this->body = $body;
	}



	/**
	 * @return string
	 */
	public function getLocale(): string
	{
		return $this->locale;
	}



	/**
	 * @param string $locale
	 */
	public function setLocale(string $locale): void
	{
		$this->locale = $locale;
	}



	/**
	 * @return DateTimeImmutable
	 */
	public function getAdded(): DateTimeImmutable
	{
		return $this->added;
	}



	/**
	 * @param DateTimeImmutable $added
	 */
	public function setAdded(DateTimeImmutable $added): void
	{
		$this->added = $added;
	}



	/**
	 * @return bool
	 */
	public function isError(): bool
	{
		return $this->error;
	}



	/**
	 * @param bool $error
	 */
	public function setError(bool $error): void
	{
		$this->error = $error;
	}



	/**
	 * @return string|null
	 */
	public function getErrorMessage(): ?string
	{
		return $this->errorMessage;
	}



	/**
	 * @param string|null $errorMessage
	 */
	public function setErrorMessage(?string $errorMessage): void
	{
		$this->errorMessage = $errorMessage;
	}



	/**
	 * @return DateTimeImmutable|null
	 */
	public function getAgain(): ?DateTimeImmutable
	{
		return $this->again;
	}



	/**
	 * @param DateTimeImmutable|null $again
	 */
	public function setAgain(?DateTimeImmutable $again): void
	{
		$this->again = $again;
	}



	/**
	 * @return string
	 */
	public function getEmailType(): string
	{
		return $this->emailType;
	}



	/**
	 * @param string $emailType
	 */
	public function setEmailType(string $emailType): void
	{
		$this->emailType = $emailType;
	}



	/**
	 * @return DateTimeImmutable|null
	 */
	public function getSent(): ?DateTimeImmutable
	{
		return $this->sent;
	}



	/**
	 * @param DateTimeImmutable|null $sent
	 */
	public function setSent(?DateTimeImmutable $sent): void
	{
		$this->sent = $sent;
	}

}
