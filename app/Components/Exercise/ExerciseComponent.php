<?php


namespace App\Components\Exercise;


use App\Model\Czechitas\CodeShare\CodeShare;
use App\Model\Czechitas\Exercise\Exercise;
use Nette\Application\UI\Control;

interface ExerciseComponentFactory
{

	public function create(Exercise $exercise): ExerciseComponent;

}



class ExerciseComponent extends Control
{


	/** @var Exercise */
	private $exercise;



	public function __construct(Exercise $exercise)
	{
		$this->exercise = $exercise;
	}



	public function render()
	{
		$this->template->setFile(__DIR__ . "/ExerciseComponent.latte");
		$this->template->exercise = $this->exercise;

//		$this->template->uniqueId = $this->getUniqueId();
		$this->template->render();
	}

}
