<?php


namespace App\AdminModule\CzechitasModule\Grid;



use App\Model\Czechitas\Lesson\Lesson;
use App\Model\Czechitas\Lesson\LessonRepository;
use App\Model\Grid\BaseGridFactory;
use App\Model\Page\Page\Page;
use Nette\Application\LinkGenerator;
use Nextras\Dbal\Utils\DateTimeImmutable;

interface LessonGridFactory
{

	public function create(): LessonGrid;

}



class LessonGrid
{

	/** @var BaseGridFactory */
	private $baseGridFactory;

	/** @var LessonRepository */
	private $lessonRepository;

	/** @var LinkGenerator */
	private $linkGenerator;



	public function __construct(BaseGridFactory $baseGridFactory, LessonRepository $lessonRepository, LinkGenerator $linkGenerator)
	{
		$this->baseGridFactory = $baseGridFactory;
		$this->lessonRepository = $lessonRepository;
		$this->linkGenerator = $linkGenerator;
	}



	public function getGrid()
	{
		$grid = $this->baseGridFactory->create("Lekce");
		//$grid->setOuterFilterRendering(TRUE);
		$grid->setDefaultPerPage(20);
		$grid->setAutoSubmit(TRUE);
		$grid->setDataSource($this->lessonRepository->findAll());
		$grid->setDefaultSort(["dateStart" => "ASC"], TRUE);
		$grid->setStrictSessionFilterValues(FALSE);

//		$grid->addColumnImage("Obrázek");
		$grid->addColumnText('name', 'Název')->setSortable()->setRenderer(function (Lesson $lesson) {
			return $lesson->getPage()->getName();
		});

		$grid->addColumnText('type', 'Typ')->setSortable()->setRenderer(function (Lesson $lesson) {
			return Lesson::getTypes()[$lesson->getType()];
		});
		$grid->addColumnText('link', 'Odkaz')->setRenderer(function (Lesson $lesson) {
			$link = $this->linkGenerator->link("Page:default", [$lesson->getPage()->getId()]);
			return "<a href='$link' target='blank'>$link</a>";
		})->setTemplateEscaping(FALSE);
		$grid->addColumnDateTime('dateStart', 'Začátek')
			 ->setSortable()
			 ->setFormat("j. n. Y H:i");
		$grid->addColumnDateTime('dateEnd', 'Konec')
			 ->setSortable()
			 ->setFormat("j. n. Y H:i");

		$grid->addColumnDateTime('published', 'Publikováno')
			 ->setSortable()
			 ->setRenderer(function (Lesson $lesson) {

				 if (!$lesson->getPage()->getPublished()) {
					 return "<span class='label label-danger' data-toggle='tooltip' data-placement='top' title='' data-original-title='Nepublikováno'>
								<i class='fa fa-times-circle'></i>
							</span>";
				 } elseif ($lesson->getPage()->getPublished() < new DateTimeImmutable()) {
					 $time = $lesson->getPage()->getPublished()->format('j. n. Y H:i');
					 return "<span class='label label-primary' data-toggle='tooltip' data-placement='top' title='' data-original-title='Publikováno $time'><i class='fa fa-check-circle'></i></span>";
				 } elseif ($lesson->getPage()->getPublished() > new DateTimeImmutable()) {
					 $time = $lesson->getPage()->getPublished()->format('j. n. Y H:i');
					 return "<span class='label label-info' data-toggle='tooltip' data-placement='top' title='' data-original-title='Naplánováno $time'><i class='fa fa-clock-o'></i></span>";
				 }
			 })->setTemplateEscaping(FALSE);

		$grid->addAction("editLesson", "")->setIcon("pencil")->setClass("btn btn-sm btn-primary ajax");
//		$grid->addAction(":Admin:Czechitas:EditLesson:EditLesson:default", "")->setIcon("search")->setClass("btn btn-sm btn-primary");

		return $grid;
	}

}
