<?php


namespace App\AdminModule\Grid;



use App\Model\Email\Email\Email;
use App\Model\Email\Email\EmailRepository;
use App\Model\Email\EmailType\EmailTypeProvider;
use App\Model\Grid\BaseGridFactory;

interface EmailGridFactory
{

	public function create(): EmailGrid;

}



class EmailGrid
{

	/**
	 * @var BaseGridFactory
	 */
	private $baseGridFactory;

	/** @var EmailRepository */
	private $emailRepository;

	/** @var EmailTypeProvider */
	private $emailTypeProvider;



	public function __construct(BaseGridFactory $baseGridFactory, EmailRepository $emailRepository, EmailTypeProvider $emailTypeProvider)
	{
		$this->baseGridFactory = $baseGridFactory;
		$this->emailRepository = $emailRepository;
		$this->emailTypeProvider = $emailTypeProvider;
	}



	public function getGrid()
	{
		$grid = $this->baseGridFactory->create("Emaily");
		//$grid->setOuterFilterRendering(TRUE);
		$grid->setDefaultPerPage(20);
		$grid->setAutoSubmit(TRUE);
		$grid->setDataSource($this->emailRepository->findAll());
		$grid->setDefaultSort(["added" => "DESC"], TRUE);
		$grid->setStrictSessionFilterValues(FALSE);

		$grid->addColumnText("state", "Stav")->setRenderer(function (Email $email) {

			if ($email->getSent()) {
				return "<span class='label label-primary' data-toggle='tooltip' data-placement='top' title='' data-original-title='Odesláno'><i class='fa fa-check-circle'></i></span>";
			} else {
				if ($email->isError()) {
					return "<span class='label label-danger' data-toggle='tooltip' data-placement='top' title='' data-original-title='" . $email->getErrorMessage() . "'>
								<i class='fa fa-times-circle'></i>
							</span>";
				} else {
					return "<span class='label label-info' data-toggle='tooltip' data-placement='top' title='' data-original-title='Odesílání'><i class='fa fa-clock-o'></i></span>";
				}

			}
		})->setTemplateEscaping(FALSE);

		$grid->addColumnText('emailType', 'Typ emailu')->setSortable()->setRenderer(function (Email $email) {
			$type = $this->emailTypeProvider->getEmailTypeTranslatedName($email->getEmailType());

			return $type ?? $email->getEmailType();
		});
		$grid->addColumnText('to', 'Příjemce')->setSortable();
		$grid->addColumnText('subject', 'Předmět')->setSortable();
		$grid->addColumnDateTime('added', 'Vytvořeno')
			 ->setSortable()
			 ->setFormat("j. n. Y H:i");
		$grid->addColumnDateTime('sent', 'Odesláno')
			 ->setSortable()
			 ->setFormat("j. n. Y H:i");


		$grid->addAction("detail", "")->setIcon("search")->setClass("btn btn-sm btn-primary")->addAttributes(["data-toggle" => "tooltip", "title" => "Zobrazit email"]);
		$grid->addAction("sendEmailAgain", "")->setIcon("paper-plane")->setClass("btn btn-sm btn-warning")->addAttributes(["data-toggle" => "tooltip", "title" => "Odeslat znovu"]);
		$grid->addAction("removeEmail", "")->setIcon("trash")->setClass("btn btn-sm btn-danger")->addAttributes(["data-toggle" => "tooltip", "title" => "Smazat email"]);


		return $grid;
	}

}
