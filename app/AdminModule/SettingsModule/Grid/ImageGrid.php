<?php


namespace App\AdminModule\SettingsModule\Grid;



use App\Model\Grid\BaseGridFactory;
use App\Model\Orm;
use App\Model\WebImage\WebImage;
use App\Model\WebImage\WebImageRepository;
use Nette\Utils\Strings;

interface ImageGridFactory
{

	public function create(): ImageGrid;

}



class ImageGrid
{

	/** @var BaseGridFactory */
	private $baseGridFactory;

	/** @var WebImageRepository */
	private $webImageRepository;

	/** @var Orm */
	private $orm;



	public function __construct(BaseGridFactory $baseGridFactory, WebImageRepository $webImageRepository, Orm $orm)
	{
		$this->baseGridFactory = $baseGridFactory;
		$this->webImageRepository = $webImageRepository;
		$this->orm = $orm;
	}



	public function getGrid()
	{

		$grid = $this->baseGridFactory->create("Obrázky");

		$grid->setOuterFilterRendering(TRUE);
		$grid->setCollapsibleOuterFilters(FALSE);
		$grid->setDefaultPerPage(20);
		$grid->setAutoSubmit(FALSE);


		$grid->setDataSource($this->webImageRepository->findAll());
		$grid->setDefaultSort(["added" => "DESC"], TRUE);
		$grid->setStrictSessionFilterValues(FALSE);

		$grid->addColumnImage("Náhled");
		$grid->addColumnText("alt", "Alternativní název")
			 ->setSortable()
			->setEditableCallback([$this, 'inlineEditAlt']);
		$grid->addColumnText("name", "Jméno souboru")->setSortable();
		$grid->addColumnNumber("size", "Velikost")->setSortable()->setRenderer(function (WebImage $webImage) {
			if ($webImage->getSize()) {
				return $this->bytes($webImage->getSize());
			}
			return NULL;
		});

		$grid->addColumnNumber("width", "Šířka")->setSortable();
		$grid->addColumnNumber("height", "Výška")->setSortable();
		$grid->addColumnText("contentType", "Content type")->setSortable();
		$grid->addColumnDateTime("added", "Datum přidání")
			 ->setSortable()
			 ->setFormat("j. n. Y H:i");
		$grid->addAction("edit", "")->setIcon("pencil")->setClass("btn btn-sm btn-primary ajax");
		//$grid->addAction("delete", "")->setIcon("trash")->setClass("btn btn-sm btn-danger");


		$grid->addFilterText("name", "Vyhledat", ["alt", "name", "image"]);

		return $grid;
	}



	public function inlineEditAlt($id, string $value)
	{
		$id = (int) $id;
		if (is_numeric($id)) {
			if ($image = $this->webImageRepository->getWebImageById($id)) {
				$image->setAlt($value);
				$this->orm->persistAndFlush($image);
			}
		}
	}


	public function bytes($bytes, $force_unit = NULL, $format = NULL, $si = TRUE)
	{
		// Format string
		$format = ($format === NULL) ? "%01.2f %s" : (string) $format;

		// IEC prefixes (binary)
		if ($si == FALSE OR strpos($force_unit, "i") !== FALSE)
		{
			$units = array("B", "KiB", "MiB", "GiB", "TiB", "PiB");
			$mod   = 1024;
		}
		// SI prefixes (decimal)
		else
		{
			$units = array("B", "kB", "MB", "GB", "TB", "PB");
			$mod   = 1000;
		}

		// Determine unit to use
		if (($power = array_search((string) $force_unit, $units)) === FALSE)
		{
			$power = ($bytes > 0) ? floor(log($bytes, $mod)) : 0;
		}

		return sprintf($format, $bytes / pow($mod, $power), $units[$power]);
	}

}
