<?php


namespace App\AdminModule\Components\Page;



use App\AdminModule\SettingsModule\Components\SetTargetForm;
use App\Model\Email\EmailType\EmptyEmail\EmptyEmail;
use App\Model\Form\BaseForm;
use App\Model\Form\BaseFormFactory;
use App\Model\Orm;
use App\Model\Page\Page\Page;
use App\Model\Router\Redirect\Redirect;
use App\Model\Router\Target\Target;
use App\Model\Router\Target\TargetRepository;
use Contributte\Translation\Translator;
use Nette\Application\LinkGenerator;
use Nette\SmartObject;
use Nette\Utils\Strings;
use Nextras\Dbal\Utils\DateTimeImmutable;
use WebChemistry\Images\IImageStorage;

interface EditPageFormFactory
{

	public function create(Page $page): EditPageForm;
}



class EditPageForm
{

	use SmartObject;

	/** @var Page */
	private $page;

	/** @var Orm */
	private $orm;

	/** @var BaseFormFactory */
	private $baseFormFactory;

	/** @var IImageStorage */
	private $storage;

	/** @var array */
	public $onSuccess = [];



	public function __construct(Page $page, Orm $orm, BaseFormFactory $baseFormFactory, IImageStorage $storage)
	{
		$this->page = $page;
		$this->orm = $orm;
		$this->baseFormFactory = $baseFormFactory;
		$this->storage = $storage;
	}



	public function getForm(): BaseForm
	{

		$form = $this->baseFormFactory->create();
		$form->addGroup();
		$form->addText("name", "Název")
			 ->setRequired("Zadejte název");

		$form->addRadioList("publish", "Publikovat", [1 => "Ano", 0 => "Ne"])
			 ->setDefaultValue(TRUE)
			 ->setRequired("Vyberte publikovat")
			 ->addCondition(BaseForm::EQUAL, TRUE)
			 ->toggle("selectDate");

		$form->addGroup()->setOption("id", "selectDate");
		$form->addText("date", "Datum publikování")
			 ->setDefaultValue((new DateTimeImmutable())->format("Y-m-d\TH:i"))
			 ->setHtmlType("datetime-local")
			 ->addConditionOn($form->getComponent("publish"), BaseForm::EQUAL, TRUE)
			 ->setRequired("Zadejte datum publikování");


		$form->addGroup();
		$form->addImageUpload("image", "Obrázek", Page::getNamespace());
		$form->addSubmitButton("send", "Odeslat");

		$defaults = [
			"name" => $this->page->getName(),
			"publish" => $this->page->getPublished() ? 1 : 0,
		];

		if ($this->page->getPublished()) {
			$defaults["date"] = $this->page->getPublished()->format("Y-m-d\TH:i");
		}


		$form->setDefaults($defaults);
		$form->onSuccess[] = [$this, "processForm"];

		return $form;

	}



	public function processForm(BaseForm $form)
	{
		$values = $form->getValues();

		$this->page->setName($values->name);
		if (!$values->publish) {
			$this->page->setPublished(NULL);
		} else {
			$dateTime = new DateTimeImmutable($values->date);
			$this->page->setPublished($dateTime);
		}

		if ($values->image) {
			if ($this->page->getImage()) {
				$this->storage->delete($this->page->getImage());
			}
			$this->page->setImage($this->storage->save($values->image)->getId());
		}

		$this->orm->persistAndFlush($this->page);


		$this->onSuccess();
	}

}
