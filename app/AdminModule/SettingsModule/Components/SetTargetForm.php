<?php


namespace App\AdminModule\SettingsModule\Components;


use App\Model\Form\BaseForm;
use App\Model\Form\BaseFormFactory;
use App\Model\Orm;
use App\Model\Page\Page\Page;
use App\Model\Router\Redirect\Redirect;
use App\Model\Router\Target\Target;
use App\Model\Router\Target\TargetRepository;
use Nette\Application\LinkGenerator;
use Nette\SmartObject;

interface SetTargetFormFactory
{

	public function create(?Target $target = NULL, Page $page = NULL): SetTargetForm;
}



class SetTargetForm
{

	use SmartObject;

	/** @var Target|null */
	private $target;

	/** @var BaseFormFactory */
	private $baseFormFactory;

	/** @var Orm */
	private $orm;

	/** @var TargetRepository */
	private $targetRepository;

	/** @var array */
	public $onSuccess = [];

	/** @var LinkGenerator */
	private $linkGenerator;

	/** @var Page|null */
	private $page;



	public function __construct(?Target $target = NULL, Page $page = NULL, BaseFormFactory $baseFormFactory, Orm $orm, TargetRepository $targetRepository, LinkGenerator $linkGenerator)
	{
		$this->target = $target;
		$this->baseFormFactory = $baseFormFactory;
		$this->orm = $orm;
		$this->targetRepository = $targetRepository;
		$this->linkGenerator = $linkGenerator;
		$this->page = $page;
	}



	public function getForm(): BaseForm
	{

		$form = $this->baseFormFactory->create();
		$presenter = $form->addText("presenter", "Presenter")
						  ->setRequired("Zadejte presenter");
		$action = $form->addText("action", "Akce")
					   ->setRequired("Zadejte akci");
		$locale = $form->addSelect("locale", "Jazyk", ["cs" => "cs"])
					   ->setRequired("Vyberte jazyk");

		$param = $form->addText("paramName", "Parametr");
		$paramValue = $form->addText("paramValue", "Hodnota parametru");

		$form->addText("title", "SEO Title");
		$form->addTextArea("description", "SEO Description");

		$form->addText("slug", "URL")
			 ->setRequired("Zadejte URL");


		if ($this->page) {
			$presenter->controlPrototype->class("d-none");
			$presenter->labelPrototype->class("d-none");
			$action->setHtmlAttribute("class", "d-none");
			$action->labelPrototype->class("d-none");
			$locale->setHtmlAttribute("class", "d-none");
			$locale->labelPrototype->class("d-none");
			$param->setHtmlAttribute("class", "d-none");
			$param->labelPrototype->class("d-none");
			$paramValue->setHtmlAttribute("class", "d-none");
			$paramValue->labelPrototype->class("d-none");
		}

		$form->addSubmit("send", $this->target ? "Upravit" : "Přidat");
		$form->onValidate[] = [$this, "validateForm"];
		$form->onSuccess[] = [$this, "processForm"];

		$defaults = [];
		if ($this->target) {
			$defaults["presenter"] = $this->target->getPresenter();
			$defaults["action"] = $this->target->getAction();
			$defaults["locale"] = $this->target->getLocale();
			$defaults["slug"] = $this->target->getSlug() == "" ? "/" : $this->target->getSlug();
			$defaults["paramName"] = $this->target->getParamName();
			$defaults["paramValue"] = $this->target->getParamValue();
			$defaults["title"] = $this->target->getTitle();
			$defaults["description"] = $this->target->getDescription();
		}
		$form->setDefaults($defaults);

		return $form;

	}



	public function validateForm(BaseForm $form)
	{
		$target = $this->targetRepository->getTargetBySlug($form->getValues()->slug);
		if ($target and $target !== $this->target) {
			$form->getComponent("slug")->addError("Tato url již existuje");
		}
	}



	public function processForm(BaseForm $form)
	{
		$values = $form->getValues();

		if ($this->target) {
			if ($this->target->getSlug() != $values->slug and $values->slug != "/") {
				$redirect = new Redirect();
				$redirect->setFrom($this->target->getSlug());
				$redirect->setTo($values->slug);
				$this->orm->persistAndFlush($redirect);
			}
		} else {
			if ($values->paramName) {
				$link = $this->linkGenerator->link("$values->presenter:$values->action", ["locale" => $values->locale, $values->paramName => $values->paramValue]);
			} else {
				$link = $this->linkGenerator->link("$values->presenter:$values->action", ["locale" => $values->locale]);
			}


			if (isset($link) and $link and strlen($link) < 60 and $values->slug != "/") {
				$link = $this->strReplaceFirst($form->getPresenter()->getHttpRequest()->getUrl()->baseUrl, "", $link);
				$redirect = new Redirect();
				$redirect->setFrom($link);
				$redirect->setTo($values->slug);
				$this->orm->persistAndFlush($redirect);
			}

		}

		$target = $this->target ?? new Target();

		$target->setPresenter($values->presenter);
		$target->setAction($values->action);
		if ($values->slug == "/") {
			$target->setSlug("");
		} else {
			$target->setSlug($values->slug);
		}
		$target->setLocale($values->locale);

		if ($values->paramName) {
			$target->setParamName($values->paramName);
		} else {
			$target->setParamName(NULL);
		}

		if ($values->paramValue) {
			$target->setParamValue($values->paramValue);
		} else {
			$target->setParamValue(NULL);
		}

		if ($values->title) {
			$target->setTitle($values->title);
		} else {
			$target->setTitle(NULL);
		}

		if ($values->description) {
			$target->setDescription($values->description);
		} else {
			$target->setDescription(NULL);
		}

		$this->orm->persistAndFlush($target);

		if ($this->page) {
			$this->page->setTarget($target);
			$this->orm->persistAndFlush($this->page);
		}
		$this->onSuccess();
	}



	public static function strReplaceFirst($from, $to, $content)
	{
		$from = '/' . preg_quote($from, '/') . '/';

		return preg_replace($from, $to, $content, 1);
	}
}
