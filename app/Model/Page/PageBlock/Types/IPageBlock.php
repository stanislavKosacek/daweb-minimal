<?php


namespace App\Model\Page\PageBlock\Types;


use App\Model\Page\Page\Page;
use Nette\Application\UI\Control;

interface IPageBlock
{

	/**
	 * @return string
	 */
	public static function getTypeStatic(): string;



	/**
	 * @return string
	 */
	public static function getTranslatedName(): string;



	public static function createForPage(Page $page): self;



	public function getAdminComponent(): Control;



	public function getFrontendComponent(): Control;

}
