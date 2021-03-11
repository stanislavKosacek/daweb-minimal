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

final class HomepagePresenter extends BasePresenter
{

	/** @var EventDispatcherInterface @autowire */
	protected $eventDispatcherInterface;

	/** @var LessonRepository @autowire */
	protected $lessonRepository;

	/** @var ExerciseRepository @autowire */
	protected $exerciseRepository;

	/** @var Exercise[] */
	protected $exercises;



	public function renderDefault()
	{
		if (!$this->exercises) {
			$this->exercises = $this->exerciseRepository->findPublished();
		}

		$this->template->exercises = $this->exercises;
		if ($this->isAjax()) {
			$this->redrawControl(NULL, FALSE);
			$this->redrawControl("exercises");
		}
		//$this->eventDispatcherInterface->dispatch(new TestEvent("ahoj"), HomepagePresenter::class);
//		$calendarEvents = [];
//		foreach ($this->lessonRepository->getLessonList() as $lesson) {
//			if ($lesson->getType() == Lesson::TYPE_JS) {
//				$bgcolor = "#F0DB46";
//			} elseif ($lesson->getType() == Lesson::TYPE_HTML) {
//				$bgcolor = "#E34C26";
//			} else {
//				$bgcolor = "#E5007D";
//			}
//
//			$calendarEvents[] = [
//				"title" => $lesson->getPage()->getName(),
//				"start" => $lesson->getDateStart()->format(\DateTimeImmutable::RFC3339),
//				"end" => $lesson->getDateEnd()->format(\DateTimeImmutable::RFC3339),
//				"backgroundColor" => $bgcolor,
//				"lessonDetail" => $lesson->getPage()->isPublished() ? $this->link("Page:default", ["id" => $lesson->getPage()->getId()]) : NULL,
//				"description" => !$lesson->getPage()->isPublished() ? "Není publikována! " . $lesson->getPage()->getName() : $lesson->getPage()->getName(),
//			];
//		}

		$this->template->lessons = $this->lessonRepository->findPublishedLessonsByDateStart();
//		$this->template->calendarEvents = $calendarEvents;

	}



	public function handleFindExercises($find = "")
	{
		$this->exercises = $this->exerciseRepository->findPublished();
		$this->exercises = $this->exercises->findBy(['name~' => LikeExpression::contains($find)]);
		$this->redrawControl(NULL, FALSE);
		$this->redrawControl("exercises");
		//$this->redrawControl("scripts");
	}



}
