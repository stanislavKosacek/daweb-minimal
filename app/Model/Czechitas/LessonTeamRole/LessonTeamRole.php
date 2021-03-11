<?php

namespace App\Model\Czechitas\LessonTeamRole;

use App\Model\Czechitas\Lesson\Lesson;
use App\Model\User\User\User;
use Nextras\Dbal\Utils\DateTimeImmutable;
use Nextras\Orm\Entity\Entity;
use Nextras\Orm\Relationships\ManyHasMany;


/**
 * LessonTeamRole
 * @property int $id    {primary}
 * @property User $user  {1:1 User, isMain=true, oneSided=true}
 * @property string $type {enum self::TYPE_*}
 * @property Lesson $lesson {m:1 Lesson::$teamRoles}
 */
class LessonTeamRole extends Entity
{

	const TYPE_LECTURER = 'lecturer';
	const TYPE_COACH = 'coach';
	const TYPE_WORKSHOPIST = 'workshopist';



	/**
	 * @return int
	 */
	public function getId(): int
	{
		return $this->id;
	}



	/**
	 * @return User
	 */
	public function getUser(): User
	{
		return $this->user;
	}



	/**
	 * @param User $user
	 */
	public function setUser(User $user): void
	{
		$this->user = $user;
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
	 * @return Lesson
	 */
	public function getLesson(): Lesson
	{
		return $this->lesson;
	}



	/**
	 * @param Lesson $lesson
	 */
	public function setLesson(Lesson $lesson): void
	{
		$this->lesson = $lesson;
	}



	public function getTranslatedType()
	{
		return self::getTypes()[$this->getType()];

	}



	public static function getTypes(): array
	{
		return [
			LessonTeamRole::TYPE_LECTURER => "Lektor",
			LessonTeamRole::TYPE_COACH => "Kouč",
			LessonTeamRole::TYPE_WORKSHOPIST => "Workshopistka",
		];
	}

}
