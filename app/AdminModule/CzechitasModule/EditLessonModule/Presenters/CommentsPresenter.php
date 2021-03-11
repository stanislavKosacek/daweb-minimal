<?php


namespace App\AdminModule\CzechitasModule\EditLessonModule\Presenters;


use App\AdminModule\CzechitasModule\Modal\Lesson\EditLessonModalFactory;
use App\AdminModule\Modal\AddPageBlockModalFactory;
use App\AdminModule\Presenters\SecuredPresenter;
use App\AdminModule\SettingsModule\Components\SetTargetFormFactory;
use App\Model\Czechitas\Lesson\Lesson;
use App\Model\Czechitas\Lesson\LessonRepository;
use App\Model\Helpers\Comments\CommentProviderFactory;
use App\Model\Page\PageBlock\PageBlock;
use App\Model\Page\PageBlock\PageBlockRepository;
use Nette\Application\BadRequestException;
use Nette\Application\UI\Multiplier;
use Nextras\Dbal\Utils\DateTimeImmutable;

class CommentsPresenter extends SecuredPresenter
{

	/** @var LessonRepository @autowire */
	protected $lessonRepository;

	/** @var Lesson */
	protected $selectedLesson;

	/** @var CommentProviderFactory @autowire */
	protected $commentProviderFactory;



	public function actionComments($id)
	{
		if (!$id or !$this->selectedLesson = $this->lessonRepository->getLessonById($id)) {
			throw new BadRequestException();
		}

		$this->template->selectedLesson = $this->selectedLesson;
	}


	public function createComponentComments()
	{
		return $this->commentProviderFactory->create($this->selectedLesson->getPage())->getAdminComponent();
	}
}
