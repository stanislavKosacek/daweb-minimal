<?php

namespace App\Model\Page\PageBlock;

use App\Model\Page\Page\Page;
use Nette\Utils\ArrayHash;
use Nextras\Orm\Entity\Entity;
use Nextras\Orm\Relationships\ManyHasOne;


/**
 * PageBlock
 * @property int $id    {primary}
 * @property string $type {enum self::TYPE_*}
 * @property int|null $relatedId
 * @property int $priority
 * @property ArrayHash|null $data {wrapper JsonContainer}
 * @property ManyHasOne|Page $page    {m:1 Page::$pageBlocks}
 */
abstract class PageBlock extends Entity
{

	const TYPE_TEXT = 'text';
	const TYPE_CODE_SHARE = 'codeShare';
	const TYPE_YOUTUBE = 'youtube';
	const TYPE_WEB_IMAGE = 'image';
	const TYPE_EXERCISE = 'exercise';



	/**
	 * @return int
	 */
	public function getId(): int
	{
		return $this->id;
	}



	/**
	 * @return string
	 */
	public function getType(): string
	{
		return $this->type;
	}



	/**
	 * @param string $type
	 */
	public function setType(string $type): void
	{
		$this->type = $type;
	}



	/**
	 * @return int
	 */
	public function getPriority(): int
	{
		return $this->priority;
	}



	/**
	 * @param int $priority
	 */
	public function setPriority(int $priority): void
	{
		$this->priority = $priority;
	}



	/**
	 * @return Page|ManyHasOne
	 */
	public function getPage()
	{
		return $this->page;
	}



	/**
	 * @param Page|ManyHasOne $page
	 */
	public function setPage($page): void
	{
		$this->page = $page;
	}



	/**
	 * @return int|null
	 */
	public function getRelatedId(): ?int
	{
		return $this->relatedId;
	}



	/**
	 * @param int|null $relatedId
	 */
	public function setRelatedId(?int $relatedId): void
	{
		$this->relatedId = $relatedId;
	}
}
