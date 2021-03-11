<?php


namespace App\AdminModule\CzechitasModule\Components\Lesson;



use App\AdminModule\SettingsModule\Components\SetTargetForm;
use App\Model\Czechitas\Lesson\Lesson;
use App\Model\Form\BaseForm;
use App\Model\Form\BaseFormFactory;
use App\Model\Orm;
use App\Model\Page\Page\Page;
use App\Model\Router\Redirect\Redirect;
use App\Model\Router\Target\Target;
use App\Model\Router\Target\TargetRepository;
use Nette\Application\LinkGenerator;
use Nette\SmartObject;
use Nette\Utils\Strings;
use Nextras\Dbal\Utils\DateTimeImmutable;
use WebChemistry\Images\IImageStorage;

interface AddLessonFormFactory
{

	public function create(): AddLessonForm;
}



class AddLessonForm
{

	use SmartObject;

	/** @var Orm */
	private $orm;

	/** @var BaseFormFactory */
	private $baseFormFactory;

	/** @var TargetRepository */
	private $targetRepository;

	/** @var LinkGenerator */
	private $linkGenerator;

	/** @var IImageStorage */
	private $storage;

	/** @var array */
	public $onSuccess = [];



	public function __construct(Orm $orm, BaseFormFactory $baseFormFactory, TargetRepository $targetRepository, LinkGenerator $linkGenerator, IImageStorage $storage)
	{
		$this->orm = $orm;
		$this->baseFormFactory = $baseFormFactory;
		$this->targetRepository = $targetRepository;
		$this->linkGenerator = $linkGenerator;
		$this->storage = $storage;
	}



	public function getForm(): BaseForm
	{

		$form = $this->baseFormFactory->create();
		$form->addGroup();
		$form->addText("name", "Název")
			 ->setRequired("Zadejte název");

		$form->addSelect("type", "Typ lekce", Lesson::getTypes())
			 ->setRequired("Vyberte typ lekce");

		$form->addText("start", "Datum")
			 ->setHtmlAttribute("class", "datepicker")
			->setDefaultValue((new DateTimeImmutable())->format("d.m.Y"))
			 ->setRequired("Nastavte začátek lekce");

//		$form->addText("end", "Konec")
//			 ->setHtmlType("datetime-local")
//			 ->setRequired("Nastavte začátek lekce");

//		$form->addImageUpload("image", "Obrázek", Page::getNamespace());

		$form->addRadioList("publish", "Publikovat", [TRUE => "Ano", FALSE => "Ne"])
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

		$form->addSubmitButton("send", "Odeslat");
		$form->onSuccess[] = [$this, "processForm"];

		return $form;

	}



	public function processForm(BaseForm $form)
	{
		$values = $form->getValues();

		$lesson = new Lesson();
		$lesson->setType($values->type);
		$lesson->setDateStart(new DateTimeImmutable($values->start));
		$lesson->setDateEnd(new DateTimeImmutable($values->start));


		$page = new Page();
		$page->setType(Page::TYPE_LESSON);
		$page->setName($values->name);
		if (!$values->publish) {
			$page->setPublished(NULL);
		} else {
			$dateTime = new DateTimeImmutable($values->date);
			$page->setPublished($dateTime);
		}

//		if ($values->image) {
//			$page->setImage($this->storage->save($values->image)->getId());
//		}

		$page->setLesson($lesson);
		$lesson->setPage($page);

		$this->orm->persist($page);
		$this->orm->persist($lesson);

		$target = new Target();
		$target->setPresenter("Page");
		$target->setAction("default");
		$target->setParamName("id");
		$target->setParamValue($page->getId());
		$target->setLocale("cs");
		$target->setSlug($this->getTargetUrlByPageName($values->name));
		$target->setTitle($values->name);


		$link = $this->linkGenerator->link("Page:default", ["locale" => "cs", "id" => $page->getId()]);


		if (isset($link) and $link and strlen($link) < 60) {
			$link = SetTargetForm::strReplaceFirst($form->getPresenter()->getHttpRequest()->getUrl()->baseUrl, "", $link);
			$redirect = new Redirect();
			$redirect->setFrom($link);
			$redirect->setTo($target->getSlug());
			$this->orm->persistAndFlush($redirect);
		}

		$this->orm->persist($target);

		$page->setTarget($target);
		$this->orm->persist($page);
		$this->orm->flush();


		$this->onSuccess();
	}



	private function getTargetUrlByPageName($name)
	{
		$url = Strings::webalize($name);

		$target = $this->targetRepository->getTargetBySlug($url);

		if (!$target) {
			return $url;
		} else {
			$i = 2;

			while ($target) {
				$url = Strings::webalize($name . "-" . $i);
				$target = $this->targetRepository->getTargetBySlug($url);
				$i++;
			}

			return $url;
		}

	}

}
