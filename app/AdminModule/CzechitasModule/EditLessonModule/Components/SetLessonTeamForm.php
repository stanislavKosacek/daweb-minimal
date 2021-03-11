<?php


namespace App\AdminModule\CzechitasModule\EditLessonModule\Components;


use App\Model\Czechitas\Lesson\Lesson;
use App\Model\Czechitas\LessonTeamRole\LessonTeamRole;
use App\Model\Form\BaseForm;
use App\Model\Form\BaseFormFactory;
use App\Model\Orm;
use App\Model\User\User\UserRepository;
use Nette\SmartObject;

interface SetLessonTeamFormFactory
{

	public function create(Lesson $lesson): SetLessonTeamForm;

}



class SetLessonTeamForm
{

	use SmartObject;

	/** @var Lesson */
	private $lesson;

	/** @var UserRepository */
	private $userRepository;

	/** @var BaseFormFactory */
	private $baseFormFactory;

	/** @var array */
	public $onSuccess = [];

	/** @var Orm */
	private $orm;



	public function __construct(Lesson $lesson, UserRepository $userRepository, BaseFormFactory $baseFormFactory, Orm $orm)
	{
		$this->lesson = $lesson;
		$this->userRepository = $userRepository;
		$this->baseFormFactory = $baseFormFactory;
		$this->orm = $orm;
	}



	public function getForm()
	{

		$admins = $this->userRepository->findAdmins();

		$team = [];
		foreach ($admins as $admin) {
			$team[$admin->getId()] = $admin->getSurname() . " " . $admin->getName();
		}

		$form = $this->baseFormFactory->create();
		$form->addMultiSelect("lecturers", "Lektoř:", $team);
		$form->addMultiSelect("coaches", "Kouči:", $team);
		$form->addMultiSelect("workshopists", "Worksopistky:", $team);

		$form->addSubmitButton("semd", "Upravit");

		$lecturers = [];
		$coaches = [];
		$workshopists = [];

		foreach ($this->lesson->getTeamRoles() as $teamRole) {
			if ($teamRole->getType() == LessonTeamRole::TYPE_LECTURER) {
				$lecturers[] = $teamRole->getUser()->getId();
			} elseif ($teamRole->getType() == LessonTeamRole::TYPE_COACH) {
				$coaches[] = $teamRole->getUser()->getId();
			} elseif ($teamRole->getType() == LessonTeamRole::TYPE_WORKSHOPIST) {
				$workshopists[] = $teamRole->getUser()->getId();
			}
		}

		$form->setDefaults([
			"lecturers" => $lecturers,
			"coaches" => $coaches,
			"workshopists" => $workshopists,
		]);

		$form->onSuccess[] = [$this, "processForm"];

		return $form;
	}



	public function processForm(BaseForm $form)
	{
		$values = $form->getValues();

		foreach ($this->lesson->getTeamRoles() as $role) {
			$this->orm->removeAndFlush($role);
		}

		foreach ($values->lecturers as $userId) {
			$user = $this->userRepository->getUserById($userId);
			$role = new LessonTeamRole();
			$role->setUser($user);
			$role->setLesson($this->lesson);
			$role->setType(LessonTeamRole::TYPE_LECTURER);

			$this->orm->persist($role);
		}

		foreach ($values->coaches as $userId) {
			$user = $this->userRepository->getUserById($userId);
			$role = new LessonTeamRole();
			$role->setUser($user);
			$role->setLesson($this->lesson);
			$role->setType(LessonTeamRole::TYPE_COACH);

			$this->orm->persist($role);
		}

		foreach ($values->workshopists as $userId) {
			$user = $this->userRepository->getUserById($userId);
			$role = new LessonTeamRole();
			$role->setUser($user);
			$role->setLesson($this->lesson);
			$role->setType(LessonTeamRole::TYPE_WORKSHOPIST);

			$this->orm->persist($role);
		}

		$this->orm->flush();

		$this->onSuccess();
	}

}
