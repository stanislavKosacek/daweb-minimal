<?php

declare(strict_types=1);

namespace App\Presenters;



use App\Model\Czechitas\Exercise\Exercise;
use App\Model\Czechitas\Exercise\ExerciseRepository;
use App\Model\Czechitas\Lesson\Lesson;
use App\Model\Czechitas\Lesson\LessonRepository;
use App\Model\Helpers\Event\TestEvent;
use Nette\Utils\Json;
use Nextras\Orm\Collection\Expression\LikeExpression;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

final class LessonPresenter extends BasePresenter
{

	/** @var LessonRepository @autowire */
	protected $lessonRepository;



	public function renderDefault()
	{
		$this->template->lessons = $this->lessonRepository->findPublishedLessonsByDateStart();

	}



}
