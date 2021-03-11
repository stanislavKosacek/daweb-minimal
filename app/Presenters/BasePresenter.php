<?php


namespace App\Presenters;

use App\Components\CodeShare\CodeShareComponentFactory;
use App\Components\SwitchLanguageFormFactory;
use App\Model\Czechitas\CodeShare\CodeShareRepository;
use App\Model\Language\LanguageRepository;
use App\Model\Modal\IModal;
use App\Model\Modal\ModalContainer;
use App\Model\Orm;
use App\Model\Router\CurrentTarget;
use App\Model\User\User\UserRepository;
use Contributte\Translation\Translator;
use Kdyby\Autowired\AutowireComponentFactories;
use Kdyby\Autowired\AutowireProperties;
use Nette;
use Nette\Application\UI\Multiplier;
use Nette\ComponentModel\IComponent;
use WebChemistry\Images\IImageStorage;


abstract class BasePresenter extends Nette\Application\UI\Presenter
{

	use AutowireProperties;
	use AutowireComponentFactories;

	/** @persistent */
	public $locale;

	/** @var Translator @autowire */
	public $translator;

	/** @var UserRepository @autowire */
	protected $userRepository;

	/** @var Orm @autowire */
	protected $orm;

	/** @var IImageStorage @autowire */
	public $imageStorage;

	/** @var ModalContainer */
	protected $modalContainer;

	/** @var CurrentTarget @autowire */
	protected $currentTarget;

	/** @var LanguageRepository @autowire */
	protected $languageRepository;

	/** @var SwitchLanguageFormFactory @autowire */
	protected $switchLanguageFormFactory;

	/** @var CodeShareRepository @autowire */
	protected $codeShareRepository;

	/** @var CodeShareComponentFactory @autowire */
	protected $codeShareComponentFactory;

	/** @var Nette\Http\Session @autowire */
	protected $netteHttpSession;



	public function __construct()
	{
		parent::__construct();
		$this->modalContainer = new ModalContainer();
	}



	protected function startup()
	{

		parent::startup();
		$this->getComponent("modalContainer");
		$this->orm;

		$defaultLocale = $this->languageRepository->getDefaultLocale();

		if ($defaultLocale) {
			$this->locale = $defaultLocale->getCode();
		} else {
			$this->locale = "cs";
		}

		if ($this->user->getIdentity() and $this->user->getIdentity()->getDefaultLocale()) {
			$this->locale = $this->user->getIdentity()->getDefaultLocale()->getCode();
		}

		/*
		elseif ($this->getSession() and $this->getSession()->getSection("lang") and $this->getSession()->getSection("lang")->lang) {
			$this->locale = $this->getSession()->getSection("lang")->lang;
		}
		*/

		if ($this->user->getIdentity() and !$this->user->getIdentity()->getDefaultLocale() and $lang = $this->languageRepository->getLanguageByCode($this->locale)) {
			$this->user->getIdentity()->setDefaultLocale($lang);
		}
	}



	public function beforeRender()
	{
		parent::beforeRender();
		if ($this->isAjax()) {
			$this->redrawControl("content");
			$this->redrawControl("heading");
			$this->redrawControl("actions");
			$this->redrawControl("title");
			$this->redrawControl("flashes");
			$this->payload->postGet = TRUE;
			$this->payload->url = $this->link('this');
		}
		$this->template->currentTarget = $this->currentTarget->getCurrentTarget();
		$this->template->allowedLocales = $this->languageRepository->findLanguageList();
	}



	/**
	 * @return ModalContainer
	 */
	public function createComponentModalContainer()
	{
		if ($this->modalContainer instanceof IComponent) {
			$this->modalContainer = new ModalContainer();
		}

		return $this->modalContainer;
	}



	/**
	 * @param IModal $modal
	 * @param string $name
	 * @param null|string $destination
	 * @param array $args
	 * @param string $defaultView
	 */
	protected function raiseModal(IModal $modal, $name, $destination = "default", array $args = [], $defaultView = "default")
	{
		$this->setView($defaultView);
		$this->redrawControl(NULL, FALSE);

		$this->modalContainer->addModal($modal, $name, $destination, $args);
		$this->modalContainer->showModal($name);
	}


	/**
	 * Determines best method for changing location - fix for $.nette.ajax history not working properly with redirect
	 * @param $destination
	 * @param array $args
	 */
	public function moveTo($destination, $args = [])
	{
		if ($this->isAjax()) {
			$this->forward($destination, $args);
		} else {
			$this->redirect($destination, $args);
		}
	}


	protected function createComponentSwitchLanguageForm()
	{
		return new Multiplier(function ($id) {

			if (!$id or !$language = $this->languageRepository->getLanguageById($id)) {
				throw new Nette\Application\BadRequestException();
			}
			$control = $this->switchLanguageFormFactory->create($language, $this->user->getIdentity());

			$control->onSuccess[] = function () {
				$this->redirect("this");
			};

			return $control->getForm();
		});
	}



	public function createComponentCodeShareComponent()
	{
		return new Multiplier(function ($codeShareId) {

			$codeShare = $this->codeShareRepository->getCodeShareById($codeShareId);

			return $this->codeShareComponentFactory->create($codeShare);

		});
	}
}
