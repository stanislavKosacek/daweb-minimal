<?php


namespace App\Model\Page\PageBlock\Types;


use App\Model\Czechitas\CodeShare\CodeShare;
use App\Model\Czechitas\CodeShare\CodeShareRepository;
use App\Model\Page\Page\Page;
use App\Model\Page\PageBlock\PageBlock;
use App\Model\Page\PageBlock\Types\Helpers\CodeSharePageBlock\AdminPageBlockFactory;
use App\Model\Page\PageBlock\Types\Helpers\CodeSharePageBlock\FrontendPageBlockFactory;
use Nette\Application\UI\Control;

class CodeSharePageBlock extends PageBlock implements IPageBlock
{

	const TRANSLATED_NAME = "Sdílení kódu";

	/** @var AdminPageBlockFactory */
	private $adminPageBlockFactory;

	/** @var CodeShareRepository */
	private $codeShareRepository;

	/** @var FrontendPageBlockFactory @autowire */
	protected $frontendPageBlockFactory;



	public function injectAdminPageBlock(AdminPageBlockFactory $adminPageBlockFactory, FrontendPageBlockFactory $frontendPageBlockFactory, CodeShareRepository $codeShareRepository)
	{
		$this->adminPageBlockFactory = $adminPageBlockFactory;
		$this->frontendPageBlockFactory = $frontendPageBlockFactory;
		$this->codeShareRepository = $codeShareRepository;
	}



	public static function getTypeStatic(): string
	{
		return self::TYPE_CODE_SHARE;
	}



	public static function createForPage(Page $page): IPageBlock
	{
		$block = new CodeSharePageBlock();
		$block->setPriority($page->getNextBlockPriority());
		$block->setPage($page);
		$block->setType(self::TYPE_CODE_SHARE);

		return $block;
	}



	public function getAdminComponent(): Control
	{
		return $this->adminPageBlockFactory->create($this);
	}



	public function getCodeShare(): ?CodeShare
	{

		if ($this->data and $this->data->codeShareId) {
			return $this->codeShareRepository->getCodeShareById($this->data->codeShareId);
		}

		return NULL;
	}



	/**
	 * @inheritDoc
	 */
	public static function getTranslatedName(): string
	{
		return self::TRANSLATED_NAME;
	}



	public function getFrontendComponent(): Control
	{
		return $this->frontendPageBlockFactory->create($this);
	}
}
