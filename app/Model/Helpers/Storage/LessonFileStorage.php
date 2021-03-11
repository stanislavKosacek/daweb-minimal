<?php


namespace App\Model\Helpers\Storage;

use Nette;
use Nette\Http\FileUpload;
use Nette\SmartObject;
use Nette\Utils\Finder;
use Nette\Utils\Random;

class LessonFileStorage
{

	use SmartObject;

	private $documentDir;



	public function __construct($dir)
	{
		$this->documentDir = $dir;
	}



	/**
	 * @param FileUpload $file
	 * @return string
	 */
	public function upload(FileUpload $file)
	{
		if (!$file->isOk()) {
			throw new Nette\InvalidArgumentException;
		}

		do {
			$name = Random::generate() . '.' . $file->getSanitizedName();
		} while (file_exists($path = $this->documentDir . "/" . $name));


		$file->move($path);

		return $name;
	}



	/**
	 * @param $filename
	 * @throws \Nette\InvalidStateException
	 */
	public function deleteFile($filename)
	{
		if (!file_exists($path = $this->documentDir . "/" .$filename)) {
			throw new Nette\InvalidStateException('Filename was not provided');
		}
		/** @var $file \SplFileInfo */
		foreach (Finder::findFiles($filename)->from($this->documentDir) as $file) {
			@unlink($file->getPathname());
		}
	}

}
