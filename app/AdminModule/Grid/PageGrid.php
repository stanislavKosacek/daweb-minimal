<?php


namespace App\AdminModule\Grid;



use App\Model\Grid\BaseGridFactory;
use App\Model\Page\Page\Page;
use App\Model\Page\Page\PageRepository;
use Nette\Application\LinkGenerator;
use Nextras\Dbal\Utils\DateTimeImmutable;

interface PageGridFactory
{

	public function create(): PageGrid;

}



class PageGrid
{

	/** @var BaseGridFactory */
	private $baseGridFactory;

	/** @var PageRepository */
	private $pageRepository;

	/** @var LinkGenerator */
	private $linkGenerator;



	public function __construct(BaseGridFactory $baseGridFactory, PageRepository $pageRepository, LinkGenerator $linkGenerator)
	{
		$this->baseGridFactory = $baseGridFactory;
		$this->pageRepository = $pageRepository;
		$this->linkGenerator = $linkGenerator;
	}



	public function getGrid()
	{
		$grid = $this->baseGridFactory->create("Stránky");
		//$grid->setOuterFilterRendering(TRUE);
		$grid->setDefaultPerPage(20);
		$grid->setAutoSubmit(TRUE);
		$grid->setDataSource($this->pageRepository->findBy(["type" => Page::TYPE_DEFAULT]));
		$grid->setDefaultSort(["added" => "DESC"], TRUE);
		$grid->setStrictSessionFilterValues(FALSE);

		$grid->addColumnImage("Obrázek");
		$grid->addColumnText("name", "Název")->setSortable();
		$grid->addColumnText('link', 'Odkaz')->setRenderer(function (Page $page) {
			$link = $this->linkGenerator->link("Page:default", [$page->getId()]);
			return "<a href='$link' target='blank'>$link</a>";
		})->setTemplateEscaping(FALSE);

		$grid->addColumnDateTime('added', 'Vytvořeno')
			 ->setSortable()
			 ->setFormat("j. n. Y H:i");
		$grid->addColumnDateTime('published', 'Publikováno')
			 ->setSortable()
			 ->setRenderer(function (Page $page) {

			 	if (!$page->getPublished()) {
					return "<span class='label label-danger' data-toggle='tooltip' data-placement='top' title='' data-original-title='Nepublikováno'>
								<i class='fa fa-times-circle'></i>
							</span>";
				} elseif ($page->getPublished() < new DateTimeImmutable()) {
			 		$time = $page->getPublished()->format('j. n. Y H:i');
					return "<span class='label label-primary' data-toggle='tooltip' data-placement='top' title='' data-original-title='Publikováno $time'><i class='fa fa-check-circle'></i></span>";
				} elseif ($page->getPublished() > new DateTimeImmutable()) {
					$time = $page->getPublished()->format('j. n. Y H:i');
			 		return "<span class='label label-info' data-toggle='tooltip' data-placement='top' title='' data-original-title='Naplánováno $time'><i class='fa fa-clock-o'></i></span>";
				}
			})->setTemplateEscaping(FALSE);


		$grid->addAction("editPage", "")->setIcon("pencil")->setClass("btn btn-sm btn-primary ajax");
		$grid->addAction("detail", "")->setIcon("search")->setClass("btn btn-sm btn-primary");
		//$grid->addAction("removePage", "")->setIcon("trash")->setClass("btn btn-sm btn-danger")->addAttributes(["data-toggle" => "tooltip", "title" => "Smazat email"]);


		return $grid;
	}

}
