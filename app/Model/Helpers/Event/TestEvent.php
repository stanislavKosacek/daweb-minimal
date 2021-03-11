<?php


namespace App\Model\Helpers\Event;


use Symfony\Contracts\EventDispatcher\Event;

class TestEvent extends Event
{

	/** @var string */
	private $a;



	public function __construct(string $a)
	{
		$this->a = $a;
	}



	/**
	 * @return string
	 */
	public function getA(): string
	{
		return $this->a;
	}

}
