<?php

declare(strict_types=1);

namespace App\Presenters;

use App\Model\Router\CurrentTarget;
use Kdyby\Autowired\AutowireComponentFactories;
use Kdyby\Autowired\AutowireProperties;
use Nette;


final class Error4xxPresenter extends Nette\Application\UI\Presenter
{

	use AutowireProperties;
	use AutowireComponentFactories;

	/** @var CurrentTarget @autowire */
	protected $currentTarget;


	public function startup(): void
	{
		parent::startup();
		if (!$this->getRequest()->isMethod(Nette\Application\Request::FORWARD)) {
			$this->error();
		}

		$this->template->currentTarget = $this->currentTarget->getCurrentTarget();
	}


	public function renderDefault(Nette\Application\BadRequestException $exception): void
	{
		// load template 403.latte or 404.latte or ... 4xx.latte
		$file = __DIR__ . "/templates/Error/{$exception->getCode()}.latte";
		$this->template->setFile(is_file($file) ? $file : __DIR__ . '/templates/Error/4xx.latte');
	}
}
