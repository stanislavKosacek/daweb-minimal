<?php


namespace App\AdminModule\Presenters;



use App\Model\Czechitas\Lesson\Lesson;
use App\Model\Czechitas\Lesson\LessonRepository;

class HomepagePresenter extends SecuredPresenter
{

	/** @var LessonRepository @autowire */
	protected $lessonRepository;



	public function actionDefault()
	{
		$this->redirect(":Admin:Czechitas:Lesson:default");
	}



	public function renderDefault()
	{
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
//				"lessonDetail" => $this->link(":Admin:Czechitas:EditLesson:EditLesson:default", ["id" => $lesson->getId()]),
//			];
//		}
//
//		$this->template->calendarEvents = $calendarEvents;

	}
}
