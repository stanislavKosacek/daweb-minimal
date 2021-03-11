<?php


namespace App\Model\Grid;


use Contributte\Translation\Translator;
use Nette\ComponentModel\IContainer;
use Ublaboo\DataGrid\DataGrid;

interface BaseGridFactory
{

	public function create($name = NULL): BaseGrid;

}



class BaseGrid extends DataGrid
{



	public function __construct(IContainer $parent = NULL, $name = NULL, Translator $translator)
	{
		parent::__construct($parent, $name);

		$this->setTranslator($translator->createPrefixedTranslator("grid"));
		$this->setTemplateFile(__DIR__ . "/datagrid.latte");
	}



	public function addColumnImage(string $name)
	{
		$this->addColumnText("image", $name)
			 ->setTemplate(__DIR__ . "/thumbnail.latte", [])
			 ->setAlign("center");
	}

}
