<?php


namespace App\Model\Page\PageBlock\Types;


use App\Model\Page\Page\Page;
use App\Model\Page\PageBlock\PageBlock;
use App\Model\Page\PageBlock\Types\Helpers\TextPageBlock\AdminPageBlockFactory;
use App\Model\Page\PageBlock\Types\Helpers\TextPageBlock\FrontendPageBlockFactory;
use Nette\Application\UI\Control;

class TextPageBlock extends PageBlock implements IPageBlock
{

	const TRANSLATED_NAME = "Markdown";

	/** @var AdminPageBlockFactory */
	private $adminPageBlockFactory;

	/** @var FrontendPageBlockFactory */
	private $frontendPageBlockFactory;



	public function injectAdminPageBlock(AdminPageBlockFactory $adminPageBlockFactory, FrontendPageBlockFactory $frontendPageBlockFactory)
	{
		$this->adminPageBlockFactory = $adminPageBlockFactory;
		$this->frontendPageBlockFactory = $frontendPageBlockFactory;
	}



	public static function getTypeStatic(): string
	{
		return self::TYPE_TEXT;
	}



	public static function createForPage(Page $page): IPageBlock
	{
		$block = new TextPageBlock();
		$block->setPriority($page->getNextBlockPriority());
		$block->setPage($page);
		$block->setType(self::TYPE_TEXT);

		return $block;

	}



	public function getAdminComponent(): Control
	{
		return $this->adminPageBlockFactory->create($this);
	}



	public function getText(): ?string
	{
		if ($this->data and $this->data->text) {
			return $this->data->text;
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
