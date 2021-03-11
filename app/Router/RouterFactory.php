<?php

declare(strict_types=1);

namespace App\Router;

use App\Model\Helpers\Hashids;
use App\Model\Router\DbRouterFactory;
use Nette;
use Nette\Application\Routers\Route;
use Nette\Application\Routers\RouteList;


final class RouterFactory
{
	use Nette\StaticClass;



	public function __construct()
	{
	}



	public static function createRouter(DbRouterFactory $dbRouterFactory, Hashids $hashIds): RouteList
	{
		$router = new RouteList;


		$router[] = $admin = new RouteList('Admin');

		$admin[] = new Route('[<locale=cs cs|en>/]admin/<presenter>/<action>[/<id>]', 'Homepage:default');


		$router->add($dbRouterFactory->create());
		$router->addRoute('[<locale=cs cs|en>/]<presenter>/<action>[/<id>]', [
			'presenter' => [
				Route::VALUE => 'Homepage',
				Route::FILTER_TABLE => [
					// řetězec v URL => presenter
				],
			],
			'action' => [
				Route::VALUE => 'default',
				Route::FILTER_TABLE => [
					'admin-webu' => 'administration',
				],
			],
			'id' => [
				Route::VALUE => NULL,
				Route::FILTER_IN => function ($in) use ($hashIds) {
					$decodes = $hashIds->decode($in);
					return reset($decodes);
				},
				Route::FILTER_OUT => function ($out) use ($hashIds) {
					return $hashIds->encode($out);
				},
			],
		]);

		return $router;
	}
}
