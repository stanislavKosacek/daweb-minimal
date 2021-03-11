<?php


namespace App\AdminModule\SettingsModule\Components;


use App\Model\Form\BaseForm;
use App\Model\Form\BaseFormFactory;
use App\Model\Orm;
use App\Model\WebImage\WebImage;
use Nette\Schema\Elements\Base;
use Nette\SmartObject;
use WebChemistry\Images\IImageStorage;
use WebChemistry\Images\Resources\Transfer\UploadResource;

interface SetImageFormFactory
{

	public function create(?WebImage $webImage = NULL): SetImageForm;
}



class SetImageForm
{

	use SmartObject;

	/** @var WebImage|null */
	private $webImage;

	/** @var Orm */
	private $orm;

	/** @var BaseFormFactory */
	private $baseFormFactory;

	/** @var IImageStorage */
	private $storage;

	/** @var array */
	public $onSuccess = [];



	public function __construct(?WebImage $webImage = NULL, BaseFormFactory $baseFormFactory, Orm $orm, IImageStorage $storage)
	{
		$this->webImage = $webImage;
		$this->baseFormFactory = $baseFormFactory;
		$this->orm = $orm;
		$this->storage = $storage;
	}



	public function getForm(): BaseForm
	{

		$form = $this->baseFormFactory->create();

		$image = $form->addImageUpload("image", "Obrázek", WebImage::getNamespace());
		if (!$this->webImage) {
			$image->setRequired("Vyberte obrázek");
		}
		$form->addText("alt", "alt");
		$form->addText("renderWidth", "Šířka px při renderování (max: 1920)")
			 ->setHtmlType("number")
			 ->addCondition(BaseForm::NOT_EQUAL, NULL)
			 ->addRule(BaseForm::MAX, "Maximální šířka je 1920px", 1920);
		//->setHtmlAttribute("max", "1920")

		if ($this->webImage) {
			$form->setDefaults([
				"alt" => $this->webImage->getAlt(),
				"renderWidth" => $this->webImage->getRenderWidth(),
			]);
		}


		$form->addSubmit("send", $this->webImage ? "Upravit" : "Nahrát");
		$form->onSuccess[] = [$this, "processForm"];

		return $form;

	}



	public function processForm(BaseForm $form)
	{
		$values = $form->getValues();
		$webImage = $this->webImage ?? new WebImage();

		if ($values->alt) {
			$webImage->setAlt($values->alt);
		} else {
			$webImage->setAlt(NULL);
		}

		if ($values->renderWidth) {
			$webImage->setRenderWidth($values->renderWidth);
		} else {
			$webImage->setRenderWidth(NULL);
		}

		$image = $values->image;

		if ($image instanceof UploadResource and $image->getUpload()) {

			if ($this->webImage) {
				if ($this->webImage->getImage()) {
					$this->storage->delete($this->webImage->getImage());
				}
			}

			$webImage->setName($image->getUpload()->getSanitizedName());
			$webImage->setSize($image->getUpload()->getSize());
			$webImage->setContentType($image->getUpload()->getContentType());

			if (isset($image->getUpload()->getImageSize()[0])) {
				$webImage->setWidth($image->getUpload()->getImageSize()[0]);
			}
			if (isset($image->getUpload()->getImageSize()[1])) {
				$webImage->setHeight($image->getUpload()->getImageSize()[1]);
			}

			$webImage->setImage($this->storage->save($values->image)->getId());
		}

		$webImage = $this->orm->persistAndFlush($webImage);
		$this->onSuccess($webImage);

	}


}
