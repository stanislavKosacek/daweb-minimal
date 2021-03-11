<?php

namespace App\Model\Czechitas\Countdown;

use Nextras\Dbal\Utils\DateTimeImmutable;
use Nextras\Orm\Entity\Entity;


/**
 * CodeShare
 * @property int $id    {primary}
 * @property string|null $text
 * @property string $template
 * @property bool $default
 * @property DateTimeImmutable $endTime
 */
class Countdown extends Entity
{


	public function onCreate(): void
	{
		$this->endTime = new DateTimeImmutable("+ 10 minutes");
		$this->template = "default";
		$this->text = "Přestávka";
		$this->default = FALSE;
	}



	/**
	 * @return int
	 */
	public function getId(): int
	{
		return $this->id;
	}



	/**
	 * @return string|null
	 */
	public function getText(): ?string
	{
		return $this->text;
	}



	/**
	 * @param string|null $text
	 */
	public function setText(?string $text): void
	{
		$this->text = $text;
	}



	/**
	 * @return string
	 */
	public function getTemplate(): string
	{
		return $this->template;
	}



	/**
	 * @param string $template
	 */
	public function setTemplate(string $template): void
	{
		$this->template = $template;
	}



	/**
	 * @return DateTimeImmutable
	 */
	public function getEndTime(): DateTimeImmutable
	{
		return $this->endTime;
	}



	/**
	 * @param DateTimeImmutable $endTime
	 */
	public function setEndTime(DateTimeImmutable $endTime): void
	{
		$this->endTime = $endTime;
	}



	/**
	 * @return bool
	 */
	public function isDefault(): bool
	{
		return $this->default;
	}



	/**
	 * @param bool $default
	 */
	public function setDefault(bool $default): void
	{
		$this->default = $default;
	}
}
