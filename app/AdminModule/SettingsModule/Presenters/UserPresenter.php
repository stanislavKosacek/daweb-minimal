<?php


namespace App\AdminModule\SettingsModule\Presenters;


use App\AdminModule\Modal\ConfirmModalFactory;
use App\AdminModule\Presenters\SecuredPresenter;
use App\AdminModule\SettingsModule\Grid\UserGridFactory;
use App\AdminModule\SettingsModule\Modal\SetUserModalFactory;
use App\Model\User\User\User;
use App\Model\User\User\UserRepository;
use Nette\Application\BadRequestException;
use Nette\Database\ForeignKeyConstraintViolationException;
use Nette\InvalidArgumentException;

class UserPresenter extends SecuredPresenter
{

	/** @var UserRepository @autowire */
	protected $userRepository;

	/** @var User */
	protected $selectedUser;

	/** @var SetUserModalFactory @autowire */
	protected $setUserModalFactory;

	/** @var UserGridFactory @autowire */
	protected $userGridFactory;

	/** @var ConfirmModalFactory @autowire */
	protected $confirmModalFactory;



	public function renderDefault()
	{

	}



	public function createComponentUserGrid()
	{
		$grid = $this->userGridFactory->create();

		return $grid->getGrid();
	}



	public function actionAdd()
	{

		$modal = $this->setUserModalFactory->create();
		$this->raiseModal($modal, 'addUserModal', 'default', [], "default");

	}



	public function actionEdit($id)
	{
		if (!$id or !$this->selectedUser = $this->userRepository->getUserById($id)) {
			throw new BadRequestException();
		}

		$modal = $this->setUserModalFactory->create($this->selectedUser);
		$this->raiseModal($modal, 'editUserModal', 'default', []);
	}


	public function actionDelete($id)
	{
		if (!$id or !$this->selectedUser = $this->userRepository->getUserById($id)) {
			throw new BadRequestException();
		}
		$confirmation = $this->confirmModalFactory->create("deleteUser");
		$confirmation->onConfirmed[] = function ($confirmed) {
			if ($confirmed) {
				try {
					if ($this->selectedUser->getImage()) {
						$this->imageStorage->delete($this->selectedUser->getImage());
					}
					$this->userRepository->removeAndFlush($this->selectedUser);
					$this->flashMessage("admin.modal.flashes.userRemoved", "success");
				} catch (InvalidArgumentException $e) {
					$this->flashMessage("admin.modal.flashes.userRemoveFailed", "error");
				} catch (ForeignKeyConstraintViolationException $e) {
					$this->flashMessage("admin.modal.flashes.userRemoveFailed", "error");
				}
			}
			$this->moveTo("default");
		};
		$confirmation->closeWithoutRedraw(FALSE);
		$this->raiseModal($confirmation, "deleteUser", "default");

	}
}
