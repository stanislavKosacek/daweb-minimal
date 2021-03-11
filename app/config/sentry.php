<?php
return [
	"parameters" => [
		"sentry" => [
			'dsn' => 'https://c4ec2cdb6eb146fcab13d550fe4a482d@sentry.prod.qop.cz/22',
			'project_root' => __DIR__ . '/../../',
			'environment' => getenv('ENVIRONMENT') ?: 'production',
			'release' => getenv('VERSION') ?: NULL,
			"default_integrations" => FALSE,
			'excluded_exceptions' => [
				\Nette\Application\BadRequestException::class,
				\Nette\Application\ForbiddenRequestException::class,
				\Nette\Application\AbortException::class,
			],
			"in_app_exclude" => [
				__DIR__ . "/../../vendor/",
			],
			"send_default_pii" => TRUE,
		],
	],
];
