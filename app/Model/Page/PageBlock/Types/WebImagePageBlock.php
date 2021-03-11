<?php


namespace App\Model\Page\PageBlock\Types;


use App\Model\Page\Page\Page;
use App\Model\Page\PageBlock\PageBlock;
use App\Model\Page\PageBlock\Types\Helpers\WebImagePageBlock\AdminPageBlockFactory;
use App\Model\Page\PageBlock\Types\Helpers\WebImagePageBlock\FrontendPageBlockFactory;
use App\Model\WebImage\WebImage;
use App\Model\WebImage\WebImageRepository;
use Nette\Application\UI\Control;


class WebImagePageBlock extends PageBlock implements IPageBlock
{

	const TRANSLATED_NAME = "Obrázek";

	/** @var AdminPageBlockFactory */
	private $adminPageBlockFactory;

	/** @var FrontendPageBlockFactory */
	private $frontendPageBlockFactory;

	/** @var WebImageRepository */
	private $webImageRepository;



	public function injectAdminPageBlock(AdminPageBlockFactory $adminPageBlockFactory, FrontendPageBlockFactory $frontendPageBlockFactory, WebImageRepository $codeShareRepository)
	{
		$this->adminPageBlockFactory = $adminPageBlockFactory;
		$this->webImageRepository = $codeShareRepository;
		$this->frontendPageBlockFactory = $frontendPageBlockFactory;
	}



	public static function getTypeStatic(): string
	{
		return self::TYPE_WEB_IMAGE;
	}



	public static function createForPage(Page $page): IPageBlock
	{
		$block = new WebImagePageBlock();
		$block->setPriority($page->getNextBlockPriority());
		$block->setPage($page);
		$block->setType(self::TYPE_WEB_IMAGE);

		return $block;
	}



	public function getAdminComponent(): Control
	{
		return $this->adminPageBlockFactory->create($this);
	}



	public function getWebImage(): ?WebImage
	{

		if ($this->data and $this->data->webImage) {
			return $this->webImageRepository->getWebImageById($this->data->webImage);
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
