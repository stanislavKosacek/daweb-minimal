<?php


namespace App\AdminModule\SettingsModule\Presenters;


use App\AdminModule\Presenters\SecuredPresenter;
use App\AdminModule\SettingsModule\Grid\ImageGridFactory;
use App\AdminModule\SettingsModule\Modal\SetImageModalFactory;
use App\Model\WebImage\WebImage;
use App\Model\WebImage\WebImageRepository;
use Nette\Application\BadRequestException;

class ImagePresenter extends SecuredPresenter
{

	/** @var SetImageModalFactory @autowire */
	protected $setImageModalFactory;

	/** @var ImageGridFactory @autowire */
	protected $imageGridFactory;

	/** @var WebImageRepository @autowire */
	protected $webImageRepository;

	/** @var WebImage */
	protected $selectedImage;



	public function renderDefault()
	{

	}



	public function actionAdd()
	{
		$modal = $this->setImageModalFactory->create();
		$this->raiseModal($modal, "addImage", "default");
	}


	public function actionEdit($id)
	{
		if (!$id or !$this->selectedImage = $this->webImageRepository->getWebImageById($id)) {
			throw new BadRequestException();
		}
		$modal = $this->setImageModalFactory->create($this->selectedImage);
		$this->raiseModal($modal, "editImage", "default");
	}



	public function createComponentImageGrid()
	{
		$grid = $this->imageGridFactory->create();

		return $grid->getGrid();

	}
}
