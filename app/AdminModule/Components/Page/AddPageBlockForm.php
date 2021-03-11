<?php


namespace App\AdminModule\Components\Page;



use App\Model\Form\BaseForm;
use App\Model\Form\BaseFormFactory;
use App\Model\Orm;
use App\Model\Page\Page\Page;
use App\Model\Page\PageBlock\Types\PageBlockTypeProvider;
use Nette\SmartObject;
use Nette\Utils\Strings;

interface AddPageBlockFormFactory
{

	public function create(Page $page): AddPageBlockForm;
}



class AddPageBlockForm
{

	use SmartObject;

	/** @var Orm */
	private $orm;

	/** @var BaseFormFactory */
	private $baseFormFactory;

	/** @var Page */
	private $page;

	/** @var PageBlockTypeProvider */
	private $pageBlockTypeProvider;

	/** @var array */
	public $onSuccess = [];



	public function __construct(Page $page, Orm $orm, BaseFormFactory $baseFormFactory, PageBlockTypeProvider $pageBlockTypeProvider)
	{
		$this->page = $page;
		$this->orm = $orm;
		$this->baseFormFactory = $baseFormFactory;
		$this->pageBlockTypeProvider = $pageBlockTypeProvider;
	}



	public function getForm(): BaseForm
	{

		$form = $this->baseFormFactory->create();

		$blocksTypes = $this->pageBlockTypeProvider->getBlockTypesNameList();

		$form->addSelect("type", "Typ bloku", $blocksTypes);

		$form->addSubmitButton("send", "Přidat");
		$form->onSuccess[] = [$this, "processForm"];

		return $form;

	}



	public function processForm(BaseForm $form)
	{
		$values = $form->getValues();

		$pageBlockType = $this->pageBlockTypeProvider->getBlockTypeByTypeName($values->type);

		$pageBlock = $pageBlockType::createForPage($this->page);


		$this->orm->persistAndFlush($pageBlock);

		$this->onSuccess();
	}

}
