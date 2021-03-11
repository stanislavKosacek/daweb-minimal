<?php

declare(strict_types=1);

namespace App\Presenters;


use App\AdminModule\CzechitasModule\Components\CodeShare\SetCodeShareFormFactory;
use App\Model\Czechitas\CodeShare\CodeShare;
use App\Model\Czechitas\CodeShare\CodeShareRepository;
use Nette\Application\BadRequestException;

final class CodeSharePresenter extends BasePresenter
{

	/** @var CodeShareRepository @autowire */
	protected $codeShareRepository;

	/** @var CodeShare */
	protected $selectedCodeShare;

	/** @var SetCodeShareFormFactory @autowire */
	protected $setCodeShareFormFactory;



	public function renderDefault($id)
	{
		if (!$id or !$this->selectedCodeShare = $this->codeShareRepository->getCodeShareById($id)) {
			throw new BadRequestException();
		}
		$this->template->codeShare = $this->selectedCodeShare;
	}



	public function actionShare()
	{

	}



	public function createComponentShareForm()
	{
		$control = $this->setCodeShareFormFactory->create();

		$control->onSuccess[] = function(CodeShare $codeShare) {
			$this->redirect("default", ["id" => $codeShare->getId()]);
		};

		return $control->getForm();
	}
}
