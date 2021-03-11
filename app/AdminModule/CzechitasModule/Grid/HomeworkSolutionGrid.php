<?php


namespace App\AdminModule\CzechitasModule\Grid;



use App\Model\Czechitas\HomeworkAssignment\HomeworkAssignment;
use App\Model\Czechitas\HomeworkAssignment\HomeworkAssignmentRepository;
use App\Model\Czechitas\HomeworkSolution\HomeworkSolution;
use App\Model\Czechitas\HomeworkSolution\HomeworkSolutionRepository;
use App\Model\Grid\BaseGridFactory;
use App\Model\Orm;
use App\Model\User\User\User;
use App\Model\User\User\UserRepository;
use Nette\Application\LinkGenerator;

interface HomeworkSolutionGridFactory
{

	public function create(HomeworkAssignment $homeworkAssignment): HomeworkSolutionGrid;

}



class HomeworkSolutionGrid
{

	/** @var HomeworkAssignment */
	private $homeworkAssignment;

	/** @var BaseGridFactory */
	private $baseGridFactory;

	/** @var LinkGenerator */
	private $linkGenerator;

	/** @var UserRepository */
	private $userRepository;

	/** @var HomeworkSolutionRepository */
	private $homeworkSolutionRepository;

	/** @var Orm */
	private $orm;



	public function __construct(HomeworkAssignment $homeworkAssignment,
		BaseGridFactory $baseGridFactory,
		UserRepository $userRepository,
		LinkGenerator $linkGenerator,
		HomeworkSolutionRepository $homeworkSolutionRepository,
		Orm $orm)
	{
		$this->homeworkAssignment = $homeworkAssignment;
		$this->baseGridFactory = $baseGridFactory;
		$this->linkGenerator = $linkGenerator;
		$this->userRepository = $userRepository;
		$this->homeworkSolutionRepository = $homeworkSolutionRepository;
		$this->orm = $orm;
	}



	public function getGrid()
	{
		$grid = $this->baseGridFactory->create("Oprava úkolů");
		//$grid->setOuterFilterRendering(TRUE);
		$grid->setDefaultPerPage(20);
		$grid->setAutoSubmit(TRUE);
		$grid->setDataSource($this->userRepository->findUsersByRole("member"));
		$grid->setDefaultSort(["surname" => "ASC"], TRUE);
		$grid->setStrictSessionFilterValues(FALSE);


		$grid->addColumnImage("Účastnice");
		$grid->addColumnText('name', 'Jméno')->setSortable()->setRenderer(function (User $user) {
			return $user->getSurname() . " " . $user->getName();
		});

		$grid->addColumnText('github', 'GitHub')->setSortable()->setRenderer(function (User $user) {
			if ($user->getGithubHomeworkUrl()) {
				$url = $user->getGithubHomeworkUrl() . "/tree/main/" . $this->homeworkAssignment->getGitFolder();

				return '<a href="' . $url . '" target="_blank">' . $url . '</a>';
			}

			return NULL;
		})->setTemplateEscaping(FALSE);

		$grid->addColumnText('netlify', 'Netlify')->setSortable()->setRenderer(function (User $user) {
			if ($user->getGithubHomeworkUrl()) {
				$url = $user->getBaseNetlifyUrl() . "/" . $this->homeworkAssignment->getGitFolder();

				return '<a href="' . $url . '" target="_blank">' . $url . '</a>';
			}

			return NULL;
		})->setTemplateEscaping(FALSE);


		$grid->addColumnText('state', 'Stav')->setSortable()->setRenderer(function (User $user) {
			$homeworkSolution = $this->homeworkSolutionRepository->getByUserAndHomeworkAssignment($user, $this->homeworkAssignment);

			if (!$homeworkSolution) {
				$homeworkSolution = new HomeworkSolution();
				$homeworkSolution->setUser($user);
				$homeworkSolution->setHomeworkAssignment($this->homeworkAssignment);
				$homeworkSolution = $this->orm->persistAndFlush($homeworkSolution);
			}

			$icon = "";

			if ($homeworkSolution->getState() == HomeworkSolution::STATE_UNDELIVERED) {
				$icon = '<span class="badge bg-danger"><i class="fa fa-times"></i></span>';
			} elseif ($homeworkSolution->getState() == HomeworkSolution::STATE_WAITING) {
				$icon = '<span class="badge bg-info"><i class="fa fa-clock-o"></i></span>';
			} elseif ($homeworkSolution->getState() == HomeworkSolution::STATE_OK) {
				$icon = '<span class="badge bg-success"><i class="fa fa-check"></i></span>';
			} elseif ($homeworkSolution->getState() == HomeworkSolution::STATE_WRONG) {
				$icon = '<span class="badge bg-warning"><i class="fa fa-warning"></i></span>';
			}

			return $icon . " " . $homeworkSolution->getTranslatedState();
		})->setTemplateEscaping(FALSE)
			 ->setEditableInputTypeSelect(HomeworkSolution::getStates())
			 ->setEditableCallback([$this, 'inlineEditState']);


		$grid->addColumnText("comments", "Komentáře")->setRenderer(function (User $user) {
			$homeworkSolution = $this->homeworkSolutionRepository->getByUserAndHomeworkAssignment($user, $this->homeworkAssignment);

			if (!$homeworkSolution) {
				$homeworkSolution = new HomeworkSolution();
				$homeworkSolution->setUser($user);
				$homeworkSolution->setHomeworkAssignment($this->homeworkAssignment);
				$homeworkSolution = $this->orm->persistAndFlush($homeworkSolution);
			}

			$button = '<a href="' . $this->linkGenerator->link("Admin:Czechitas:HomeworkRepair:comments", [$homeworkSolution->getId()]) . '" class="btn btn-primary">Komentáře <span class="label label-default">' . $homeworkSolution->getComments()->count(). '</span></a>';

			return $button;
		})->setTemplateEscaping(FALSE);
//		$grid->addAction(":Admin:Czechitas:EditLesson:EditLesson:default", "")->setIcon("search")->setClass("btn btn-sm btn-primary");

		return $grid;
	}



	public function inlineEditState($id, string $value)
	{

		$id = (int) $id;
		if (is_numeric($id)) {
			$user = $this->userRepository->getUserById($id);
			if ($solution = $this->homeworkSolutionRepository->getByUserAndHomeworkAssignment($user, $this->homeworkAssignment)) {
				$solution->setState($value);
				$this->orm->persistAndFlush($solution);
			}
		}
	}

}
