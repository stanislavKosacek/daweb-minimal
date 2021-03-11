<?php


namespace App\Components;


use App\Model\Form\BaseForm;
use App\Model\Form\BaseFormFactory;
use App\Model\Language\Language;
use App\Model\Orm;
use App\Model\User\User\User;
use Nette\SmartObject;


interface SwitchLanguageFormFactory
{

	public function create(Language $language, User $userEntity = NULL): SwitchLanguageForm;
}



class SwitchLanguageForm
{

	use SmartObject;

	/** @var Language */
	private $language;

	/** @var User */
	private $userEntity;

	/** @var Orm */
	private $orm;

	/** @var BaseFormFactory */
	private $baseFormFactory;

	/** @var array */
	public $onSuccess = [];



	public function __construct(Language $language, User $userEntity = NULL, Orm $orm, BaseFormFactory $baseFormFactory)
	{
		$this->language = $language;
		$this->userEntity = $userEntity;
		$this->orm = $orm;
		$this->baseFormFactory = $baseFormFactory;
	}



	public function getForm()
	{
		$form = $this->baseFormFactory->create();
		$form->addSubmit("send", $this->language->getName());


		$form->onSuccess[] = [$this, 'processForm'];

		return $form;

	}



	public function processForm(BaseForm $form)
	{

		$form->getPresenter()->getSession()->getSection("lang")->lang = $this->language->getCode();

		if ($this->userEntity) {
			$this->userEntity->setDefaultLocale($this->language);
			$this->orm->persistAndFlush($this->userEntity);
		}

		$this->onSuccess();

	}
}
