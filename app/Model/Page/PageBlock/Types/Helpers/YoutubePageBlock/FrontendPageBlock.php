<?php


namespace App\Model\Page\PageBlock\Types\Helpers\YoutubePageBlock;

use App\Model\Page\PageBlock\Types\YoutubePageBlock;
use Nette\Application\UI\Control;

interface FrontendPageBlockFactory
{

	public function create(YoutubePageBlock $youtubePageBlock): FrontendPageBlock;

}



class FrontendPageBlock extends Control
{

	/** @var YoutubePageBlock */
	private $youtubePageBlock;



	public function __construct(YoutubePageBlock $youtubePageBlock)
	{
		$this->youtubePageBlock = $youtubePageBlock;
	}



	public function render()
	{
		$this->template->setFile(__DIR__ . "/FrontendPageBlock.latte");
		$this->template->pageBlock = $this->youtubePageBlock;
		$this->template->render();
	}

}
