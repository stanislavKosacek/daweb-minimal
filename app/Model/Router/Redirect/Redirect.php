<?php

namespace App\Model\Router\Redirect;

use Nextras\Orm\Entity\Entity;


/**
 * User
 * @property int $id    {primary}
 * @property string $from
 * @property string $to
 */
class Redirect extends Entity
{




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


}
