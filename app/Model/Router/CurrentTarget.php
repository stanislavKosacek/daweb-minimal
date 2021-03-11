<?php


namespace App\Model\Router;


use App\Model\Router\Target\Target;
use Nette\SmartObject;

class CurrentTarget
{
	use SmartObject;

	/** @var Target|null */
	private $currentTarget;



	/**
	 * @param Target $target
	 */
	public function setCurrentTarget(Target $target = NULL)
	{
		$this->currentTarget = $target;
	}



	/**
	 * @return Target|null
	 */
	public function getCurrentTarget()
	{
		return $this->currentTarget;
	}
}
