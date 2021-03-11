<?php

namespace App\Model\Form;

use Contributte\Translation\Translator;
use Nette\Application\UI\Form;
use Nette\Bridges\ApplicationLatte\ILatteFactory;
use Nette\Forms\Controls;
use Nette\Forms\IFormRenderer;
use Nette\Forms\Rendering\DefaultFormRenderer;
use Nette\Utils\Html;
use Nette\Utils\Strings;
use WebChemistry\Images\Controls\AdvancedUploadControl;
use WebChemistry\Images\Controls\UploadControl;

interface BaseFormFactory
{

	public function create($translatorDomain = NULL): BaseForm;
}



class BaseForm extends Form
{

	/** @var array */
	private $languages = [];

	/** @var array */
	private $unsetLanguages = [];

	/** @var object */
	private $entity;

	/** @var object[] */
	private $translatedControls = [];

	/** @var  ILatteFactory */
	private $latteFactory;

	/** @var int */
	protected $labelSize = 12;

	/** @var string */
	protected $breakPoint = 'sm';

	/** @var bool */
	private $usedPrimary = FALSE;

	/** @var bool */
	protected $ajaxForm;

	/** @var Translator */
	private $translator;



	public function __construct($translatorDomain = NULL, Translator $translator)
	{
		$this->translator = $translator;
		$this->setTranslator($translatorDomain ? $this->translator->createPrefixedTranslator($translatorDomain) : $this->translator);

	}



	/**
	 * @param array $languages
	 */
	public function setLanguages(array $languages)
	{
		$this->languages = $languages;
	}



	public function setUnsetLanguages()
	{
		if (!$this->entity) {
			return;
		}

		$entityLocales = array_keys($this->entity->getTranslations()->toArray());
		$this->unsetLanguages = array_diff($this->languages, $entityLocales);
		$this->unsetLanguages = array_merge($this->unsetLanguages, $this->emptyTranslationsLocales());
	}



	/**
	 * @param ILatteFactory $latteFactory
	 */
	public function setLatteFactory(ILatteFactory $latteFactory)
	{
		$this->latteFactory = $latteFactory;
	}





	public function getRenderer(): IFormRenderer
	{
		/** @var DefaultFormRenderer $renderer */
		$renderer = parent::getRenderer();

		// setup form rendering
		$renderer->wrappers['controls']['container'] = NULL;
		$renderer->wrappers['pair']['container'] = 'div class=form-group';
		$renderer->wrappers['pair']['.error'] = 'has-error';
		$renderer->wrappers['control']['container'] = 'div class=' . $this->getControlClass();
		$renderer->wrappers['label']['container'] = 'div class="' . $this->getLabelClass() . ' control-label"';
		$renderer->wrappers['control']['description'] = 'span class=help-block';
		$renderer->wrappers['control']['errorcontainer'] = 'span class=text-danger';
		$renderer->wrappers['error']['container'] = 'div';
		$renderer->wrappers['error']['item'] = 'div class="alert alert-danger"';

		return $renderer;
	}



	public function setLabelSize($size = 2, $breakpoint = 'sm')
	{
		$this->labelSize = $size;
		$this->breakPoint = $breakpoint;
	}



	protected function getLabelClass()
	{
		return "col-{$this->breakPoint}-{$this->labelSize}";
	}



	protected function getControlClass()
	{
		return "col-{$this->breakPoint}-" . (12 - $this->labelSize);
	}



	public function getControls(): \Iterator
	{
		$controls = parent::getControls();
		foreach ($controls as $control) {
			$this->setControlClass($control);
		}

		return $controls;
	}



	public function getElementPrototype(): Html
	{
		$prototype = parent::getElementPrototype();
		$prototype->addClass('form-horizontal');

		if ($this->ajaxForm) {
			$prototype->addClass('ajax');
		}

		return $prototype;
	}




	private function setControlClass($control)
	{
		if ($control instanceof Controls\Button) {
			$baseClass = "btn";
			$appendClass = TRUE;
			if ($control->getControlPrototype()->class) {
				foreach ($control->getControlPrototype()->class as $key => $value) {
					if (Strings::contains($key, 'btn')) {
						$appendClass = FALSE;
					}
				}
			}
			$appendClass && $control->getControlPrototype()->addClass(empty($this->usedPrimary) ? $baseClass . ' btn-primary' : $baseClass . ' btn-info');
			$appendClass && $this->usedPrimary = TRUE;

		} elseif ($control instanceof Controls\TextBase || $control instanceof Controls\SelectBox || $control instanceof Controls\MultiSelectBox) {
			$control->getControlPrototype()->addClass('form-control');

		} elseif ($control instanceof Controls\Checkbox || $control instanceof Controls\CheckboxList || $control instanceof Controls\RadioList) {
			$control->getSeparatorPrototype()->setName('div')->addClass($control->getControlPrototype()->type);
		}
	}



	/**
	 * @return bool
	 */
	public function getAjax()
	{
		return $this->ajaxForm;
	}



	/**
	 * @param $bool bool
	 */
	public function setAjax($bool = TRUE)
	{
		$this->ajaxForm = $bool;
	}



	public function addSubmitButton($name, $caption = NULL)
	{
		return $this[$name] = new Controls\SubmitButton($caption);
	}



	public function addImageUpload($name, $label = NULL, $namespace = NULL)
	{
		$control = new UploadControl($label, $namespace);
		return $this[$name] = $control;
	}


}
