<?php


namespace App\AdminModule\SettingsModule\Grid;



use App\Model\Grid\BaseGridFactory;
use App\Model\User\Role\RoleRepository;
use App\Model\User\User\User;
use App\Model\User\User\UserRepository;
use Nextras\Orm\Collection\DbalCollection;

interface UserGridFactory {

    public function create(): UserGrid;

}

class UserGrid
{

	/**
	 * @var BaseGridFactory
	 */
	private $baseGridFactory;

	/**
	 * @var UserRepository
	 */
	private $userRepository;

	/**
	 * @var RoleRepository
	 */
	private $roleRepository;



	public function __construct(BaseGridFactory $baseGridFactory, UserRepository $userRepository, RoleRepository $roleRepository)
	{

		$this->baseGridFactory = $baseGridFactory;
		$this->userRepository = $userRepository;
		$this->roleRepository = $roleRepository;
	}



	public function getGrid()
	{
		$grid = $this->baseGridFactory->create("Uživatele");
		$grid->setOuterFilterRendering(TRUE);
		$grid->setDefaultPerPage(20);
		$grid->setAutoSubmit(TRUE);
		$grid->setDataSource($this->userRepository->findAll());
		$grid->setStrictSessionFilterValues(FALSE);
		$grid->addColumnImage("Náhled");
		$grid->addColumnText('surname', 'Příjmení')->setSortable();
		$grid->addColumnText('name', 'Jméno')->setSortable();
		$grid->addColumnText("email", "Email")->setSortable();
		$grid->addColumnText('username', 'Uživatelské jméno')->setSortable();
		$grid->addColumnText("role", "Role")->setRenderer(function (User $user) {
			$roles = [];
			foreach ($user->getRolesEntity() as $role) {
				$roles[] = $role->getNameCs();
			}
			return implode(", ", $roles);
		});
		$grid->addColumnDateTime("added", "Datum registrace")
			 ->setSortable()
			 ->setFormat("j. n. Y H:i");
		$grid->addAction("edit", "")->setIcon("pencil")->setClass("btn btn-sm btn-primary ajax");
		$grid->addAction("delete", "")->setIcon("trash")->setClass("btn btn-sm btn-danger");

		$roles = ['' => "vše"];
		foreach ($this->roleRepository->findRoleList() as $role) {
			$roles[$role->getNameCs()] = $role->getNameCs();
		}
		$grid->addFilterText("name", "Vyhledat", ["user.username", "user.email", "user.name", "user.surname"]);
		$grid->addFilterSelect("Typ", "Typ", $roles)->setCondition(function (DbalCollection $collection, $value) {
			$collection->getQueryBuilder()->joinLeft("[user_x_role] AS [uxr]", "[user.id] = [uxr.user_id]")
					   ->joinLeft("[role] AS [ur]", "[uxr.role_id] = [ur.id]")->andWhere("ur.name_cs = %s", $value);

		})->setAttribute("data-grid", "true");
		return $grid;
	}

}
