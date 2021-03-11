<?php

namespace App\Model\Czechitas\LessonFile;

use Nextras\Orm\Collection\ICollection;
use Nextras\Orm\Repository\Repository;


/**
 * @method LessonFile|NULL getById($id)
 */
class LessonFileRepository extends Repository
{

	static function getEntityClassNames(): array
	{
		return [LessonFile::class];
	}



	/**
	 * @param $id
	 * @return LessonFile|null
	 */
	public function getLessonFileById($id): ?LessonFile
	{
		return $this->getById($id);
	}



	/**
	 * @return LessonFile[]|ICollection
	 */
	public function getLessonFileList(): ICollection
	{
		return $this->findAll();
	}

}
