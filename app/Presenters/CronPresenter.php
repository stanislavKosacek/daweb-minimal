<?php

declare(strict_types=1);

namespace App\Presenters;


use Nette\Application\Responses\JsonResponse;

final class CronPresenter extends BasePresenter
{




	public function actionClearCache()
	{
		$dir = __DIR__ . "/../../temp/cache";

		$it = new \RecursiveDirectoryIterator($dir, \RecursiveDirectoryIterator::SKIP_DOTS);
		$files = new \RecursiveIteratorIterator($it,
			\RecursiveIteratorIterator::CHILD_FIRST);
		foreach($files as $file) {
			if ($file->isDir()){
				rmdir($file->getRealPath());
			} else {
				unlink($file->getRealPath());
			}
		}
		rmdir($dir);
		$response = new JsonResponse("Finished");
		$this->sendResponse($response);
	}
}
