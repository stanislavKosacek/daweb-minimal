<?php


namespace App\AdminModule\CzechitasModule\Components\Lesson;



use App\AdminModule\SettingsModule\Components\SetTargetForm;
use App\Model\Czechitas\Lesson\Lesson;
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

interface EditLessonFormFactory
{

	public function create(Lesson $lesson): EditLessonForm;
}



class EditLessonForm
{

	use SmartObject;

	/** @var Lesson */
	private $lesson;

	/** @var Orm */
	private $orm;

	/** @var BaseFormFactory */
	private $baseFormFactory;

	/** @var IImageStorage */
	private $storage;

	/** @var array */
	public $onSuccess = [];



	public function __construct(Lesson $lesson, Orm $orm, BaseFormFactory $baseFormFactory, IImageStorage $storage)
	{
		$this->orm = $orm;
		$this->baseFormFactory = $baseFormFactory;
		$this->storage = $storage;
		$this->lesson = $lesson;
	}



	public function getForm(): BaseForm
	{

		$form = $this->baseFormFactory->create();
		$form->addGroup();
		$form->addText("name", "Název")
			 ->setRequired("Zadejte název");

		$form->addSelect("type", "Typ lekce", Lesson::getTypes())
			 ->setRequired("Vyberte typ lekce");

		$form->addText("start", "Začátek")
			 ->setHtmlType("datetime-local");
//			 ->setRequired("Nastavte začátek lekce");

		$form->addText("end", "Konec")
			 ->setHtmlType("datetime-local");
//			 ->setRequired("Nastavte začátek lekce");

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
//		$form->addImageUpload("image", "Obrázek", Page::getNamespace());
		$form->addSubmitButton("send", "Upravit");

		$defaults = [
			"name" => $this->lesson->getPage()->getName(),
			"type" => $this->lesson->getType(),
			"start" => $this->lesson->getDateStart() ? $this->lesson->getDateStart()->format("Y-m-d\TH:i") : NULL,
			"end" => $this->lesson->getDateEnd() ? $this->lesson->getDateEnd()->format("Y-m-d\TH:i") : NULL,
			"publish" => $this->lesson->getPage()->getPublished() ? 1 : 0,
		];

		if ($this->lesson->getPage()->getPublished()) {
			$defaults["date"] = $this->lesson->getPage()->getPublished()->format("Y-m-d\TH:i");
		}

		$form->setDefaults($defaults);
		$form->onSuccess[] = [$this, "processForm"];

		return $form;

	}



	public function processForm(BaseForm $form)
	{
		$values = $form->getValues();

		$this->lesson->setType($values->type);
		if ($values->start) {
			$this->lesson->setDateStart(new DateTimeImmutable($values->start));
		} else {
			$this->lesson->setDateStart(NULL);
		}

		if ($values->end) {
			$this->lesson->setDateEnd(new DateTimeImmutable($values->end));
		} else {
			$this->lesson->setDateEnd(NULL);
		}

		$this->lesson->getPage()->setName($values->name);
		if (!$values->publish) {
			$this->lesson->getPage()->setPublished(NULL);
		} else {
			$dateTime = new DateTimeImmutable($values->date);
			$this->lesson->getPage()->setPublished($dateTime);
		}

//		if ($values->image) {
//			if ($this->lesson->getPage()->getImage()) {
//				$this->storage->delete($this->lesson->getPage()->getImage());
//			}
//			$this->lesson->getPage()->setImage($this->storage->save($values->image)->getId());
//		}

		$this->orm->persist($this->lesson);
		$this->orm->persist($this->lesson->getPage());
		$this->orm->flush();


		$this->onSuccess();
	}

}
