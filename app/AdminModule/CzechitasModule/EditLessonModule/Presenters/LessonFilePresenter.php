<?php


namespace App\AdminModule\CzechitasModule\EditLessonModule\Presenters;


use App\AdminModule\CzechitasModule\EditLessonModule\Modal\SetLessonFileModalFactory;
use App\AdminModule\CzechitasModule\EditLessonModule\Modal\SetLessonTeamModalFactory;
use App\AdminModule\CzechitasModule\Modal\Lesson\EditLessonModalFactory;
use App\AdminModule\Modal\AddPageBlockModalFactory;
use App\AdminModule\Presenters\SecuredPresenter;
use App\AdminModule\SettingsModule\Components\SetTargetFormFactory;
use App\Model\Czechitas\Lesson\Lesson;
use App\Model\Czechitas\Lesson\LessonRepository;
use App\Model\Czechitas\LessonFile\LessonFile;
use App\Model\Czechitas\LessonFile\LessonFileRepository;
use App\Model\Czechitas\LessonTeamRole\LessonTeamRoleRepository;
use App\Model\Helpers\Storage\LessonFileStorage;
use App\Model\Page\PageBlock\PageBlock;
use App\Model\Page\PageBlock\PageBlockRepository;
use Nette\Application\BadRequestException;
use Nette\Application\UI\Multiplier;
use Nextras\Dbal\Utils\DateTimeImmutable;

class LessonFilePresenter extends SecuredPresenter
{

	/** @var LessonRepository @autowire */
	protected $lessonRepository;

	/** @var Lesson */
	protected $selectedLesson;

	/** @var SetLessonFileModalFactory @autowire */
	protected $setLessonFileModalFactory;

	/** @var LessonFileRepository @autowire */
	protected $lessonFileRepository;

	/** @var LessonFile */
	protected $selectedLessonFile;

	/** @var LessonFileStorage @autowire */
	protected $lessonFileStorage;



	public function actionDefault($id)
	{
		if (!$id or !$this->selectedLesson = $this->lessonRepository->getLessonById($id)) {
			throw new BadRequestException();
		}

		$this->template->selectedLesson = $this->selectedLesson;
	}



	public function actionAddFile($id)
	{
		if (!$id or !$this->selectedLesson = $this->lessonRepository->getLessonById($id)) {
			throw new BadRequestException();
		}

		$modal = $this->setLessonFileModalFactory->create($this->selectedLesson);
		$this->actionDefault($id);

		$this->raiseModal($modal, "addLessonFile", "default", [$id], "default");
	}



	public function actionEditFile($id, $fileId)
	{
		if (
			!$id or
			!$fileId or
			!$this->selectedLesson = $this->lessonRepository->getLessonById($id) or
			!$this->selectedLessonFile = $this->lessonFileRepository->getLessonFileById($fileId)
		) {
			throw new BadRequestException();
		}

		$modal = $this->setLessonFileModalFactory->create($this->selectedLesson, $this->selectedLessonFile);
		$this->actionDefault($id);

		$this->raiseModal($modal, "editLessonFile", "default", [$id], "default");
	}



	public function handleDeleteLessonFile($fileId)
	{
		$file = $this->lessonFileRepository->getLessonFileById($fileId);
		if ($file) {
			$this->lessonFileStorage->deleteFile($file->getFilename());
			$this->orm->removeAndFlush($file);
		}

		$this->redirect("this");
	}
}
