<?php


namespace App\Model\Page\PageBlock\Types;


use App\Model\Page\Page\Page;
use App\Model\Page\PageBlock\PageBlock;
use App\Model\Page\PageBlock\Types\Helpers\YoutubePageBlock\AdminPageBlockFactory;
use App\Model\Page\PageBlock\Types\Helpers\YoutubePageBlock\FrontendPageBlockFactory;
use Nette\Application\UI\Control;

class YoutubePageBlock extends PageBlock implements IPageBlock
{

	const TRANSLATED_NAME = "Youtube";

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
		return self::TYPE_YOUTUBE;
	}



	public static function createForPage(Page $page): IPageBlock
	{
		$block = new YoutubePageBlock();
		$block->setPriority($page->getNextBlockPriority());
		$block->setPage($page);
		$block->setType(self::TYPE_YOUTUBE);

		return $block;

	}



	public function getAdminComponent(): Control
	{
		return $this->adminPageBlockFactory->create($this);
	}



	public function getYoutubeId(): ?string
	{

		if ($this->data and $this->data->youtube) {
			return $this->data->youtube;
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
