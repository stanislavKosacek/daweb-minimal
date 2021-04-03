<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';
if ('https' === getenv('HTTP_X_FORWARDED_PROTO')) {
	\Nette\Http\Url::$defaultPorts['https'] = (int) getenv('SERVER_PORT');
}
App\Bootstrap::boot()
	->createContainer()
	->getByType(Nette\Application\Application::class)
	->run();
