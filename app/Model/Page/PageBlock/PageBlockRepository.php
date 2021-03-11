<?php

namespace App\Model\Page\PageBlock;

use App\Model\Page\PageBlock\Types\CodeSharePageBlock;
use App\Model\Page\PageBlock\Types\ExercisePageBlock;
use App\Model\Page\PageBlock\Types\TextPageBlock;
use App\Model\Page\PageBlock\Types\WebImagePageBlock;
use App\Model\Page\PageBlock\Types\YoutubePageBlock;
use Nextras\Orm\Repository\Repository;


/**
 * @method PageBlock|NULL getById($id)
 */
class PageBlockRepository extends Repository
{


	public static function getEntityClassNames(): array
	{
		return [PageBlock::class, TextPageBlock::class, CodeSharePageBlock::class, YoutubePageBlock::class, WebImagePageBlock::class, ExercisePageBlock::class];
	}



	public function getEntityClassName(array $data): string
	{
		if ($data["type"] == PageBlock::TYPE_TEXT) {
			return TextPageBlock::class;
		} elseif ($data["type"] == PageBlock::TYPE_CODE_SHARE) {
			return CodeSharePageBlock::class;
		} elseif ($data["type"] == PageBlock::TYPE_YOUTUBE) {
			return YoutubePageBlock::class;
		} elseif ($data["type"] == PageBlock::TYPE_WEB_IMAGE) {
			return WebImagePageBlock::class;
		} elseif ($data["type"] == PageBlock::TYPE_EXERCISE) {
			return ExercisePageBlock::class;
		}

		return PageBlock::class;
	}



	public function getPageBlockById($id): ?PageBlock
	{
		return $this->getById($id);
	}



	public function getPageBlockList()
	{
		return $this->findAll();
	}

}
