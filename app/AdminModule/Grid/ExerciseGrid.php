<?php


namespace App\AdminModule\Grid;



use App\Model\Czechitas\Exercise\Exercise;
use App\Model\Czechitas\Exercise\ExerciseRepository;
use App\Model\Czechitas\Lesson\LessonRepository;
use App\Model\Grid\BaseGridFactory;
use App\Model\Orm;
use Nette\Application\LinkGenerator;

interface ExerciseGridFactory
{

	public function create(): ExerciseGrid;

}



class ExerciseGrid
{

	/** @var BaseGridFactory */
	private $baseGridFactory;

	/** @var ExerciseRepository */
	private $exerciseRepository;

	/** @var LinkGenerator */
	private $linkGenerator;

	/** @var Orm */
	private $orm;

	/** @var LessonRepository */
	private LessonRepository $lessonRepository;



	public function __construct(BaseGridFactory $baseGridFactory, ExerciseRepository $exerciseRepository, LinkGenerator $linkGenerator, Orm $orm, LessonRepository $lessonRepository)
	{
		$this->baseGridFactory = $baseGridFactory;
		$this->exerciseRepository = $exerciseRepository;
		$this->linkGenerator = $linkGenerator;
		$this->orm = $orm;
		$this->lessonRepository = $lessonRepository;
	}



	public function getGrid()
	{
		$grid = $this->baseGridFactory->create("Cvičení");
		//$grid->setOuterFilterRendering(TRUE);
		$grid->setDefaultPerPage(20);
		$grid->setAutoSubmit(FALSE);
		$grid->setDataSource($this->exerciseRepository->findAll()->orderBy(["lesson->dateStart" => "ASC", "orderInLesson" => "ASC"]));
		//$grid->setDefaultSort(["lesson->dateStart" => "ASC", "orderInLesson" => "ASC"], TRUE);
		$grid->setStrictSessionFilterValues(FALSE);
		$grid->setMultiSortEnabled(TRUE);

		$grid->addColumnText("name", "Název")->setSortable();

		$grid->addColumnText('lesson', 'Lekce')->setRenderer(function (Exercise $exercise) {
			return $exercise->getLesson() ? $exercise->getLesson()->getPage()->getName() : NULL;
		})->setSortable();

		$grid->addColumnNumber("orderInLesson", "Pořadí v lekci")
			 ->setSortable()
			 ->setEditableCallback([$this, "inlineEditOrderInLesson"]);


		$grid->addColumnStatus('published', 'Publikováno')
			 ->setSortable()
			 ->setCaret(FALSE)
			 ->addOption(TRUE, 'Ano')
			 ->setClass('btn-success')
			 ->endOption()
			 ->addOption(FALSE, 'Ne')
			 ->setClass('btn-danger')
			 ->endOption()
			->onChange[] = [$this, "inlineEditPublished"];

		$grid->addColumnStatus('publishedSolution', 'Publikováno řešení')
			 ->setSortable()
			 ->setCaret(FALSE)
			 ->addOption(TRUE, 'Ano')
			 ->setClass('btn-success')
			 ->endOption()
			 ->addOption(FALSE, 'Ne')
			 ->setClass('btn-danger')
			 ->endOption()
			->onChange[] = [$this, "inlineEditPublishedSolution"];
		$grid->addColumnNumber("solutions", "Počet souborů řeršení")->setRenderer(function (Exercise $exercise) {
			return $exercise->getExerciseSolutionFiles()->countStored();
		})->setSortable();

		$grid->addFilterText("name", "Vyhledat", ["exercise.name"]);

		$grid->addAction("edit", "")->setIcon("pencil")->setClass("btn btn-sm btn-primary ajax");
		$grid->addAction("detail", "")->setIcon("search")->setClass("btn btn-sm btn-primary");



		return $grid;
	}



	public function inlineEditPublished($id, string $value)
	{
		$id = (int) $id;
		if (is_numeric($id)) {
			$exercise = $this->exerciseRepository->getExerciseById($id);
			if ($exercise) {
				$exercise->setPublished($value);
				$this->orm->persistAndFlush($exercise);
			}
		}
	}



	public function inlineEditPublishedSolution($id, string $value)
	{
		$id = (int) $id;
		if (is_numeric($id)) {
			$exercise = $this->exerciseRepository->getExerciseById($id);
			if ($exercise) {
				$exercise->setPublishedSolution($value);
				$this->orm->persistAndFlush($exercise);
			}
		}
	}

	public function inlineEditOrderInLesson($id, string $value)
	{
		$id = (int) $id;
		if (is_numeric($id)) {
			$exercise = $this->exerciseRepository->getExerciseById($id);
			if ($exercise) {
				$exercise->setOrderInLesson($value);
				$this->orm->persistAndFlush($exercise);
			}
		}
	}
}
