<?php


namespace App\Model\Page\PageBlock\Types\Helpers\WebImagePageBlock;

use App\AdminModule\SettingsModule\Components\SetImageFormFactory;
use App\Model\Orm;
use App\Model\Page\PageBlock\Types\WebImagePageBlock;
use App\Model\WebImage\WebImage;
use Nette\Application\UI\Control;

interface AdminPageBlockFactory
{

	public function create(WebImagePageBlock $webImagePageBlock): AdminPageBlock;

}



class AdminPageBlock extends Control
{

	/** @var WebImagePageBlock */
	private $webImagePageBlock;

	/** @var SetImageFormFactory */
	private $setImageFormFactory;

	/** @var Orm */
	private $orm;



	public function __construct(WebImagePageBlock $webImagePageBlock, Orm $orm, SetImageFormFactory $setImageFormFactory)
	{
		$this->webImagePageBlock = $webImagePageBlock;
		$this->orm = $orm;
		$this->setImageFormFactory = $setImageFormFactory;
	}



	public function render()
	{
		$this->template->setFile(__DIR__ . "/AdminPageBlock.latte");
		$this->template->pageBlock = $this->webImagePageBlock;
		$this->template->render();
	}



	public function createComponentForm()
	{
		$form = $this->setImageFormFactory->create($this->webImagePageBlock->getWebImage());

		$form->onSuccess[] = function (WebImage $webImage) {
			$this->webImagePageBlock->data = ["webImage" => $webImage->getId()];
			$this->orm->persistAndFlush($this->webImagePageBlock);
			$this->flashMessage("Obrázek byl uložen", "success");
			$this->redirect("this");
		};

		$control = $form->getForm();
		$control->setAjax(TRUE);

		return $control;
	}

}
