<?php

namespace App\AdminModule\CzechitasModule\Components\CodeShare;

use App\Model\Czechitas\CodeShare\CodeShare;
use App\Model\Czechitas\CodeShare\CodeShareRepository;
use App\Model\Form\BaseForm;
use App\Model\Form\BaseFormFactory;
use App\Model\Orm;
use App\Model\Page\PageBlock\Types\CodeSharePageBlock;
use Nette\SmartObject;
use Nette\Utils\Json;

interface SetCodeShareFormFactory
{

	function create(?CodeShare $codeShare = NULL, ?CodeSharePageBlock $codeSharePageBlock = NULL): SetCodeShareForm;
}



class SetCodeShareForm
{

	use SmartObject;

	/** @var CodeShare|null */
	private $codeShare;

	/** @var CodeSharePageBlock|null */
	private $codeSharePageBlock;

	/** @var Orm */
	private $orm;

	/** @var CodeShareRepository */
	private $codeShareRepository;

	/** @var array */
	public $onSuccess = [];

	/** @var BaseFormFactory */
	private $baseFormFactory;



	public function __construct(?CodeShare $codeShare = NULL, ?CodeSharePageBlock $codeSharePageBlock = NULL, Orm $orm, CodeShareRepository $codeShareRepository, BaseFormFactory $baseFormFactory)
	{
		$this->codeShare = $codeShare;
		$this->codeSharePageBlock = $codeSharePageBlock;
		$this->orm = $orm;
		$this->codeShareRepository = $codeShareRepository;
		$this->baseFormFactory = $baseFormFactory;
	}



	public function getForm()
	{

		$languages = [
			"js" => "JavaScript",
			"html" => "Html",
			"css" => "css",
			"jsx" => "jsx",
			"sass" => "Sass",
			"less" => "Less",
			"txt" => "txt",
			"php" => "PHP",
		];

		$form = $this->baseFormFactory->create();
		$form->addText("name", "Název")
			 ->setRequired()
			 ->setDefaultValue($this->generateName());
		$form->addSelect("language", "Jazyk", $languages)
			 ->setRequired("Vyberte jazyk");
		$form->addTextArea("code", "Kód")
			 ->setAttribute("rows", 10);
		//$form->addTextArea("note", "Poznámka");

		if ($this->codeShare) {
			$form->setDefaults([
				"name" => $this->codeShare->getName(),
				"code" => $this->codeShare->getTextCode(),
				//"note" => $this->codeShare->getNote(),
				"language" => $this->codeShare->getLanguage(),
			]);
		}

		$form->addSubmit("upload", $this->codeShare ? "Upravit" : "Přidat");

		$form->onSuccess[] = [$this, 'processForm'];

		return $form;
	}



	public function processForm(BaseForm $form)
	{
		$values = $form->getValues();

		$codeShare = $this->codeShare ?? new CodeShare();

		$codeShare->setName($values->name);
		$codeShare->setLanguage($values->language);

		$codeShare->setTextCode($values->code);

		//$codeShare->setNote($values->note);
		if ($form->getPresenter()->getUser()->getIdentity()) {
			$codeShare->setUser($form->getPresenter()->getUser()->getIdentity());
		}
		$this->orm->persist($codeShare);

		if ($this->codeSharePageBlock) {
//			$data = ["codeShareId" => $codeShare->getId()];
			$this->codeSharePageBlock->data = ["codeShareId" => $codeShare->getId()];
			$this->orm->persistAndFlush($this->codeSharePageBlock);
		}

		$this->orm->persistAndFlush($codeShare);

		$this->onSuccess($codeShare);
	}



	private function generateName(): string
	{
		$name = "code" . rand(1000, 100000);

		$codeShare = $this->codeShareRepository->getCodeShareByName($name);

		if ($codeShare) {
			$this->generateName();
		} else {
			return $name;
		}
	}
}
