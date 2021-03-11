<?php


namespace App\Model\Page\PageBlock\Types\Helpers\YoutubePageBlock;

use App\Model\Form\BaseForm;
use App\Model\Form\BaseFormFactory;
use App\Model\Orm;
use App\Model\Page\PageBlock\Types\YoutubePageBlock;
use Nette\Application\UI\Control;

interface AdminPageBlockFactory
{

	public function create(YoutubePageBlock $youtubePageBlock): AdminPageBlock;

}



class AdminPageBlock extends Control
{

	/** @var YoutubePageBlock */
	private $youtubePageBlock;

	/** @var BaseFormFactory */
	private $baseFormFactory;

	/** @var Orm */
	private $orm;



	public function __construct(YoutubePageBlock $youtubePageBlock, BaseFormFactory $baseFormFactory, Orm $orm)
	{
		$this->youtubePageBlock = $youtubePageBlock;
		$this->baseFormFactory = $baseFormFactory;
		$this->orm = $orm;
	}



	public function render()
	{
		$this->template->setFile(__DIR__ . "/AdminPageBlock.latte");
		$this->template->pageBlock = $this->youtubePageBlock;
		$this->template->render();
	}



	public function createComponentForm()
	{
		$form = $this->baseFormFactory->create();
		$form->setAjax(TRUE);
		$form->addText("youtube", "Youtube id nebo URL");

		$form->addSubmitButton("save", $this->youtubePageBlock->getYoutubeId() ? "Upravit" : "Uložit");

		if ($this->youtubePageBlock->getYoutubeId()) {
			$form->setDefaults([
				"youtube" => $this->youtubePageBlock->getYoutubeId(),
			]);
		}

		$form->onSuccess[] = [$this, "processForm"];


		return $form->getForm();
	}



	public function processForm(BaseForm $form)
	{
		$values = $form->getValues();


		$this->youtubePageBlock->data = ["youtube" => $this->extractYoutubeId($values->youtube)];

		$this->orm->persistAndFlush($this->youtubePageBlock);

		$this->redirect("this");
	}



	private function extractYoutubeId($url)
	{
		preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $url, $match);

		return $match[1] ?? $url;

	}

}
