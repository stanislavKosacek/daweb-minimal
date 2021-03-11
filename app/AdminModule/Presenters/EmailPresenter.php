<?php


namespace App\AdminModule\Presenters;



use App\AdminModule\Grid\EmailGridFactory;
use App\AdminModule\Modal\SendEmailModalFactory;
use App\AdminModule\Modal\ShowEmailModalFactory;
use App\Model\Email\Email\Email;
use App\Model\Email\Email\EmailRepository;
use App\Model\Email\EmailType\EmptyEmail\EmptyEmail;
use App\Model\Email\SendEmailFactory;
use Nette\Application\BadRequestException;
use Nette\Application\Responses\TextResponse;

class EmailPresenter extends SecuredPresenter
{

	/** @var SendEmailFactory @autowire */
	protected $sendEmailFactory;

	/** @var EmailGridFactory @autowire */
	protected $emailGridFactory;

	/** @var Email */
	protected $selectedEmail;

	/** @var EmailRepository @autowire */
	protected $emailRepository;

	/** @var ShowEmailModalFactory @autowire */
	protected $showEmailModalFactory;

	/** @var SendEmailModalFactory @autowire */
	protected $sendEmailModalFactory;



	public function renderDefault()
	{

	}



	public function actionDetail($id)
	{
		if (!$id or !$this->selectedEmail = $this->emailRepository->getEmailById($id)) {
			throw new BadRequestException();
		}
		$modal = $this->showEmailModalFactory->create($this->selectedEmail);
		$modal->addClass("modal-xl");

		$this->raiseModal($modal, "showEmail", "default");

	}



	public function actionSendEmailAgain($id)
	{
		if (!$id or !$this->selectedEmail = $this->emailRepository->getEmailById($id)) {
			throw new BadRequestException();
		}

		$this->sendEmailFactory->create($this->selectedEmail)->sendEmail();

		$this->redirect("default");
	}



	public function actionRemoveEmail($id)
	{
		if (!$id or !$this->selectedEmail = $this->emailRepository->getEmailById($id)) {
			throw new BadRequestException();
		}

		$this->orm->removeAndFlush($this->selectedEmail);

		$this->redirect("default");
	}



	public function actionShowEmailBody(int $id)
	{
		if (!$id or !$this->selectedEmail = $this->emailRepository->getEmailById($id)) {
			throw new BadRequestException();
		}

		$this->sendResponse(new TextResponse($this->selectedEmail->getBody()));
	}



	public function actionSendEmail()
	{
		$modal = $this->sendEmailModalFactory->create();
		$modal->addClass("modal-xl");

		$this->raiseModal($modal, "sendEmail", "default");
	}



	public function createComponentEmailGrid()
	{
		$grid = $this->emailGridFactory->create();

		return $grid->getGrid();
	}
}
