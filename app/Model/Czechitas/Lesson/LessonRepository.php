<?php

namespace App\Model\Czechitas\Lesson;

use Nextras\Orm\Collection\ICollection;
use Nextras\Orm\Repository\Repository;


/**
 * @method Lesson|NULL getById($id)
 */
class LessonRepository extends Repository
{

	static function getEntityClassNames(): array
	{
		return [Lesson::class];
	}



	/**
	 * @param $id
	 * @return Lesson|null
	 */
	public function getLessonById($id): ?Lesson
	{
		return $this->getById($id);
	}



	/**
	 * @return Lesson[]|ICollection
	 */
	public function getLessonList(): ICollection
	{
		return $this->findAll()->orderBy("dateStart", "ASC");
	}


	/**
	 * @return Lesson[]|ICollection
	 */
	public function findPublishedLessonsByDateStart(): ICollection
	{
		return $this->findBy(["page->published<" => new \DateTimeImmutable()])->orderBy("dateStart", "ASC");
	}

}
