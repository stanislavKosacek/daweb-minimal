<?php


namespace App\AdminModule\CzechitasModule\Grid;



use App\Model\Czechitas\CodeShare\CodeShare;
use App\Model\Czechitas\CodeShare\CodeShareRepository;
use App\Model\Grid\BaseGridFactory;
use Nette\Application\LinkGenerator;

interface CodeShareGridFactory
{

	public function create(): CodeShareGrid;

}



class CodeShareGrid
{

	/**
	 * @var BaseGridFactory
	 */
	private $baseGridFactory;

	/** @var CodeShareRepository */
	private $codeShareRepository;

	/** @var LinkGenerator */
	private $linkGenerator;



	public function __construct(BaseGridFactory $baseGridFactory, CodeShareRepository $codeShareRepository, LinkGenerator $linkGenerator)
	{
		$this->baseGridFactory = $baseGridFactory;
		$this->codeShareRepository = $codeShareRepository;
		$this->linkGenerator = $linkGenerator;
	}



	public function getGrid()
	{
		$grid = $this->baseGridFactory->create("Sdílení kódu");
		//$grid->setOuterFilterRendering(TRUE);
		$grid->setDefaultPerPage(20);
		$grid->setAutoSubmit(TRUE);
		$grid->setDataSource($this->codeShareRepository->findAll());
		$grid->setDefaultSort(["added" => "DESC"], TRUE);
		$grid->setStrictSessionFilterValues(FALSE);


		$grid->addColumnText('name', 'Název')->setSortable();
		$grid->addColumnText('link', 'Odkaz')->setRenderer(function (CodeShare $codeShare) {
			$link = $this->linkGenerator->link("CodeShare:default", [$codeShare->getId()]);
			return "<a href='$link' target='blank'>$link</a>";
		})->setTemplateEscaping(FALSE);
		$grid->addColumnDateTime('added', 'Vytvořeno')
			 ->setSortable()
			 ->setFormat("j. n. Y H:i");

		$grid->addAction("edit", "")->setIcon("pencil")->setClass("btn btn-sm btn-primary ajax");

		return $grid;
	}

}
