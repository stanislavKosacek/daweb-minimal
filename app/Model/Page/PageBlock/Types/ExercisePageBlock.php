<?php


namespace App\Model\Page\PageBlock\Types;


use App\Model\Czechitas\Exercise\Exercise;
use App\Model\Czechitas\Exercise\ExerciseRepository;
use App\Model\Page\Page\Page;
use App\Model\Page\PageBlock\PageBlock;
use App\Model\Page\PageBlock\Types\Helpers\Exercise\AdminPageBlockFactory;
use App\Model\Page\PageBlock\Types\Helpers\Exercise\FrontendPageBlockFactory;
use Nette\Application\UI\Control;

class ExercisePageBlock extends PageBlock implements IPageBlock
{

	const TRANSLATED_NAME = "Cvičení";

	/** @var AdminPageBlockFactory */
	private $adminPageBlockFactory;

	/** @var FrontendPageBlockFactory @autowire */
	protected $frontendPageBlockFactory;

	/** @var ExerciseRepository @autowire */
	protected $exerciseRepository;



	public function injectAdminPageBlock(AdminPageBlockFactory $adminPageBlockFactory, FrontendPageBlockFactory $frontendPageBlockFactory, ExerciseRepository $exerciseRepository)
	{
		$this->adminPageBlockFactory = $adminPageBlockFactory;
		$this->frontendPageBlockFactory = $frontendPageBlockFactory;
		$this->exerciseRepository = $exerciseRepository;
	}



	public static function getTypeStatic(): string
	{
		return self::TYPE_EXERCISE;
	}



	public static function createForPage(Page $page): IPageBlock
	{
		$block = new ExercisePageBlock();
		$block->setPriority($page->getNextBlockPriority());
		$block->setPage($page);
		$block->setType(self::TYPE_EXERCISE);

		return $block;
	}



	public function getAdminComponent(): Control
	{
		return $this->adminPageBlockFactory->create($this);
	}



	public function getExercise(): ?Exercise
	{

		if ($this->data and $this->data->exerciseId) {
			return $this->exerciseRepository->getExerciseById($this->data->exerciseId);
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
