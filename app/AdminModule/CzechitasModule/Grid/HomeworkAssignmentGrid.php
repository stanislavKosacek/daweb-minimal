<?php


namespace App\AdminModule\CzechitasModule\Grid;



use App\Model\Czechitas\HomeworkAssignment\HomeworkAssignment;
use App\Model\Czechitas\HomeworkAssignment\HomeworkAssignmentRepository;
use App\Model\Grid\BaseGridFactory;
use Nette\Application\LinkGenerator;

interface HomeworkAssignmentGridFactory
{

	public function create(): HomeworkAssignmentGrid;

}



class HomeworkAssignmentGrid
{

	/** @var BaseGridFactory */
	private $baseGridFactory;

	/** @var LinkGenerator */
	private $linkGenerator;

	/** @var HomeworkAssignmentRepository */
	private $homeworkAssignmentRepository;



	public function __construct(BaseGridFactory $baseGridFactory, HomeworkAssignmentRepository $homeworkAssignmentRepository, LinkGenerator $linkGenerator)
	{
		$this->baseGridFactory = $baseGridFactory;
		$this->linkGenerator = $linkGenerator;
		$this->homeworkAssignmentRepository = $homeworkAssignmentRepository;
	}



	public function getGrid()
	{
		$grid = $this->baseGridFactory->create("Oprava úkolů");
		//$grid->setOuterFilterRendering(TRUE);
		$grid->setDefaultPerPage(20);
		$grid->setAutoSubmit(TRUE);
		$grid->setDataSource($this->homeworkAssignmentRepository->findAll());
		$grid->setDefaultSort(["added" => "ASC"], TRUE);
		$grid->setStrictSessionFilterValues(FALSE);


		$grid->addColumnText('name', 'Název')->setSortable()->setRenderer(function (HomeworkAssignment $homeworkAssignment) {
			return $homeworkAssignment->getPage()->getName();
		});

		$grid->addColumnText('lessonName', 'Lekce')->setSortable()->setRenderer(function (HomeworkAssignment $homeworkAssignment) {
			return $homeworkAssignment->getLesson()->getPage()->getName();
		});

		$grid->addColumnText('type', 'Typ lekce')->setSortable()->setRenderer(function (HomeworkAssignment $homeworkAssignment) {
			return $homeworkAssignment->getLesson()->getTranslatedPageType();
		});

		$grid->addColumnNumber('uncheckedCount', 'Počet nezkontrolovaných úkolů')->setSortable()->setRenderer(function (HomeworkAssignment $homeworkAssignment) {
			return $homeworkAssignment->getUncheckedHomeworkSolutions()->countStored();
		});

		$grid->addColumnDateTime('deadline', 'Deadline')
			 ->setSortable()
			 ->setFormat("j. n. Y H:i");


		$grid->addAction("detail", "")->setIcon("search")->setClass("btn btn-sm btn-primary");

		return $grid;
	}

}
