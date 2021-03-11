<?php


namespace App\Model\Page\PageBlock\Types\Helpers\Exercise;

use App\Model\Page\PageBlock\Types\ExercisePageBlock;
use Nette\Application\UI\Control;

interface FrontendPageBlockFactory
{

	public function create(ExercisePageBlock $exercisePageBlock): FrontendPageBlock;

}



class FrontendPageBlock extends Control
{

	/** @var ExercisePageBlock */
	private ExercisePageBlock $exercisePageBlock;



	public function __construct(ExercisePageBlock $exercisePageBlock)
	{
		$this->exercisePageBlock = $exercisePageBlock;
	}



	public function render()
	{
		$this->template->setFile(__DIR__ . "/FrontendPageBlock.latte");
		$this->template->pageBlock = $this->exercisePageBlock;
		$this->template->render();
	}

}
